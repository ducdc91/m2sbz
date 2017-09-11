<?php


namespace Funk\SbzImport\Api\Data;

interface DataTemporarySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get DataTemporary list.
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface[]
     */
    public function getItems();

    /**
     * Set entity_id list.
     * @param \Funk\SbzImport\Api\Data\DataTemporaryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
