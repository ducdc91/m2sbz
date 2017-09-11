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
use Magento\Catalog\Model\Product\Type;

class Base extends \Funk\SbzImport\Model\Import\Adapter\Type\Product\Data\ClassAbstract
{
    protected $_entity_type_id;
    protected $_default_static_data = array();
    protected $_attribute_set_collection;
    protected $_attribute_set_name_to_id;
    protected $_product_table;
    protected $_website_product_table;
    // static
    static $ATTRIBUTE_SET_COLLECTION =  null;
    static $ATTRIBUTE_SET_NAME_TO_ID =  array();

    static $ENTITY_TYPE_ID 	=  array();
    static $PRODUCT_TABLE 	=  array();
    static $WEBSITE_PRODUCT_TABLE =  array();
    /**
     * Initialize base data and config
     */
    protected function _construct() {

        if(!isset(self::$ATTRIBUTE_SET_COLLECTION)){
              self::$ENTITY_TYPE_ID				    =  $this->_getEntityResource()->getTypeId();
              self::$PRODUCT_TABLE				    =  $this->_resource->getTableName('catalog_product_entity');
              self::$WEBSITE_PRODUCT_TABLE		=  $this->_resource->getTableName('catalog_product_website');

              $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
              $attribute_set_collection= $objectManager->create('\Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection');
              $attribute_set_collection->setEntityTypeFilter(self::$ENTITY_TYPE_ID);
              $this->_attribute_set_collection = $attribute_set_collection;

              foreach ($this->_attribute_set_collection as $attribute_set) {
                  $attribute_id					=  $attribute_set->getId();
                  self::$ATTRIBUTE_SET_COLLECTION[$attribute_id] = $attribute_set ;
                  $this->_attribute_set_name_to_id[$attribute_set->getAttributeSetName()] = $attribute_set->getId();
              }
              $this->_attribute_set_collection  =  self::$ATTRIBUTE_SET_COLLECTION ;
        }else{
          $this->_attribute_set_collection = self::$ATTRIBUTE_SET_COLLECTION;
          $this->_attribute_set_name_to_id   = self::$ATTRIBUTE_SET_NAME_TO_ID;
		    }
       
        $this->_entity_type_id         	= self::$ENTITY_TYPE_ID	;
        $this->_product_table         	= self::$PRODUCT_TABLE ;
        $this->_website_product_table  	= self::$WEBSITE_PRODUCT_TABLE ;

        $this->_default_static_data = array(
          "sku"              => '',
          "attribute_set_id" => $this->_getDefaultAttributeSet()->getId(),
          "type_id"          => Type::DEFAULT_TYPE,
          "created_at"       => date('Y-m-d H:i:s'),
          "updated_at"       => date('Y-m-d H:i:s'),
        );
    }
    protected function _getEntityResource()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product');
        return $resource;
    }

    protected function _getDefaultAttributeSet()
    {
        $default_attribute_set_id = $this->_getEntityResource()->getEntityType()->getDefaultAttributeSetId();
        return $this->_attribute_set_collection[$default_attribute_set_id];
    }
    public function processData(array &$data)
    {

        if (isset($data['attribute_set'])) {
            if (isset($this->_attribute_set_name_to_id[$data['attribute_set']])) {
                $data['attribute_set_id'] = $this->_attribute_set_name_to_id[$data['attribute_set']];
                unset($data['attribute_set']);
            } else {
                $this->_throwException($this->_helper->__('Unknown attribute set.'));
            }
        }
        $data['_is_new'] = false;
        if ($data['product_id']) {
            $productId = $data['product_id'];
            $baseData  = $this->arrayIntersectKeys($data, $this->_default_static_data);
			      $baseData['updated_at'] = date('Y-m-d H:i:s');
            $this->_writeConnection->update($this->_product_table, $baseData, "entity_id = {$productId}");
        } else {
            $baseData           = array_merge($this->_default_static_data, $data);

            $baseData           = $this->arrayIntersectKeys($baseData, $this->_default_static_data);
            $this->_writeConnection->insert($this->_product_table, $baseData);
            $productId          = $this->_writeConnection->lastInsertId();
            $data['product_id'] = $productId;
            $data['_is_new']    = true;
        }

        if (isset($data['website_id'])) {
            $websiteIds         = explode(',', $data['website_id']);
            $websites           = $this->_config->getWebsites();
            $data['website_id'] = array();
            foreach ($websiteIds as $websiteId) {
                if (in_array($websiteId, $websites)) {
                    $data['website_id'][] = $websiteId;
                }
            }
        }

        if (isset($data['websites'])) {
            $websiteCodes = explode(',', $data['websites']);
            $websites     = $this->_config->getWebsites();
            foreach ($websiteCodes as $websiteCode) {
                if (isset($websites[$websiteCode])) {
                    $data['website_id'][] = $websites[$websiteCode];
                }
            }
        }

        if (!isset($data['website_id']) || empty($data['website_id'])) {
            $data['website_id'][] = $this->_config->getDefaultWebsiteId();
        }

        foreach ($data['website_id'] as $id) {
            $this->_writeConnection->insertOnDuplicate($this->_website_product_table,
                array('product_id' => $productId, 'website_id' => $id)
            );
        }
        return $this;
    }
}