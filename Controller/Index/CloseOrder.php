<?php

namespace Funk\SbzImport\Controller\Index;


class CloseOrder extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Funk\SbzImport\Model\ProcessOrder
     */
    protected $_processOrder;

    /**
     * ImportProduct constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Funk\SbzImport\Model\ProcessOrder $processOrder
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Funk\SbzImport\Model\ProcessOrder $processOrder
    )
    {
        parent::__construct($context);
        $this->_processOrder = $processOrder;

    }

    public function execute()
    {
        $this->_processOrder->execute(1);
    }
}