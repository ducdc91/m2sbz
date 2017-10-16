<?php

namespace Funk\SbzImport\Cron\Order;


class CloseOrder
{
    /**
     * @var \Funk\SbzImport\Model\ProcessOrder
     */
    protected $_processOrder;

    /**
     * ImportProduct constructor.
     * @param \Funk\SbzImport\Model\ProcessOrder $processOrder
     */
    public function __construct(
        \Funk\SbzImport\Model\ProcessOrder $processOrder
    )
    {
        $this->_processOrder = $processOrder;

    }

    public function execute()
    {
        $this->_processOrder->execute(1);
    }
}