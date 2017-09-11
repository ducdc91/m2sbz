<?php


namespace Funk\SbzImport\Api\Data;

interface CatalogMappingSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get CatalogMapping list.
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Funk\SbzImport\Api\Data\CatalogMappingInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
