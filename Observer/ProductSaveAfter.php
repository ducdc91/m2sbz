<?php
namespace Funk\SbzImport\Observer;

use Magento\Framework\Event\ObserverInterface;

class ProductSaveAfter implements ObserverInterface
{
    /**
     * @var \Funk\SbzImport\Model\ProductsFactory
     */
    protected $_productsFactory;

    /**
     * ProductSaveAfter constructor.
     * @param \Funk\SbzImport\Model\ProductsFactory $productsFactory
     */
    public function __construct(
        \Funk\SbzImport\Model\ProductsFactory $productsFactory
    )
    {
        $this->_productsFactory = $productsFactory;
    }

    /**
     * @param \Magento\Framework\Event\Observer $observer
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $product    = $observer->getProduct();
        $withoutSbz = $product->getCustomAttribute('without_sbzimport');
        $productId  = $product->getId();
        $productSku  = $product->getSku();
        $sbzProduct = $this->_productsFactory->create()->loadBySku($productSku);

        if($withoutSbz && $withoutSbz->getValue()){
            $withoutSbz = 1;
        }else{
            $withoutSbz = 0;
        }

        $sbzProduct->setProductId($productId);
        $sbzProduct->setSku($productSku);
        $sbzProduct->setWithoutSbzimport($withoutSbz);
        $sbzProduct->save();
    }
}