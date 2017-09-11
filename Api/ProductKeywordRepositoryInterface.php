<?php


namespace Funk\SbzImport\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface ProductKeywordRepositoryInterface
{


    /**
     * Save ProductKeyword
     * @param \Funk\SbzImport\Api\Data\ProductKeywordInterface $productKeyword
     * @return \Funk\SbzImport\Api\Data\ProductKeywordInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Funk\SbzImport\Api\Data\ProductKeywordInterface $productKeyword
    );

    /**
     * Retrieve ProductKeyword
     * @param string $entityId
     * @return \Funk\SbzImport\Api\Data\ProductKeywordInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve ProductKeyword matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Funk\SbzImport\Api\Data\ProductKeywordSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete ProductKeyword
     * @param \Funk\SbzImport\Api\Data\ProductKeywordInterface $productKeyword
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Funk\SbzImport\Api\Data\ProductKeywordInterface $productKeyword
    );

    /**
     * Delete ProductKeyword by ID
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
