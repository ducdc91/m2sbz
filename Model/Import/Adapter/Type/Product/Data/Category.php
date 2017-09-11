<?php
/**
 * SupplierConnect - Magento Extension
 *
 * @package SupplierConnect
 * @category FunkExtensions
 * @copyright Copyright 2014 FunkExtensions.
 * @version: 0.1.0
 */

namespace Funk\SbzImport\Model\Import\Adapter\Type\Product\Data;

class Category
    extends \Funk\SbzImport\Model\Import\Adapter\Type\Product\Data\ClassAbstract
{
    protected $_category_tree = array();
    protected $_category_product_table;
    //static varible
    static $CATEGORY_TREE;
    static $CATEGORY_PRODUCT_TABLE;

    /**
     * Initialize base data and config
     */
    protected function _construct()
    {
        if (!isset(self::$CATEGORY_PRODUCT_TABLE)) {
            self::$CATEGORY_PRODUCT_TABLE = $this->_resource->getTableName('catalog_category_product');
        }
        $this->_category_product_table = self::$CATEGORY_PRODUCT_TABLE;
    }

    protected function _initializeCategoryTree()
    {
        if (!isset(self::$CATEGORY_TREE)) {
            $root_category_id = $this->_store->getRootCategoryId();
            if (!$root_category_id) {
                $root_category_id = $this->_config->getDefaultWebsite()->getDefaultStore()->getRootCategoryId();
            }
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeFactory = $objectManager->create('Magento\Catalog\Model\Category');

            $root_category = $storeFactory->load($root_category_id);


            if (is_null($root_category->getId())) {
                throw new \Funk\SbzImport\Model\Import\Adapter\Exception('Root category not found.');
            }

            $collection = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Category\Collection');
            $collection->addAttributeToSelect(array('name', 'path', 'parent_id'));
            $collection->addIdFilter($root_category->getChildren());
            self::$CATEGORY_TREE[$root_category->getName()] = $this->_prepareCategoryTree($root_category, $collection);
        }

        $this->_category_tree = self::$CATEGORY_TREE;
        return $this;
    }

    protected function _prepareCategoryTree(
        $category,
        \Magento\Catalog\Model\ResourceModel\Category\Collection $collection)
    {
        $child_categories = $collection->getItemsByColumnValue('parent_id', $category->getId());
        $children = array();
        if (!empty($child_categories)) {
            foreach ($child_categories as $sub_category) {
                $children[$sub_category->getName()] = $this->_prepareCategoryTree($sub_category, $collection);
            }
        }

        return array('category' => $category, 'children' => $children);
    }

    public function beforePrepare()
    {
        $this->_initializeCategoryTree();
        return $this;
    }

    public function prepareData(array &$data)
    {
        if ($this->_config->getCanCreateCategories()) {
            $this->_prepareCategories($data);
        }

        return $this;
    }


    public function afterPrepare()
    {
        if ($this->_config->getCanCreateCategories()) {
            $this->_writeConnection->beginTransaction();
            try {
                foreach ($this->_category_tree as $name => $data) {
                    $this->_saveCategoryTree($this->_category_tree[$name]);
                }
                $this->_writeConnection->commit();
            } catch (Exception $e) {
                $this->_writeConnection->rollBack();
                throw $e;
            }
        }

        return $this;
    }

    public function beforeProcess()
    {
        $this->_initializeCategoryTree();
        return $this;
    }

    public function processData(array &$data)
    {
        try {
            if (isset($data['categories']) && $data['categories'] || isset($data['category_ids']) && $data['category_ids']) {
                if (isset($data['category_ids']) && $data['category_ids']) {
                    $category_ids = explode(',', $data['category_ids']);
                    foreach ($category_ids as $key => $id) {
                        $category_ids[$key] = intval($id);
                    }
                } else {
                    $category_ids = array();
                }

                if (isset($data['categories']) && $data['categories']) {
                    $categories = explode(',', $data['categories']);
                    foreach ($categories as $category_path) {
                        foreach ($this->_category_tree as $root_node) {
                            $category_ids = array_merge(
                                $category_ids,
                                $this->getCategoryPathIds(explode('/', $category_path), $root_node)
                            );
                        }
                    }
                }

                $category_ids = array_unique($category_ids);

                $where = sprintf('product_id = %d', $data['product_id']);
                if (!empty($category_ids)) {
                    $where .= sprintf(' AND category_id NOT IN (%s)', implode(',', $category_ids));
                }
                $this->_writeConnection->delete($this->_category_product_table, $where);

                if (!empty($category_ids)) {
                    foreach ($category_ids as $categoryId) {
                        $this->_writeConnection->insertOnDuplicate(
                            $this->_category_product_table,
                            array('product_id' => $data['product_id'], 'category_id' => $categoryId, 'position' => 1)
                        );
                    }
                }
            }
        } catch (Exception $e) {
            throw new \Funk\SbzImport\Model\Import\Adapter\Exception("Can't save category relation.");
        }

        return $this;
    }

    protected function _prepareCategories(array &$data)
    {
        if (isset($data['categories']) && $data['categories']) {
            $categories = explode(',', $data['categories']);
            foreach ($categories as $categoryPath) {
                foreach ($this->_category_tree as $rootCategoryName => $categoryData) {
                    $this->_updateCategoryTree(explode('/', $categoryPath), $this->_category_tree[$rootCategoryName]);
                }
            }
        }

        return $this;
    }

    protected function _updateCategoryTree(array $categoryPath, array &$treeNode)
    {
        if ($categoryName = array_shift($categoryPath)) {
            if (!isset($treeNode['children'][$categoryName])) {
                $treeNode['children'][$categoryName] = array(
                    'category' => null,
                    'children' => array(),
                );
            }
            $this->_updateCategoryTree($categoryPath, $treeNode['children'][$categoryName]);
        }

        return $this;
    }


    protected function _saveCategoryTree(array &$node)
    {
        foreach ($node['children'] as $categoryName => $categoryData) {
            if (is_null($categoryData['category'])) {
                $category = Mage::getModel('catalog/category');
                $category->addData(array(
                    'path' => $node['category']->getData('path'),
                    'name' => $categoryName,
                    'is_active' => true,
                ));
                $category->getResource()->save($category);
                $node['children'][$categoryName]['category'] = $category;
            }
            $this->_saveCategoryTree($node['children'][$categoryName]);
        }

        return $this;
    }

    public function getCategoryPathIds(array $path, array $rootNode)
    {
        $result = array();
        $categories = $rootNode['children'];
        while ($name = array_shift($path)) {
            if (isset($categories[$name])) {
                $result[] = $categories[$name]['category']->getId();
                $categories = $categories[$name]['children'];
            }
        }

        return $result;
    }
}