<?php


namespace Funk\SbzImport\Api\Data;

interface KeywordsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Keywords list.
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Funk\SbzImport\Api\Data\KeywordsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
