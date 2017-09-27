<?php

namespace Funk\SbzImport\Controller\Index;


class GetDownloads extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Funk\SbzImport\Model\GetDownloads
     */
    protected $_getDownloads;

    /**
     * GetDownloads constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Funk\SbzImport\Model\GetDownloads $getDownloads
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Funk\SbzImport\Model\GetDownloads $getDownloads
    )
    {
        parent::__construct($context);
        $this->_getDownloads = $getDownloads;

    }

    public function execute()
    {
        $this->_getDownloads->execute();
    }
}