<?php

namespace Funk\SbzImport\Model\ResourceModel\DataTemporary;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * MUST USE set primary key of table
     * @var string
     */
    protected $_idFieldName = 'id';
    /**
     * Remittance File Collection Constructor
     * @return void
     */
    protected function _construct()
    {
      $this->_init('Funk\SbzImport\Model\DataTemporary', 'Funk\SbzImport\Model\ResourceModel\DataTemporary');
    }
}