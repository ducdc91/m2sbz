<?php


namespace Funk\SbzImport\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface CatalogMappingRepositoryInterface
{


    /**
     * Save CatalogMapping
     * @param \Funk\SbzImport\Api\Data\CatalogMappingInterface $catalogMapping
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Funk\SbzImport\Api\Data\CatalogMappingInterface $catalogMapping
    );

    /**
     * Retrieve CatalogMapping
     * @param string $entityId
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve CatalogMapping matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Funk\SbzImport\Api\Data\CatalogMappingSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete CatalogMapping
     * @param \Funk\SbzImport\Api\Data\CatalogMappingInterface $catalogMapping
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Funk\SbzImport\Api\Data\CatalogMappingInterface $catalogMapping
    );

    /**
     * Delete CatalogMapping by ID
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
