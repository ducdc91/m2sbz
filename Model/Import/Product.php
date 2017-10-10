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

use Funk\SbzImport\Model\ProductKeywordFactory;
use Funk\SbzImport\Model\KeywordsFactory;

class Product extends \Magento\Framework\Model\AbstractModel
{
    protected $_attributeCodes = array();

    /**
     * @var ProductKeywordFactory
     */
    protected $_productKeywordFactory;

    /**
     * @var KeywordsFactory
     */
    protected $_keywordsFactory;

    //static varible
    static $OPERATORS = array();
    static $ADAPTER_CLASS_NAME;
    static $PRODUCT_URL_MODEL;
    static $PRODUCT_TABLE_NAME;
    static $CONNECTION;
    static $FIELDS_MAPPING = [
        'sku' => 'Artikel',
        'price' => 'Preis',
        'name' => 'Titel',
        'description' => 'Zusammenfassung',
    ];

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        ProductKeywordFactory $productKeywordFactory,
        KeywordsFactory $keywordsFactory
    )
    {
        $this->_resource = $resource;
        $this->_productKeywordFactory = $productKeywordFactory;
        $this->_keywordsFactory = $keywordsFactory;
    }

    public function init()
    {
        self::$PRODUCT_TABLE_NAME = $this->_resource->getTableName('catalog/product');
        self::$CONNECTION = $this->_resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        return true;
    }

    public function getAdapterClassName()
    {

        if (!isset(self::$ADAPTER_CLASS_NAME)) {
            $adapterClassName = '\Funk\SbzImport\Model\Import\Adapter\Type\Product\Product';
            self::$ADAPTER_CLASS_NAME = $adapterClassName;
        }
        $adapterClassName = self::$ADAPTER_CLASS_NAME;
        return $adapterClassName;
    }

    public function import($data)
    {

        $data = $this->getValues($data);
        $adapter = $this->_initializeAdapter();
        $operatorConfig = new \Funk\SbzImport\Model\Import\Operator\Config($adapter);
        $operator = new \Funk\SbzImport\Model\Import\Operator($operatorConfig);
        $operator->run($data);

    }

    public function getValues($data)
    {
        $new_data = array();

        if (is_null(self::$FIELDS_MAPPING)) {
            /*$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $fields = $objectManager->create('\Funk\SbzImport\Model\ResourceModel\FieldsMapping\Collection');
            foreach ($fields as $field) {
                self::$FIELDS_MAPPING[$field->getMageField()] = $field->getBszField();
            }*/
        }
        $new_data["product_id"] = $data["product_id"];
        $new_data["images"] = $data["images"];
        $new_data["categories"] = $this->getCategoriesBySku($data);
        $new_data["without_sbzimport"] = 0;
        if($data["product_type"]=='download'){
            $new_data["qty"]   =-2;
        }
        foreach (self::$FIELDS_MAPPING as $k => $v) {
            $value = "";
            if (isset($data[$v])) {
                $value = $data[$v];
            }
            $new_data[$k] = $value;
        }
        $this->_attributeCodes = array_keys($new_data);
        return $new_data;
    }

    protected function _initializeAdapter()
    {
        $attributeCodes = $this->_attributeCodes;

        // Initialize data adapter
        $adapterClassName = $this->getAdapterClassName();
        $adapterConfig = self::getOperator($attributeCodes);
        return new $adapterClassName($adapterConfig);
    }

    protected function getCategoriesBySku($data)
    {

        $categories = '';
        $mainCategory = $data['main_category'];
        $subCategory = $data['sub_category'];
        if ($mainCategory && $subCategory) {
            $categories = $mainCategory . '/' . $subCategory;
        }
        return $categories;
    }

    // make unique key by code
    public static function genKeyByAttributeCode($attributeCodes)
    {
        $str_key = "";
        foreach ($attributeCodes as $v) {
            $str_key .= $v . "_";
        }
        return $str_key;
    }

    public static function getOperator($attributeCodes)
    {
        $code = self::genKeyByAttributeCode($attributeCodes);
        $keys = array_keys(self::$OPERATORS);
        if (!in_array($code, $keys)) {

            $adapterConfig = new \Funk\SbzImport\Model\Import\Adapter\Config($attributeCodes);
            $adapterConfig->setStoreId(0);
            $adapterConfig->setCanCreateNewEntity(1);
            $adapterConfig->setCanCreateOptions(1);
            $adapterConfig->setCanCreateCategories(1);
            $adapterConfig->setCanDownloadMedia(1);
            $adapterConfig->setOptionCorrectionFactor(0);
            $adapterConfig->setOptionDelimiter(',');
            self::$OPERATORS[$code] = $adapterConfig;
        }
        $adapterConfig = self::$OPERATORS[$code];
        return $adapterConfig;
    }
}