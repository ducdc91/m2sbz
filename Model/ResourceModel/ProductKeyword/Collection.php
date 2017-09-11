<?php


namespace Funk\SbzImport\Model\ResourceModel\ProductKeyword;

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
            'Funk\SbzImport\Model\ProductKeyword',
            'Funk\SbzImport\Model\ResourceModel\ProductKeyword'
        );
    }
}
