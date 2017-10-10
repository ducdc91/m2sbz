<?php

namespace Funk\SbzImport\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Upgrade the Rbslider module DB scheme
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * {@inheritdoc}
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        if ($context->getVersion() && version_compare($context->getVersion(), '1.1.0', '<=')) {
            $table_funk_sbzimport_orderedproducts = $setup->getConnection()->newTable($setup->getTable('funk_sbz_import_ordered_products'));


            $table_funk_sbzimport_orderedproducts->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                array('identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,),
                'Entity ID'
            );


            $table_funk_sbzimport_orderedproducts->addColumn(
                'sku',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => False],
                'Article id'
            );


            $table_funk_sbzimport_orderedproducts->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => False, 'unsigned' => true],
                'Commerce product id'
            );


            $table_funk_sbzimport_orderedproducts->addColumn(
                'sbz_order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => False, 'unsigned' => true],
                'Sbz order id'
            );


            $table_funk_sbzimport_orderedproducts->addColumn(
                'position',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False, 'unsigned' => true],
                'Position within the sbz order'
            );


            $table_funk_sbzimport_orderedproducts->addColumn(
                'product_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => False],
                'Article type (ex.: download)'
            );


            $table_funk_sbzimport_orderedproducts->addColumn(
                'url',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                2000,
                ['nullable' => False],
                'Url for downloadable products'
            );

            $table_funk_sbzimport_orderedproducts->addColumn(
                'qty',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                10,
                ['nullable' => False],
                'Qty'
            );


            $table_funk_sbzimport_orders = $setup->getConnection()->newTable($setup->getTable('funk_sbz_import_orders'));


            $table_funk_sbzimport_orders->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                array('identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,),
                'Entity ID'
            );


            $table_funk_sbzimport_orders->addColumn(
                'order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['nullable' => False, 'unsigned' => true],
                'Order id'
            );


            $table_funk_sbzimport_orders->addColumn(
                'sbz_order_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False, 'unsigned' => true],
                'Sbz order id'
            );


            $table_funk_sbzimport_orders->addColumn(
                'has_downloadables',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False, 'unsigned' => true],
                'Indicates whether the order has dowloadable products'
            );


            $table_funk_sbzimport_orders->addColumn(
                'sbz_released',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False, 'unsigned' => true],
                'Indicates whether the order has been released on sbz'
            );


            $table_funk_sbzimport_orders->addColumn(
                'sbz_finished',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False, 'unsigned' => true],
                'Indicates whether the processing with sbz has finished for the order'
            );


            $table_funk_sbzimport_orders->addColumn(
                'sbz_send_attempts',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False, 'unsigned' => true],
                'The number of attempts to send the order to buchzentrum'
            );


            $table_funk_sbzimport_orders->addColumn(
                'sbz_release_attempts',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False, 'unsigned' => true],
                'The number of attempts to release the order on buchzentrum'
            );


            $table_funk_sbzimport_products = $setup->getConnection()->newTable($setup->getTable('funk_sbz_import_products'));


            $table_funk_sbzimport_products->addColumn(
                'entity_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                array('identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,),
                'Entity ID'
            );


            $table_funk_sbzimport_products->addColumn(
                'product_id',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False, 'unsigned' => true],
                'product_id'
            );


            $table_funk_sbzimport_products->addColumn(
                'sku',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => False],
                'sku'
            );


            $table_funk_sbzimport_products->addColumn(
                'product_display_nid',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False, 'unsigned' => true],
                'product_display_nid'
            );


            $table_funk_sbzimport_products->addColumn(
                'product_db_type',
                \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                255,
                ['nullable' => False],
                'product_db_type'
            );


            $table_funk_sbzimport_products->addColumn(
                'import',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '1', 'nullable' => False],
                'import'
            );


            $table_funk_sbzimport_products->addColumn(
                'last_update',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False],
                'last_update'
            );


            $table_funk_sbzimport_products->addColumn(
                'last_update_success',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False],
                'last_update_success'
            );


            $table_funk_sbzimport_products->addColumn(
                'product_changed',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False],
                'product_changed'
            );


            $table_funk_sbzimport_products->addColumn(
                'next_stock_update',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                null,
                ['default' => '0', 'nullable' => False, 'unsigned' => true],
                'next_stock_update'
            );

            $table_funk_sbzimport_products->addColumn(
                'without_sbzimport',
                \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
                1,
                ['default' => '0', 'nullable' => true, 'unsigned' => true],
                'without_sbzimport'
            );


            $setup->getConnection()->createTable($table_funk_sbzimport_products);

            $setup->getConnection()->createTable($table_funk_sbzimport_orders);

            $setup->getConnection()->createTable($table_funk_sbzimport_orderedproducts);
        }
        $setup->endSetup();
    }
}
