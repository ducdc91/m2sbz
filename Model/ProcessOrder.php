<?php

namespace Funk\SbzImport\Model;


class ProcessOrder
{
    const SBZ_IMPORT_ORDER_BUCHZENTRUM_PROCESS_MAX_ATTEMPTS = 3;
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
     * @var Sbz\Request
     */
    protected $_sbzRequest;

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

    public function execute($type = 0){
        if($type == 0){
            $orders = $this->getSendOrderCollection();
            foreach ($orders as $order){
                $this->sendOrderToSbz($order);
            }
        }else{
            $orders = $this->getUnFinishOrderCollection();
            foreach ($orders as $order){
                $this->closeOrderToSbz($order);
            }
        }
    }

    public function getSendOrderCollection(){
        $collection = $this->_ordersFactory->create()->getCollection();
        $collection->getSelectSql()
            ->where("`sbz_order_id` = 0 AND `sbz_send_attempts` < ". self::SBZ_IMPORT_ORDER_BUCHZENTRUM_PROCESS_MAX_ATTEMPTS )
            ->orWhere("`sbz_order_id` != 0 AND `sbz_released` = 0 AND `sbz_release_attempts` < ". self::SBZ_IMPORT_ORDER_BUCHZENTRUM_PROCESS_MAX_ATTEMPTS );
        return $collection;
    }

    public function getUnFinishOrderCollection(){
        $collection = $this->_ordersFactory->create()->getCollection();
        $collection->addFieldToFilter('sbz_released',1);
        $collection->addFieldToFilter('sbz_finished',0);
        return $collection;
    }

    private function sendOrderToSbz($order_info){
        if (empty($order_info->getSbzOrderId())) {
            $sbz_send_attempts = $order_info->getSbzSendAttempts();
            $sbz_send_attempts++;
            $sbz_order_result = $this->_sbzRequest->clearResults()->addOrderNormal()->getResults();

            if (isset($sbz_order_result['order_id'])){
                $sbz_order_id    = $sbz_order_result['order_id'];
                $order_info->setSbzOrderId($sbz_order_id);
            }
            elseif ($order_info->sbz_send_attempts >= self::SBZ_IMPORT_ORDER_BUCHZENTRUM_PROCESS_MAX_ATTEMPTS){
                //drupal_mail('sbz_import', 'sbz_import_order_buchzentrum_add_fail', $site_mail, language_default(), array('order_id' => $order->order_id), $site_mail);
                //send mail to store (update later)
            }
        }
        if ($order_info->getSbzOrderId()){
            if ($order_info->getSbzReleaseAttempts() == 0) {
                $order_has_downloadable_items = 0;
                $items = $order_info->getItems();
                foreach ($items as $item) {
                    if ($item->getProductType() == 'download'){
                        $order_has_downloadable_items = 1;
                    }
                    $ordered_article_result = $this->_sbzRequest->clearResults()->addDetailNormal($order_info->getSbzOrderId(), $item->getSku(), $item->getQty())->getResults();

                    if (isset($ordered_article_result['position_number'])){
                        $item->setPosition($ordered_article_result['position_number']);
                    }
                    $item->save();
                }
               $order_info->setHasDownloadables($order_has_downloadable_items);
            }

            if (empty($order_info->getSbzReleased())) {
                $sbz_release_attempts = $order_info->getSbzReleaseAttempts() + 1;
                $release_order_result = $this->_sbzRequest->clearResults()->releaseOrder($order_info->getSbzOrderId())->getResults();
                if ($release_order_result['order_released']){
                    $order_info->setSbzReleased(1);
                    if (!$order_info->getHasDownloadables()){
                        $order_info->setSbzFinished(1);
                    }
                }
                elseif ($order_info->getSbzReleaseAttempts() >= self::SBZ_IMPORT_ORDER_BUCHZENTRUM_PROCESS_MAX_ATTEMPTS){
                    //drupal_mail('sbz_import', 'sbz_import_order_buchzentrum_release_fail', $site_mail, language_default(), array('order_id' => $order->order_id), $site_mail);
                    //send mail to store (update later)
                }
            }
        }
        $order_info->setSbzSendAttempts($sbz_send_attempts);
        $order_info->setSbzReleaseAttempts($sbz_release_attempts);
        $order_info->save();
    }

    private function closeOrderToSbz($order_info){
        $items = $order_info->getItems();
        $items->addFieldToFilter('product_type','download');
        $items->addFieldToFilter('url',array('like' => ''));
        if(!count($items)){
            $order_info->setSbzFinished(1);
            $order_info->save();
        }

    }
}