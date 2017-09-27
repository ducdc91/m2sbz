<?php

namespace Funk\SbzImport\Model\Import;


class UpdateStock extends \Magento\Framework\Model\AbstractModel
{
    protected $_attributeCodes = array();

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
        \Magento\Framework\App\ResourceConnection $resource
    )
    {
        $this->_resource = $resource;
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
            $adapterClassName = '\Funk\SbzImport\Model\Import\Adapter\Type\Product\UpdateStock';
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

        $this->_attributeCodes = array_keys($data);
        return $data;
    }

    protected function _initializeAdapter()
    {
        $attributeCodes = $this->_attributeCodes;

        // Initialize data adapter
        $adapterClassName = $this->getAdapterClassName();
        $adapterConfig = self::getOperator($attributeCodes);
        return new $adapterClassName($adapterConfig);
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
            $adapterConfig->setCanCreateNewEntity(0);
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