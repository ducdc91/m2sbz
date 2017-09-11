<?php
namespace Funk\SbzImport\Model\Config\Category;

class Categories implements \Magento\Framework\Option\ArrayInterface
{
    protected $_categoryHelper;
    protected $_categoryFlatConfig;

    function __construct(\Magento\Catalog\Helper\Category $categoryHelper, \Magento\Catalog\Model\Indexer\Category\Flat\State $categoryFlatState) {
        $this->_categoryHelper = $categoryHelper;
        $this->_categoryFlatConfig = $categoryFlatState;
    }

    public function toOptionArray()
    {
        $categories = $this->getListCatOfStore();
        return $categories;
    }

    public function getListCatOfStore() {
        $categories = $this->getStoreCategories(true,false,true);
        $data = $this->_processArrData($categories);
        return $data;
    }

    private function _processArrData($categories, $data = []) {
        foreach ($categories as $category){
            $cat_name = $category->getName();

            $cat_id = $category->getId();
            $_value = ['value' => $cat_id, 'label'=> __($cat_name)];
            // load cat children
            if ($childrenCategories = $this->getChildCategories($category)) {

            }
            $data[] = $_value;


        }

        return $data;
    }
    /**
     * @param bool $sorted
     * @param bool $asCollection
     * @param bool $toLoad
     *
     * @return \Magento\Framework\Data\Tree\Node\Collection
     */
    protected function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }

    /**
     * @param $category
     *
     * @return array
     */
    protected function getChildCategories($category)
    {
        if ($this->_categoryFlatConfig->isFlatEnabled() && $category->getUseFlatResource()) {
            $subcategories = (array)$category->getChildrenNodes();
        } else {
            $subcategories = $category->getChildren();
        }
        return $subcategories;
    }
}
