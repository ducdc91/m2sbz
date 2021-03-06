<?php

namespace Funk\SbzImport\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;


class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        /**
         * Create table 'funk_sbz_import_data_tpm'
         */
        $setup->getConnection()->dropTable($setup->getTable('funk_sbz_import_data_tpm'));
        $tableImportDataTpm = $installer->getConnection()->newTable(
            $installer->getTable('funk_sbz_import_data_tpm')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'entity_id'
        )->addColumn(
			'sku',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            [],
            'sku'
        )->addColumn(
			'product_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            11,
            ['unsigned' => true],
            'product_id'
        )->addColumn(
			'main_category',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'main_category'
        )->addColumn(
			'sub_category',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'sub_category'
        )->addColumn(
            'data_content',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            '2M',
            [],
            'data_content'
        )->addColumn(
            'product_type',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            50,
            ['default' => ''],
            'product_type'
        )->addColumn(
            'create_date',
            \Magento\Framework\DB\Ddl\Table::TYPE_DATETIME,
            null,
            [],
            'create_date'
        );
        $installer->getConnection()->createTable($tableImportDataTpm);
		
        /**
         * Create table 'funk_sbz_import_keywords'
         */
        $setup->getConnection()->dropTable($setup->getTable('funk_sbz_import_keywords'));
        $tableImportKeywords = $installer->getConnection()->newTable(
            $installer->getTable('funk_sbz_import_keywords')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'entity_id'
        )->addColumn(
            'kwd_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Primary ID field for the table. Not used for anything except internal lookups.'
        )->addColumn(
            'keyword',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'Keywords'
        )->addColumn(
            'main_category',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'main_category'
        )->addColumn(
            'sub_category',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'sub_category'
        );
        $installer->getConnection()->createTable($tableImportKeywords);

        /**
         * Create table 'funk_sbz_import_product_keyword'
         */
        $setup->getConnection()->dropTable($setup->getTable('funk_sbz_import_product_keyword'));
        $tableImportProductKeyword = $installer->getConnection()->newTable(
            $installer->getTable('funk_sbz_import_product_keyword')
        )->addColumn(
            'entity_id',
            \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'entity_id'
        )->addColumn(
            'sku',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            64,
            [],
            'sku'
        )->addColumn(
            'keyword',
            \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
            255,
            [],
            'keyword'
        );
        $installer->getConnection()->createTable($tableImportProductKeyword);

        $installer->endSetup();
    }
}