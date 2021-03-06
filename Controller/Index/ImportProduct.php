<?php

namespace Funk\SbzImport\Controller\Index;


class ImportProduct extends \Magento\Framework\App\Action\Action
{
    /**
     * @var \Funk\SbzImport\Model\Import
     */
    protected $_import;

    /**
     * ImportProduct constructor.
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Funk\SbzImport\Model\Import $import
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Funk\SbzImport\Model\Import $import
    )
    {
        parent::__construct($context);
        $this->_import = $import;

    }

    public function execute()
    {
        $this->_import->execute();
    }
}