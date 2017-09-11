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

use Funk\SbzImport\Model\Import\Adapter\Type\Product\Exception;

abstract class ClassAbstract
{
    protected function _throwException($message, $code = 0, Exception $previous = null)
    {
        throw new Exception($message, $code, $previous);
    }
}