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

class Eav
    extends \Funk\SbzImport\Model\Import\Adapter\Type\Product\Data\ClassAbstract
{

    protected $_attribute_codes;
    protected $_attribute_collection;
    protected $_attribute_option_cache = array();
    protected $_required_eav_data = array();
    protected $_attribute_set_collection;
    protected $_static_attributes       = array();
    protected $_eav_attributes          = array();
    protected $_customSourceAttributes = array();//array("status", "visibility", "tax_class_id");

    // static varible
    static $ATTRIBUTES =  array();
    static $ATTRIBUTE_CODES =  array();
    static $ATTRIBUTE_COLLECTIONS =  array();
    static $EAV_ATTRIBUTES =  array();
    static $STATIC_ATTRIBUTES =  array();
    static $ATTRIBUTE_OPTIONCACHE =  array();

    protected function _construct() {
        $this->_required_eav_data = array(
            "status"           =>  \Magento\Catalog\Model\Product\Attribute\Source\Status::STATUS_ENABLED,
            "visibility"       => \Magento\Catalog\Model\Product\Visibility::VISIBILITY_BOTH,
            "weight"           => 1,
            "price"            => 0,
            "tax_class_id"     => 0,
        );


        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $attribute_set_collection= $objectManager->create('\Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection');
        $attribute_set_collection->setEntityTypeFilter($this->_config->getEntityTypeId());
        $this->_attribute_set_collection = $attribute_set_collection;
        $this->initializeAttributes($this->_config->getAttributeCodes());

        return $this;
    }

    public function prepareData(array &$data) {

        return $this;
    }

    public function afterPrepare() {


        return $this;
    }

    public function initializeAttributes(array $attribute_codes) {
        // Ignore media attributes
        $attribute_codes = array_diff($attribute_codes,
            array('images', 'exclude_images', 'image', 'small_image', 'thumbnail', 'category_ids'));

        // Merge with required attributes
        $this->_attribute_codes = array_merge(array_keys($this->_required_eav_data), $attribute_codes);
		    self::initCache($this);
        return $this;
    }

    protected function _prepareEavData(array &$data) {

        if ($data['_is_new']) {
            if (!isset($data['visibility'])) {
                $data['visibility'] = array_search($this->_required_eav_data['visibility'], $this->_attribute_option_cache['visibility']);
            }
            if (!isset($data['status'])) {
                $data['status'] = array_search($this->_required_eav_data['status'], $this->_attribute_option_cache['status']);//$this->_attributeOptionCache['status'][$this->_requiredEavData['status']];
            }
            if (!isset($data['tax_class_id'])) {
                $data['tax_class_id'] = array_search($this->_required_eav_data['tax_class_id'], $this->_attribute_option_cache['tax_class_id']);//$this->_attributeOptionCache['tax_class_id'][$this->_requiredEavData['tax_class_id']];
            }
        }

        return $this;
    }

    public function processData(array &$data)
    {
        // Set default data for system attributes if require
        $this->_prepareEavData($data);

        // Update attributes
		
        foreach ($this->_eav_attributes as $attributeCode => $attributeId) {
		   if($attributeId) {
            /** @var $attribute Mage_Eav_Model_Attribute */
            $attribute   = $this->_attribute_collection[$attributeCode];
            $value       = isset($data[$attributeCode]) ? trim($data[$attributeCode]) : null;
            $backendType = $attribute->getBackendType();
            if (('decimal' == $backendType || 'datetime' == $backendType) && '' === $value) {
                $value = null;
            }
            if (!is_null($value)) {
                $table = $attribute->getBackendTable();
                $values = array(
                    'entity_id'      => $data['product_id'],
                    'attribute_id'   => $attributeId,
                    'store_id'       => $this->_store->getId(),
                    'value'          => $value,
                );
                if (!$data['_is_new'] && self::DELETE_VALUE_FLAG == $value) {
                    $where = array(
                        $this->_writeConnection->quoteInto('entity_id = ?', $values['entity_id']),
                        $this->_writeConnection->quoteInto('attribute_id = ?', $attributeId),
                        $this->_writeConnection->quoteInto('entity_type_id = ?', $this->_config->getEntityTypeId()),
                        $this->_writeConnection->quoteInto('store_id = ?', $values['store_id']),
                    );
                    $this->_writeConnection->delete($table, implode(' AND ', $where));
                } else {
                    if ($attribute->usesSource()) {
                        if ('multiselect' == $attribute->getData('frontend_input')) {
                            $labels     = explode($this->_config->getOptionDelimiter(), $values['value']);
                            $optionsIds = array();
                            foreach ($labels as $label) {
                                if (isset($this->_attribute_option_cache[$attributeCode][$label])) {
                                    $optionsIds[] = $this->_attribute_option_cache[$attributeCode][$label];
                                }
                            }
                            $values['value'] = implode(',', array_unique($optionsIds));
                        } else {
                            if (isset($this->_attribute_option_cache[$attributeCode][$values['value']])) {
                                $values['value'] = $this->_attribute_option_cache[$attributeCode][$values['value']];
                            }
                        }
                    }
                    if (!is_null($values['value'])) {
                        $this->_writeConnection->insertOnDuplicate($table, $values);
                    }
                }
            }
		  }
        }

        return $this;
    }

	
	public static function initCache(&$object){
		// Initialize attribute collection
		$attribute_codes  = $object->_attribute_codes ;
		foreach($attribute_codes  as $code){
        if(!in_array($code,self::$ATTRIBUTE_CODES)){
              $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
              $attribute_collection= $objectManager->create('\Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection');

              $attribute_collection->setItemObjectClass('\Magento\Catalog\Model\ResourceModel\Eav\Attribute');
              $attribute_collection->addFieldToSelect('*');
              $attribute_collection->addFieldToFilter('attribute_code', $code);

              // assign attribute ;
              $attribute = $attribute_collection->getFirstItem() ;
              $attribute->setStoreId($object->_store->getId());

              self::$ATTRIBUTE_COLLECTIONS[$code] 	= $attribute ;
              self::$ATTRIBUTE_CODES[] 				= $code ;
              if (\Magento\Eav\Model\Entity\Attribute\AbstractAttribute::TYPE_STATIC != $attribute->getBackendType()) {
                   self::$EAV_ATTRIBUTES[$code] 			= $attribute->getId();
                   $object->_eav_attributes[$code] 		=  self::$EAV_ATTRIBUTES[$code]  ;
              }else{
                  self::$STATIC_ATTRIBUTES[$code]  		= 	 $attribute->getId();
                  $object->_static_attributes[$code] 		=     self::$STATIC_ATTRIBUTES[$code]  ;
              }
              $object->_attribute_collection[$code] 	=  self::$ATTRIBUTE_COLLECTIONS[$code] ;
              if ($attribute->usesSource()) {
                  $cacheOption  = self::arrayToOptionHash(
                  $attribute->getSource()->getAllOptions(),
                      'value',
                     'label',
                    false
                  );
                  self::$ATTRIBUTE_OPTIONCACHE[$code]  = $cacheOption ;
                  $object->_attribute_option_cache[$code] = $cacheOption ;
              }
        }else{
                $attribute 		= self::$ATTRIBUTE_COLLECTIONS[$code] ;

                if (\Magento\Eav\Model\Entity\Attribute\AbstractAttribute::TYPE_STATIC != $attribute->getBackendType()) {
                  $object->_eav_attributes[$code] 		=  self::$EAV_ATTRIBUTES[$code]  ;
                }else{
                  $object->_static_attributes[$code] 		=     self::$STATIC_ATTRIBUTES[$code]  ;
                }

                $object->_attribute_collection[$code] 	=  self::$ATTRIBUTE_COLLECTIONS[$code] ;

                if ($attribute->usesSource()) {
                  $object->_attribute_option_cache[$code] =	self::$ATTRIBUTE_OPTIONCACHE[$code]  ;
                }
        }
		}
		
	}
  static function arrayToOptionHash(array $array, $keyField, $valueField, $withEmpty = true) {
    $result = array();
    if ($withEmpty) {
      $result[''] = '-- Please Select --';
    }
    foreach ($array as $item) {
      /** @var $item Varien_Object */

      $new_key = $item[$valueField];
      if(is_object($new_key)){
        $value = (string) $new_key->getText();
      }else{
        $value = $new_key;
      }



      $result[$value] = $item[$keyField];
    }

    return $result;
  }
}