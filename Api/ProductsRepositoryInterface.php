<?php


namespace Funk\SbzImport\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductsRepositoryInterface
{


    /**
     * Save Products
     * @param \Funk\SbzImport\Api\Data\ProductsInterface $products
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Funk\SbzImport\Api\Data\ProductsInterface $products
    );

    /**
     * Retrieve Products
     * @param string $entityId
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve Products matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Funk\SbzImport\Api\Data\ProductsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Products
     * @param \Funk\SbzImport\Api\Data\ProductsInterface $products
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Funk\SbzImport\Api\Data\ProductsInterface $products
    );

    /**
     * Delete Products by ID
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
