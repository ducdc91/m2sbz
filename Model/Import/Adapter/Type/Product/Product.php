<?php

namespace Funk\SbzImport\Model\Import\Adapter\Type\Product;

class Product
    extends \Funk\SbzImport\Model\Import\Adapter\ClassAbstract
{

    protected $_dataProcessors;
    protected $_helper;
    protected $_reflectionHelper;
    protected $_resourceConnection;
    protected $_PRODUCT_TABLE;
    protected $_skuToId;
    /**
     * @return Mage_Catalog_Model_Resource_Product
     */
    static $ENTITY_TYPE_ID;
    static $RESOURCE_CONNECTION;
    static $PRODUCT_TABLE;

    public function setImportData($data)
    {
        $this->_skuToId = $data["sku"];
    }

    protected function _getEntityTypeId()
    {
        if (!isset(self::$ENTITY_TYPE_ID)) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product');
            self::$ENTITY_TYPE_ID = $resource->getTypeId();
        }
        $entityTypeID = self::$ENTITY_TYPE_ID;
        return $entityTypeID;
    }

    protected function _construct()
    {
        if (!isset(self::$PRODUCT_TABLE)) {
            self::$RESOURCE_CONNECTION = $this->_config->getResourceConnection();
            self::$PRODUCT_TABLE = $this->_config->getResource()->getTableName('catalog/product');
        }

        $this->_resourceConnection = self::$RESOURCE_CONNECTION;
        $this->_PRODUCT_TABLE = self::$PRODUCT_TABLE;
        // list type need operator to db
        $this->_dataProcessors = array(
            0 => new \Funk\SbzImport\Model\Import\Adapter\Type\Product\Data\Base($this),
            1 => new \Funk\SbzImport\Model\Import\Adapter\Type\Product\Data\Category($this),
            2 => new \Funk\SbzImport\Model\Import\Adapter\Type\Product\Data\Eav($this),
            3 => new \Funk\SbzImport\Model\Import\Adapter\Type\Product\Data\Inventory($this),
            4 => new \Funk\SbzImport\Model\Import\Adapter\Type\Product\Data\Media($this),
        );
        return $this;
    }

    public function beforePrepare()
    {
        foreach ($this->_dataProcessors as $processor) {
            $processor->beforePrepare();
        }
        return $this;
    }

    public function prepareData(array &$data)
    {
        foreach ($this->_dataProcessors as $processor) {
            $processor->prepareData($data);
        }
        return $this;
    }

    public function afterPrepare()
    {
        foreach ($this->_dataProcessors as $processor) {
            $processor->afterPrepare();
        }

        return $this;
    }

    public function beforeProcess()
    {
        foreach ($this->_dataProcessors as $processor) {
            $processor->beforeProcess();
        }
        return $this;
    }

    protected function _prepareAndValidateData(array &$data)
    {
        if (!isset($data['sku']) || !$data['sku'] || !trim($data['sku'])) {
            $this->_throwException($this->_helper->__('Product SKU is empty or not found.'));
        }
        $data['sku'] = trim($data['sku']);

        if (isset($data['type'])) {
            $data['type_id'] = $data['type'];
            unset($data['type']);
        }
        return $data;
    }

    public function processData(array &$data)
    {
        $this->_prepareAndValidateData($data);
        $this->_resourceConnection->beginTransaction();
        try {
            foreach ($this->_dataProcessors as $processor) {
                $processor->processData($data);
            }
            $this->_resourceConnection->commit();
        } catch (Exception $e) {
            $this->_resourceConnection->rollBack();
            throw $e;
        }
        return true;
    }

    public function afterProcess()
    {
        foreach ($this->_dataProcessors as $processor) {
            $processor->afterProcess($this->_skuToId);
        }
        return $this;
    }
}
