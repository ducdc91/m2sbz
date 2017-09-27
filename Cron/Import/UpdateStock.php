<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 9/25/2017
 * Time: 11:33 AM
 */

namespace Funk\SbzImport\Cron\Import;


class UpdateStock
{
    /**
     * @var \Funk\SbzImport\Model\SbzImport
     */
    protected $_sbzImport;

    /**
     * @var \Funk\SbzImport\Model\Import\Product
     */
    protected $_productImport;

    /**
     * @var \Funk\SbzImport\Model\DataTemporaryFactory
     */
    protected $_dataTemporaryFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * Product constructor.
     * @param \Funk\SbzImport\Model\SbzImport $sbzImport
     * @param \Funk\SbzImport\Model\DataTemporaryFactory $dataTemporaryFactory
     */
    public function __construct(
        \Funk\SbzImport\Model\SbzImport $sbzImport,
        \Funk\SbzImport\Model\Import\Product $productImport,
        \Psr\Log\LoggerInterface $logger,
        \Funk\SbzImport\Model\DataTemporaryFactory $dataTemporaryFactory
    )
    {
        $this->_sbzImport = $sbzImport;
        $this->_productImport = $productImport;
        $this->_dataTemporaryFactory = $dataTemporaryFactory;
        $this->_logger = $logger;
    }

    public function execute()
    {
        $this->_logger->info('run cron sbzimport update stock');
        $this->_logger->info(__METHOD__);
        $this->_sbzImport->execute();
        $dataTemporary = $this->_dataTemporaryFactory->create();
        $dataCollection = $dataTemporary->getCollection();
        foreach ($dataCollection as $item) {
            $data = unserialize($item->getDataContent());
            $data["product_id"] = $item->getProductId();
            $data["sku"] = $item->getSku();
            $this->_productImport->updateStock($data);
        }
    }
}