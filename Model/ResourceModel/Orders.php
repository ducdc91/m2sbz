<?php


namespace Funk\SbzImport\Model\ResourceModel;

class Orders extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('funk_sbz_import_orders', 'entity_id');
    }
}
