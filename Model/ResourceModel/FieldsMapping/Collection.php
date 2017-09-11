<?php

namespace Funk\SbzImport\Model\ResourceModel\FieldsMapping;

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
      $this->_init('Funk\SbzImport\Model\FieldsMapping', 'Funk\SbzImport\Model\ResourceModel\FieldsMapping');
    }
}