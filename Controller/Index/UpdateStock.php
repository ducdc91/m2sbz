<?php

namespace Funk\SbzImport\Controller\Index;


class UpdateStock extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Funk\SbzImport\Model\Import|\Funk\SbzImport\Model\UpdateStock
     */
    protected $_updateStock;

    /**
     * UpdateStock constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Funk\SbzImport\Model\UpdateStock $import
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Funk\SbzImport\Model\UpdateStock $updateStock
    )
    {
        parent::__construct($context);
        $this->_updateStock = $updateStock;

    }

    public function execute()
    {
        $this->_updateStock->execute();
    }
}