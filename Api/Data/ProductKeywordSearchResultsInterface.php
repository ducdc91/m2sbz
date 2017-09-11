<?php


namespace Funk\SbzImport\Api\Data;

interface ProductKeywordSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get ProductKeyword list.
     * @return \Funk\SbzImport\Api\Data\ProductKeywordInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Funk\SbzImport\Api\Data\ProductKeywordInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
