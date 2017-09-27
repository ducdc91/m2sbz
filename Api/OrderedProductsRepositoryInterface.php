<?php


namespace Funk\SbzImport\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface OrderedProductsRepositoryInterface
{


    /**
     * Save OrderedProducts
     * @param \Funk\SbzImport\Api\Data\OrderedProductsInterface $orderedProducts
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Funk\SbzImport\Api\Data\OrderedProductsInterface $orderedProducts
    );

    /**
     * Retrieve OrderedProducts
     * @param string $entityId
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve OrderedProducts matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Funk\SbzImport\Api\Data\OrderedProductsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete OrderedProducts
     * @param \Funk\SbzImport\Api\Data\OrderedProductsInterface $orderedProducts
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Funk\SbzImport\Api\Data\OrderedProductsInterface $orderedProducts
    );

    /**
     * Delete OrderedProducts by ID
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
