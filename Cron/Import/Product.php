<?php

namespace Funk\SbzImport\Cron\Import;

class Product
{
    /**
     * @var SbzImport
     */
    protected $_sbzImport;

    /**
     * @var \Funk\SbzImport\Model\Import\Product
     */
    protected $_productImport;

    /**
     * @var DataTemporaryInterface
     */
    protected $_dataTemporaryFactory;

    /**
     * Product constructor.
     * @param \Funk\SbzImport\Model\SbzImport $sbzImport
     * @param \Funk\SbzImport\Model\DataTemporaryFactory $dataTemporaryFactory
     */
    public function __construct(
        \Funk\SbzImport\Model\SbzImport $sbzImport,
        \Funk\SbzImport\Model\Import\Product $productImport,
        \Funk\SbzImport\Model\DataTemporaryFactory $dataTemporaryFactory
    )
    {
        $this->_sbzImport = $sbzImport;
        $this->_productImport = $productImport;
        $this->_dataTemporaryFactory = $dataTemporaryFactory;
    }

    public function execute()
    {
        //$this->_sbzImport->execute();
        $dataTemporary = $this->_dataTemporaryFactory->create();
        $dataCollection = $dataTemporary->getCollection();
        foreach ($dataCollection as $item) {
            $data = unserialize($item->getDataContent());
            $data["product_id"] = $item->getProductId();
            $data["sku"] = $item->getSku();
            $this->_productImport->import($data);
        }
        print_r(count($dataCollection));
        exit;
    }

}