<?php


namespace Funk\SbzImport\Api\Data;

interface FieldsMappingSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get FieldsMapping list.
     * @return \Funk\SbzImport\Api\Data\FieldsMappingInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Funk\SbzImport\Api\Data\FieldsMappingInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
