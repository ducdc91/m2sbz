<?php

namespace Funk\SbzImport\Cron\Import;

class Product
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Funk\SbzImport\Model\Import
     */
    protected $_import;

    public function __construct(
        \Funk\SbzImport\Model\Import $import,
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->_import = $import;
        $this->_logger = $logger;
    }

    public function execute()
    {
        $this->_logger->info('run cron sbzimport product');
        $this->_logger->info(__METHOD__);
        $this->_import->execute();
    }

}