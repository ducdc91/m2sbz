<?php

namespace Funk\SbzImport\Model\ResourceModel;


class ProductKeyword extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * SbzImportArticlesKeyword constructor.
     *
     * @param \Magento\Framework\Model\ResourceModel\Db\Context $context
     */
    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }


    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('funk_sbz_import_product_keyword', 'id');
    }

}