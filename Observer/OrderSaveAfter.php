<?php
namespace Funk\SbzImport\Observer;

use Magento\Framework\Event\ObserverInterface;

class OrderSaveAfter implements ObserverInterface
{
    protected $_ordersFactory;

    protected $_resource;
    protected $_productsFactory;

    protected $_orderedProductsFactory;

    public function __construct(
        \Funk\SbzImport\Model\OrdersFactory $ordersFactory,
        \Funk\SbzImport\Model\ProductsFactory $productsFactory,
        \Funk\SbzImport\Model\OrderedProductsFactory $orderedProductsFactory,
        \Magento\Framework\App\ResourceConnection $resource
    )
    {
        $this->_resource = $resource;
        $this->_ordersFactory = $ordersFactory;
        $this->_productsFactory = $productsFactory;
        $this->_orderedProductsFactory = $orderedProductsFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $order    = $observer->getEvent()->getOrder();
    /*if($order->getState() == 'complete') {*/
        $isSbzOrderDownload = false;
        $orderId  = $order->getId();
        $items = $this->getItemManagedBySbz($orderId);
        if(count($items)){
            foreach ($items as $item){
                $sku            = $item["sku"];
                $productId      = $item["product_id"];
                $productType    = $item["product_db_type"];
                $qty            = $item["qty_ordered"];

                $sbzItem = $this->_orderedProductsFactory->create()->loadByOrderIdAndSku($orderId, $sku);
                $sbzItem->setSku($sku);
                $sbzItem->setProductId($productId);
                $sbzItem->setSbzOrderID($orderId);
                $sbzItem->setProductType($productType);
                $sbzItem->setQty($qty);
                $sbzItem->save();

            }
            $sbzOrder = $this->_ordersFactory->create()->loadByOrderId($orderId);
            $sbzOrder->setOrderId($orderId);
          /*  if($isSbzOrderDownload){
                $sbzOrder->setHasDownloadables(1);
            }*/
            $sbzOrder->save();
        }
        /*}*/
    }
    private function getItemManagedBySbz($order_id=0) {
        $order_item_table = $this->_resource->getTableName('sales_order_item');
        $sbz_imported_product_table  ='funk_sbz_import_products';

        $connection = $this->_resource->getConnection();
        $sql = "SELECT
                p.product_id, p.sku ,p.product_db_type,
                o.qty_ordered
                FROM
                {$order_item_table} as o
                INNER JOIN 
                {$sbz_imported_product_table} as p
                 ON o.sku = p.sku
                where o.order_id = {$order_id} and p.without_sbzimport !=1";

        $result = $connection->fetchAll($sql);
        return $result;
    }
}