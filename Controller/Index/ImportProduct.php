<?php

namespace Funk\SbzImport\Controller\Index;


class ImportProduct extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Funk\SbzImport\Cron\Import\Product
     */
    protected $_importProduct;

    /**
     * ImportProduct constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Funk\SbzImport\Cron\Import\Product $importProduct
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Funk\SbzImport\Cron\Import\Product $importProduct
    )
    {
        parent::__construct($context);
        $this->_importProduct = $importProduct;

    }

    public function execute()
    {
        $this->_importProduct->execute();
    }
}