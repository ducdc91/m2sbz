<?php


namespace Funk\SbzImport\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface FieldsMappingRepositoryInterface
{


    /**
     * Save FieldsMapping
     * @param \Funk\SbzImport\Api\Data\FieldsMappingInterface $fieldsMapping
     * @return \Funk\SbzImport\Api\Data\FieldsMappingInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Funk\SbzImport\Api\Data\FieldsMappingInterface $fieldsMapping
    );

    /**
     * Retrieve FieldsMapping
     * @param string $entityId
     * @return \Funk\SbzImport\Api\Data\FieldsMappingInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve FieldsMapping matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Funk\SbzImport\Api\Data\FieldsMappingSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete FieldsMapping
     * @param \Funk\SbzImport\Api\Data\FieldsMappingInterface $fieldsMapping
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Funk\SbzImport\Api\Data\FieldsMappingInterface $fieldsMapping
    );

    /**
     * Delete FieldsMapping by ID
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
