<?php


namespace Funk\SbzImport\Api\Data;

interface OrdersSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Orders list.
     * @return \Funk\SbzImport\Api\Data\OrdersInterface[]
     */
    public function getItems();

    /**
     * Set order_id list.
     * @param \Funk\SbzImport\Api\Data\OrdersInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
