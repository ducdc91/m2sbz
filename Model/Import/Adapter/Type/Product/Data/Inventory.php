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

class Inventory
    extends \Funk\SbzImport\Model\Import\Adapter\Type\Product\Data\ClassAbstract
{
    protected $_default_inventory_data = array();
    protected $_stock_item_table;
    protected $_stock_status_table;

    // static varible
    static $STOCK_ITEM_TABLE;
    static $STOCK_STATUS_TABLE;

    /**
     * Initialization
     *
     * @return $this
     */

    protected function _construct()
    {
        if (!isset(self::$STOCK_ITEM_TABLE)) {
            self::$STOCK_ITEM_TABLE = $this->_config->getResource()->getTableName('cataloginventory_stock_item');
            self::$STOCK_STATUS_TABLE = $this->_config->getResource()->getTableName('cataloginventory_stock_status');
        }
        $this->_stock_item_table = self::$STOCK_ITEM_TABLE;
        $this->_stock_status_table = self::$STOCK_STATUS_TABLE;

        $this->_default_inventory_data = array(
            "qty" => 0,
            "is_in_stock" => 1,
            "stock_id" => \Magento\CatalogInventory\Model\Stock::DEFAULT_STOCK_ID,
            "use_config_min_qty" => 1,
            "use_config_backorders" => 1,
            "use_config_manage_stock" => 1,
            "backorders" => 0,
            "manage_stock" => 0,
            "min_sale_qty" => 0,
            "max_sale_qty" => 0,
            "use_config_min_sale_qty" => 1,
            "use_config_enable_qty_inc" => 1,
            "enable_qty_increments" => 0,
            "use_config_qty_increments" => 1,
            "qty_increments" => 0,
            "notify_stock_qty" => null,
            "use_config_notify_stock_qty" => 1,
            "is_qty_decimal" => 0,
            "use_config_max_sale_qty" => 1,
        );
        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function processData(array &$data)
    {
        $product_id = $data['product_id'];
        if ($data['_is_new']) {
            $inventoryData = $this->arrayIntersectKeys($data, $this->_default_inventory_data);
            $inventoryData = array_merge($this->_default_inventory_data, $inventoryData);
        } else {
            $inventoryData = $this->arrayIntersectKeys($data, $this->_default_inventory_data);
        }
        $inventoryData['product_id'] = $product_id;
        if (!isset($inventoryData['stock_id'])) {
            $inventoryData['stock_id'] = \Magento\CatalogInventory\Model\Stock::DEFAULT_STOCK_ID;
        }
        $this->_writeConnection->insertOnDuplicate($this->_stock_item_table, $inventoryData);
        foreach ($data['website_id'] as $id) {
            $insertData = array(
                'product_id' => $product_id,
                'website_id' => $id,
            );

            if ($data['_is_new']) {
                $insertData['stock_status'] = $inventoryData['is_in_stock'] ? \Magento\CatalogInventory\Model\Stock::STOCK_IN_STOCK : \Magento\CatalogInventory\Model\Stock::STOCK_OUT_OF_STOCK;
            }

            if (isset($inventoryData['stock_id'])) {
                $insertData['stock_id'] = $inventoryData['stock_id'];
            }
            if (isset($inventoryData['qty'])) {
                $insertData['qty'] = $inventoryData['qty'];
            }

            $this->_writeConnection->insertOnDuplicate($this->_stock_status_table, $insertData);
        }
        return $this;
    }
}