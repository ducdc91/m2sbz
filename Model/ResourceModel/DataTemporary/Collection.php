<?php


namespace Funk\SbzImport\Model\ResourceModel\DataTemporary;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/**
     * MUST USE set primary key of table
     * @var string
     */
    protected $_idFieldName = 'entity_id';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            'Funk\SbzImport\Model\DataTemporary',
            'Funk\SbzImport\Model\ResourceModel\DataTemporary'
        );
    }
}
