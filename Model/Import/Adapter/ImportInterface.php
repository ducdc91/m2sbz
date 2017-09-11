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

interface ImportInterface
{
    public function beforePrepare();
    public function prepareData(array &$data);
    public function afterPrepare();
    public function beforeProcess();
    public function processData(array &$data);
    public function afterProcess();
}
