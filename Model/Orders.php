<?php


namespace Funk\SbzImport\Model;

use Funk\SbzImport\Api\Data\OrdersInterface;

class Orders extends \Magento\Framework\Model\AbstractModel implements OrdersInterface
{

    protected $_orderedProductsFactory;

    public function __construct(
        \Funk\SbzImport\Model\OrderedProductsFactory $orderedProductsFactory,
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
        $this->_orderedProductsFactory = $orderedProductsFactory;
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Funk\SbzImport\Model\ResourceModel\Orders');
    }

    /**
     * Get entity_id
     * @return string
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set entity_id
     * @param string $entity_id
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setId($entity_id)
    {
        return $this->setData(self::ENTITY_ID, $entity_id);
    }

    /**
     * Get order_id
     * @return string
     */
    public function getOrderId()
    {
        return $this->getData(self::ORDER_ID);
    }

    /**
     * Set order_id
     * @param string $order_id
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setOrderId($order_id)
    {
        return $this->setData(self::ORDER_ID, $order_id);
    }

    /**
     * Get sbz_order_id
     * @return string
     */
    public function getSbzOrderId()
    {
        return $this->getData(self::SBZ_ORDER_ID);
    }

    /**
     * Set sbz_order_id
     * @param string $sbz_order_id
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setSbzOrderId($sbz_order_id)
    {
        return $this->setData(self::SBZ_ORDER_ID, $sbz_order_id);
    }

    /**
     * Get has_downloadables
     * @return string
     */
    public function getHasDownloadables()
    {
        return $this->getData(self::HAS_DOWNLOADABLES);
    }

    /**
     * Set has_downloadables
     * @param string $has_downloadables
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setHasDownloadables($has_downloadables)
    {
        return $this->setData(self::HAS_DOWNLOADABLES, $has_downloadables);
    }

    /**
     * Get sbz_released
     * @return string
     */
    public function getSbzReleased()
    {
        return $this->getData(self::SBZ_RELEASED);
    }

    /**
     * Set sbz_released
     * @param string $sbz_released
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setSbzReleased($sbz_released)
    {
        return $this->setData(self::SBZ_RELEASED, $sbz_released);
    }

    /**
     * Get sbz_finished
     * @return string
     */
    public function getSbzFinished()
    {
        return $this->getData(self::SBZ_FINISHED);
    }

    /**
     * Set sbz_finished
     * @param string $sbz_finished
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setSbzFinished($sbz_finished)
    {
        return $this->setData(self::SBZ_FINISHED, $sbz_finished);
    }

    /**
     * Get sbz_send_attempts
     * @return string
     */
    public function getSbzSendAttempts()
    {
        return $this->getData(self::SBZ_SEND_ATTEMPTS);
    }

    /**
     * Set sbz_send_attempts
     * @param string $sbz_send_attempts
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setSbzSendAttempts($sbz_send_attempts)
    {
        return $this->setData(self::SBZ_SEND_ATTEMPTS, $sbz_send_attempts);
    }

    /**
     * Get sbz_release_attempts
     * @return string
     */
    public function getSbzReleaseAttempts()
    {
        return $this->getData(self::SBZ_RELEASE_ATTEMPTS);
    }

    /**
     * Set sbz_release_attempts
     * @param string $sbz_release_attempts
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setSbzReleaseAttempts($sbz_release_attempts)
    {
        return $this->setData(self::SBZ_RELEASE_ATTEMPTS, $sbz_release_attempts);
    }

    public function loadByOrderId($orderId){
        $collection = $this->getCollection();
        $collection->addFieldToFilter('order_id',array('eq' => $orderId));

        return $collection->getFirstItem();
    }

    public function getItems(){
        $collection = $this->_orderedProductsFactory->create()->getCollection();
        $collection->addFieldToFilter('sbz_order_id',array('eq' => $this->getOrderId()));

        return $collection;
    }
}
