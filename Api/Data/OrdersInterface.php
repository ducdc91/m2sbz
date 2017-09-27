<?php


namespace Funk\SbzImport\Api\Data;

interface OrdersInterface
{

    const SBZ_RELEASE_ATTEMPTS = 'sbz_release_attempts';
    const ENTITY_ID = 'entity_id';
    const SBZ_FINISHED = 'sbz_finished';
    const HAS_DOWNLOADABLES = 'has_downloadables';
    const ORDER_ID = 'order_id';
    const SBZ_RELEASED = 'sbz_released';
    const SBZ_SEND_ATTEMPTS = 'sbz_send_attempts';
    const SBZ_ORDER_ID = 'sbz_order_id';


    /**
     * Get entity_id
     * @return string|null
     */
    public function getId();

    /**
     * Set entity_id
     * @param string $entity_id
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
     */
    public function setId($entity_id);

    /**
     * Get order_id
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     * @param string $order_id
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setOrderId($order_id);

    /**
     * Get sbz_order_id
     * @return string|null
     */
    public function getSbzOrderId();

    /**
     * Set sbz_order_id
     * @param string $sbz_order_id
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setSbzOrderId($sbz_order_id);

    /**
     * Get has_downloadables
     * @return string|null
     */
    public function getHasDownloadables();

    /**
     * Set has_downloadables
     * @param string $has_downloadables
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setHasDownloadables($has_downloadables);

    /**
     * Get sbz_released
     * @return string|null
     */
    public function getSbzReleased();

    /**
     * Set sbz_released
     * @param string $sbz_released
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setSbzReleased($sbz_released);

    /**
     * Get sbz_finished
     * @return string|null
     */
    public function getSbzFinished();

    /**
     * Set sbz_finished
     * @param string $sbz_finished
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setSbzFinished($sbz_finished);

    /**
     * Get sbz_send_attempts
     * @return string|null
     */
    public function getSbzSendAttempts();

    /**
     * Set sbz_send_attempts
     * @param string $sbz_send_attempts
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setSbzSendAttempts($sbz_send_attempts);

    /**
     * Get sbz_release_attempts
     * @return string|null
     */
    public function getSbzReleaseAttempts();

    /**
     * Set sbz_release_attempts
     * @param string $sbz_release_attempts
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     */
    public function setSbzReleaseAttempts($sbz_release_attempts);
}
