<?php


namespace Funk\SbzImport\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface OrdersRepositoryInterface
{


    /**
     * Save Orders
     * @param \Funk\SbzImport\Api\Data\OrdersInterface $orders
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Funk\SbzImport\Api\Data\OrdersInterface $orders
    );

    /**
     * Retrieve Orders
     * @param string $entityId
     * @return \Funk\SbzImport\Api\Data\OrdersInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve Orders matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Funk\SbzImport\Api\Data\OrdersSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Orders
     * @param \Funk\SbzImport\Api\Data\OrdersInterface $orders
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Funk\SbzImport\Api\Data\OrdersInterface $orders
    );

    /**
     * Delete Orders by ID
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
