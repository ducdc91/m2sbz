<?php


namespace Funk\SbzImport\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface DataTemporaryRepositoryInterface
{


    /**
     * Save DataTemporary
     * @param \Funk\SbzImport\Api\Data\DataTemporaryInterface $dataTemporary
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Funk\SbzImport\Api\Data\DataTemporaryInterface $dataTemporary
    );

    /**
     * Retrieve DataTemporary
     * @param string $entityId
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve DataTemporary matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Funk\SbzImport\Api\Data\DataTemporarySearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete DataTemporary
     * @param \Funk\SbzImport\Api\Data\DataTemporaryInterface $dataTemporary
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Funk\SbzImport\Api\Data\DataTemporaryInterface $dataTemporary
    );

    /**
     * Delete DataTemporary by ID
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
