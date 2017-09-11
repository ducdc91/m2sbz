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

abstract class ClassAbstract
{
    const  DELETE_VALUE_FLAG = 'unset';
    protected $_adapter;
    protected $_config;
    protected $_resource;
    protected $_readConnection;
    protected $_writeConnection;
    protected $_website;
    protected $_store;
    // static varible
    static $ADAPTER;
    static $CONFIG;
    static $RESOURCE;
    static $WEBSITE;
    static $STORE;
    static $READCONNECTION;
    static $WRITECONNECTION;

    final public function __construct(\Funk\SbzImport\Model\Import\Adapter\Type\Product\Product $adapter) {
            if(!isset(self::$ADAPTER)){
                self::$ADAPTER          = $adapter;
                self::$CONFIG           = $adapter->getConfig();
                self::$RESOURCE         = self::$CONFIG->getResource();
                self::$WEBSITE          = self::$CONFIG->getWebsite();
                self::$STORE            = self::$CONFIG->getStore();
                self::$READCONNECTION   = self::$CONFIG->getResourceConnection();
                self::$WRITECONNECTION  = self::$CONFIG->getResourceConnection();
            }
            $this->_adapter          = self::$ADAPTER ;
            $this->_config           = self::$CONFIG ;
            $this->_resource         = self::$RESOURCE ;
            $this->_website          = self::$WEBSITE ;
            $this->_store            = self::$STORE ;
            $this->_readConnection   = self::$READCONNECTION ;
            $this->_writeConnection  = self::$WRITECONNECTION ;
            $this->_construct();
      }
    protected function _construct() {
          return $this;
    }

    protected function _throwException($message, $code = 0, Exception $previous = null) {
        throw new \Funk\SbzImport\Model\Import\Adapter\Exception($message, $code);
    }
    public function beforePrepare() {
        return $this;
    }
    public function prepareData(array &$data) {
        return $this;
    }
    public function afterPrepare() {
        return $this;
    }
    public function beforeProcess() {
        return $this;
    }

    abstract public function processData(array &$data);

    public function afterProcess($skuToId)
    {
        return $this;
    }
    protected function arrayIntersectKeys($array1, $array2)
    {
      $keys = array_keys($array2);
      foreach ($array1 as $key => $value) {
        if (!in_array($key, $keys)) {
          unset($array1[$key]);
        }
      }
      return $array1;
    }
}