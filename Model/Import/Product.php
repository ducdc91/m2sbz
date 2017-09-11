<?php
/**
 * SupplierConnect - Magento Extension
 *
 * @package SupplierConnect
 * @category FunkExtensions
 * @copyright Copyright 2014 FunkExtensions.
 * @version: 0.1.0
 */
namespace Funk\SbzImport\Model\Import;
use Magento\Backend\App\Area\FrontNameResolver;

use Funk\SbzImport\Model\Import\Adpter\Exception;
use Magento\Framework\App\ObjectManagerFactory;

class Product extends \Magento\Framework\Model\AbstractModel
{
      protected 	$_attributeCodes 	= 	array();
      protected	$_collection;
      protected	$_supplier_id;
      protected	$_objectManager;
      protected	$_objectManagerFactory;
      //static varible
      static $OPERATORS= array();
      static $ADAPTER_CLASS_NAME;
      static $PRODUCT_URL_MODEL;
      static $PRODUCT_TABLE_NAME;
      static $CONNECTION;
      static $FIELDS_MAPPING;


      public function __construct(
          \Magento\Framework\App\ResourceConnection $resource,
        ObjectManagerFactory $objectManagerFactory
      ) {
        $this->_objectManagerFactory = $objectManagerFactory;
        $this->_resource = $resource;
      }
      public function init() {
          self::$PRODUCT_TABLE_NAME			  = $this->_resource->getTableName('catalog/product');
          self::$CONNECTION					      = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
          return true;
      }

      public function getHelper() {
          return null;//Mage::helper('funkextensions_supplierconnect');
      }

      public function getItems() {
          return $this->_collection;
      }

      public function getAdapterClassName() {

          if(!isset(self::$ADAPTER_CLASS_NAME)){
            $adapterClassName = '\Funk\SbzImport\Model\Import\Adapter\Type\Product\Product';
            self::$ADAPTER_CLASS_NAME = $adapterClassName ;
          }
          $adapterClassName = self::$ADAPTER_CLASS_NAME ;
          return $adapterClassName;
      }

      public function import($data) {

              $data 			= $this->getValues($data);
              $adapter    	= 	$this->_initializeAdapter();
              $operatorConfig   	= new \Funk\SbzImport\Model\Import\Operator\Config($adapter);
              $operator 			= new \Funk\SbzImport\Model\Import\Operator($operatorConfig);
              $operator->run($data);
              $this->reindexSystemData();

      }


      protected function reindexSystemData(){
            $indexer = $this->getObjectManager()->create(
              'Magento\Indexer\Model\Indexer'
            );
            $indexerList = array(
                        'catalog_category_product',
                        'catalog_product_category',
                        'catalog_product_price',
                        'catalog_product_attribute',
                        'cataloginventory_stock',
                        'catalogrule_product',
                        'catalogsearch_fulltext'

            );
            foreach($indexerList as $item){
              $indexer->load($item);
              $indexer->reindexAll();
            }
      }

      protected function getObjectManager() {
            if (null == $this->_objectManager) {
              $area = FrontNameResolver::AREA_CODE;
              $this->_objectManager = $this->_objectManagerFactory->create($_SERVER);
              /** @var \Magento\Framework\App\State $appState */
              $appState = $this->_objectManager->get('Magento\Framework\App\State');
              $appState->setAreaCode($area);
              $configLoader = $this->_objectManager->get('Magento\Framework\ObjectManager\ConfigLoaderInterface');
              $this->_objectManager->configure($configLoader->load($area));
            }
            return $this->_objectManager;
      }

      public function getValues($data){
          $new_data  = array();

          if(is_null(self::$FIELDS_MAPPING)){
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $fields= $objectManager->create('\Funk\SbzImport\Model\ResourceModel\FieldsMapping\Collection');
            foreach($fields as $field){
              self::$FIELDS_MAPPING[$field->getMageField()] =  $field->getBszField();
            }
          }
          $new_data["product_id"] = $data["product_id"];
          $new_data["images"] = $data["images"];
          $new_data["qty"] = 100;
          $new_data["category_ids"] = 38;
          foreach(self::$FIELDS_MAPPING as $k=>$v)
          {
            $value = "";
            if(isset($data[$v])){
              $value = $data[$v];
            }
            $new_data[$k] = $value;
          }
          $this->_attributeCodes	= array_keys($new_data);
          return $new_data;
      }
      protected function _initializeAdapter() {
            $attributeCodes = $this->_attributeCodes;

            // Initialize data adapter
            $adapterClassName = $this->getAdapterClassName();
            $adapterConfig    =   self::getOperator($attributeCodes);
            return new $adapterClassName($adapterConfig);
      }

      // make unique key by code
      public static function genKeyByAttributeCode($attributeCodes){
        $str_key ="";
        foreach($attributeCodes as $v){
          $str_key .= $v ."_";
        }
        return $str_key;
      }

      public static function getOperator($attributeCodes){
            $code  = self::genKeyByAttributeCode($attributeCodes);
            $keys = array_keys(self::$OPERATORS);
            if(!in_array($code,$keys)){

                $adapterConfig    = new \Funk\SbzImport\Model\Import\Adapter\Config($attributeCodes);
                $adapterConfig->setStoreId(0);
                $adapterConfig->setCanCreateNewEntity(1);
                $adapterConfig->setCanCreateOptions(1);
                $adapterConfig->setCanCreateCategories(1);
                $adapterConfig->setCanDownloadMedia(1);
                $adapterConfig->setOptionCorrectionFactor(0);
                $adapterConfig->setOptionDelimiter(',');
                self::$OPERATORS[$code] = $adapterConfig ;
            }
            $adapterConfig    = self::$OPERATORS[$code] ;
            return $adapterConfig ;
      }
}