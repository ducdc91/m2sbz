<?php
/**
 * SupplierConnect - Magento Extension
 *
 * @package SupplierConnect
 * @category FunkExtensions
 * @copyright Copyright 2014 FunkExtensions.
 * @version: 0.1.0
 */

namespace Funk\SbzImport\Model\Import\Adapter;

class Config
{
    /** @var  int */
    protected $_entityTypeId;
    protected $_attributeCodes;
    protected $_beforeProcessCallback;
    protected $_canCreateNewEntity = true;
    protected $_canCreateOptions = true;
    protected $_canCreateCategories = true;
    protected $_canDownloadMedia = true;
    protected $_websiteCollection;
    protected $_websites;
    protected $_stores;
    protected $_defaultWebsiteId;
    protected $_optionCorrectionFactor = 15;
    protected $_resource;
    protected $_resourceConnection;
    protected $_optionDelimiter = ',';
    protected $_storeId;
    protected $_store;

    public function __construct(array $attributeCodes
    )
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        if (empty($attributeCodes)) {
            throw new \Funk\SbzImport\Model\Import\Adapter\Exception(
                Mage::helper('funkextensions_supplierconnect')->__('Please specify attributes.'));
        }
        $this->_attributeCodes = $attributeCodes;
        //1 get connection db of magento
        $resource = $objectManager->create('\Magento\Framework\App\ResourceConnection');
        $this->_resource = $resource;
        $this->_resourceConnection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        // get website collection
        $websiteCollection = $objectManager->create('Magento\Store\Model\ResourceModel\Website\Collection');
        $this->_websiteCollection = $websiteCollection;
        // assign default wesbite to config
        foreach ($websiteCollection as $website) {
            /** @var $website Mage_Core_Model_Website */
            $this->_websites[$website->getCode()] = $website->getId();
            if ($website->getIsDefault()) {
                $this->_defaultWebsiteId = $website->getId();
            }
        }
        // assign store id to config ;
        $storeCollection = $objectManager->create('Magento\Store\Model\ResourceModel\Store\Collection');
        foreach ($storeCollection as $store) {
            /** @var $store Mage_Core_Model_Store */
            $this->_stores[$store->getCode()] = $store->getId();
        }
    }

    public function getHelper()
    {
        return \Magento\Framework\App\ObjectManager::getInstance()->get('Magento\Core\Helper\Data');
    }

    public function getResource()
    {
        return $this->_resource;
    }

    public function getResourceConnection()
    {
        return $this->_resourceConnection;
    }

    public function getAttributeCodes()
    {
        return $this->_attributeCodes;
    }

    public function setEntityTypeId($entityTypeId)
    {
        $this->_entityTypeId = $entityTypeId;

        return $this;
    }

    public function getEntityTypeId()
    {
        return $this->_entityTypeId;
    }

    /**
     * Setting function which will be called before row processing
     *
     *
     * @param string $callBack
     * @return $this
     */
    public function setBeforeProcessCallback($callBack)
    {
        $this->_beforeProcessCallback = $callBack;

        return $this;
    }

    /**
     * @return string
     */
    public function getBeforeProcessCallback()
    {
        return $this->_beforeProcessCallback;
    }

    /**
     * @param bool $flag
     * @return $this
     */
    public function setCanCreateNewEntity($flag)
    {
        $this->_canCreateNewEntity = (bool)$flag;

        return $this;
    }

    /**
     * @return bool
     */
    public function getCanCreateNewEntity()
    {
        return $this->_canCreateNewEntity;
    }

    /**
     * @param bool $flag
     * @return $this
     */
    public function setCanCreateOptions($flag)
    {
        $this->_canCreateOptions = (bool)$flag;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCanCreateOptions()
    {
        return $this->_canCreateOptions;
    }

    /**
     * @param bool $flag
     * @return $this
     */
    public function setCanCreateCategories($flag)
    {
        $this->_canCreateCategories = (bool)$flag;
        return $this;
    }

    /**
     * @return bool
     */
    public function getCanCreateCategories()
    {
        return $this->_canCreateCategories;
    }

    /**
     * @param bool $flag
     * @return $this
     */
    public function setCanDownloadMedia($flag)
    {
        $this->_canDownloadMedia = (bool)$flag;

        return $this;
    }

    /**
     * @return bool
     */
    public function getCanDownloadMedia()
    {
        return $this->_canDownloadMedia;
    }

    /**
     * @return array
     */
    public function getWebsites()
    {
        return $this->_websites;
    }

    /**
     * @return array
     */
    public function getStores()
    {
        return $this->_stores;
    }

    /**
     * @return null|Mage_Core_Model_Website
     */
    public function getDefaultWebsite()
    {
        /** @var $website Mage_Core_Model_Website */
        foreach ($this->_websiteCollection as $website) {
            if ($website->getIsDefault()) {
                return $website;
            }
        }
    }

    /**
     * @return int|mixed
     */
    public function getDefaultWebsiteId()
    {
        return $this->_defaultWebsiteId;
    }

    /**
     * @return Mage_Core_Model_Store
     */
    public function getStore()
    {
        if (is_null($this->_store)) {
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $storeFactory = $objectManager->create('Magento\Store\Model\StoreFactory')->create();
            $store = $storeFactory->load($this->_storeId);
            if (is_null($store->getId())) {
                $store = $this->getDefaultWebsite()->getDefaultStore();
            }
            $this->_store = $store;
        }
        return $this->_store;
    }

    /**
     * @return Mage_Core_Model_Website
     */
    public function getWebsite()
    {
        return $this->getStore()->getWebsite();
    }

    /**
     * @param int $factor
     * @return $this
     */
    public function setOptionCorrectionFactor($factor)
    {
        $this->_optionCorrectionFactor = (int)$factor;
        return $this;
    }

    /**
     * @return int
     */
    public function getOptionCorrectionFactor()
    {
        return $this->_optionCorrectionFactor;
    }

    /**
     * @param string $delimiter
     * @return $this
     */
    public function setOptionDelimiter($delimiter)
    {
        if (strlen($delimiter)) {
            $this->_optionDelimiter = $delimiter;
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getOptionDelimiter()
    {
        return $this->_optionDelimiter;
    }

    /**
     * @param int $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->_storeId = $storeId;
        return $this;
    }
}