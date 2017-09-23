<?php

namespace Funk\SbzImport\Controller\Index;

use Funk\SbzImport\Model\SbzImport;
use Funk\SbzImport\Model\Import\Product;

class Import extends \Magento\Framework\App\Action\Action
{

    protected $resultPageFactory;
    protected $sbzImport;

    /**
     * Index constructor.
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Funk\SbzImport\Model\SbzImport $sbzImport
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        SbzImport $sbzImport,
        Product $productImport
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->sbzImport = $sbzImport;
        $this->productImport = $productImport;

    }

    /**
     * Execute view action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $dataCollection = $objectManager->create('\Funk\SbzImport\Model\ResourceModel\DataTemporary\Collection');

        foreach ($dataCollection as $item) {
            $data = unserialize($item->getDataContent());
            $data["product_id"] = $item->getProductId();
            $data["main_category"] = $item->getMainCategory();
            $data["sub_category"] = $item->getSubCategory();
            $this->productImport->import($data);
        }

        return $this->resultPageFactory->create();
    }
}