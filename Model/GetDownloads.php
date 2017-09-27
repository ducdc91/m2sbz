<?php

namespace Funk\SbzImport\Model;


class GetDownloads
{
    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var OrderedProductsFactory
     */
    protected $_orderedProductsFactory;

    /**
     * @var OrdersFactory
     */
    protected $_ordersFactory;

    /**
     * GetDownloads constructor.
     * @param Sbz\Request $sbzRequest
     * @param OrderedProductsFactory $orderedProductsFactory
     * @param OrdersFactory $ordersFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\App\ResourceConnection $resourceConnection
     */
    public function __construct(
        \Funk\SbzImport\Model\Sbz\Request $sbzRequest,
        \Funk\SbzImport\Model\OrderedProductsFactory $orderedProductsFactory,
        \Funk\SbzImport\Model\OrdersFactory $ordersFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    )
    {
        $this->_sbzRequest = $sbzRequest;
        $this->_resourceConnection = $resourceConnection;
        $this->_orderedProductsFactory = $orderedProductsFactory;
        $this->_ordersFactory = $ordersFactory;
        $this->_logger = $logger;
    }

    /**
     *
     */
    public function execute(){
        $items = $this->getOrderedProducts();

        $downloadable_articles = array();
        $orders = array();

        foreach ($items as $item){
            $downloadable_articles[$item->getSku() . '-' . $item->getOrderId()] = $item;
            $orders[$item->getOrderId()] = $item->getOrderId();
        }

        $sbz_downloads = $this->_sbzRequest->clearResults()->getDownload($orders)->getResults();
        foreach ($sbz_downloads as $sbz_download){
            $key = $sbz_download['Bznr'].'-'.$sbz_download['Auftrag'];
            $url = $sbz_download['Url'];
            if (isset($downloadable_articles[$key]) && $sbz_download['Downloadstatus'] == 'Bereit' && !empty($url)){
                $downloadProduct = $downloadable_articles[$key];
                $downloadProduct->setUrl($url);
                $downloadProduct->save();
            }
        }
    }

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getOrderedProducts(){
        $collection = $this->_orderedProductsFactory->create()->getCollection();
        $collection->addFieldToFilter('product_type', 'download');
        $collection->addFieldToFilter(
            array('url' ,'url'),
            array(
                array('like' => ''),
                array('null' => true)
            )
        );
        $orderTableName = 'funk_sbz_import_orders';
        $collection->join(
            ['o' => $orderTableName],
            "o.entity_id = main_table.sbz_order_id",
            ['sbz_order_id as order_id']
        );
        $collection->addFieldToFilter('o.sbz_released', 1);
        $collection->addFieldToFilter('o.sbz_finished', 0);

        return $collection;
    }
}