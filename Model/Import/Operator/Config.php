<?php
/**
 * SupplierConnect - Magento Extension
 *
 * @package SupplierConnect
 * @category FunkExtensions
 * @copyright Copyright 2014 FunkExtensions.
 * @version: 0.1.0
 */

namespace Funk\SbzImport\Model\Import\Operator;

class Config
{
    protected $_adapter;

    protected $_tmpStorage;

    public function __construct(
        \Funk\SbzImport\Model\Import\Adapter\ImportInterface $adapter
    )
    {

        $this->_adapter = $adapter;

    }

    public function getAdapter()
    {
        return $this->_adapter;
    }


}
