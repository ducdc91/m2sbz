<?php


namespace Funk\SbzImport\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface KeywordsRepositoryInterface
{


    /**
     * Save Keywords
     * @param \Funk\SbzImport\Api\Data\KeywordsInterface $keywords
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Funk\SbzImport\Api\Data\KeywordsInterface $keywords
    );

    /**
     * Retrieve Keywords
     * @param string $entityId
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($entityId);

    /**
     * Retrieve Keywords matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Funk\SbzImport\Api\Data\KeywordsSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Keywords
     * @param \Funk\SbzImport\Api\Data\KeywordsInterface $keywords
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Funk\SbzImport\Api\Data\KeywordsInterface $keywords
    );

    /**
     * Delete Keywords by ID
     * @param string $entityId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($entityId);
}
