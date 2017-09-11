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

abstract class ClassAbstract
    implements \Funk\SbzImport\Model\Import\Adapter\ImportInterface
{

    protected $_config;

    final public function __construct(\Funk\SbzImport\Model\Import\Adapter\Config $config)
    {
        $config->setEntityTypeId($this->_getEntityTypeId());
        $this->_config = $config;
        $this->_construct();
    }

    final public function getConfig()
    {
        return $this->_config;
    }

    /**
     * @param string $message
     * @param int $code
     */
    protected function _throwException($message, $code = 0)
    {
        throw new \Funk\SbzImport\Model\Import\Adapter\Exception($message, $code);
    }

    /**
     * @return $this
     */
    abstract protected function _construct();

    /**
     * @return int
     */
    abstract protected function _getEntityTypeId();
}
