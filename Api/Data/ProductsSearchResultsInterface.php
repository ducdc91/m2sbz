<?php


namespace Funk\SbzImport\Api\Data;

interface ProductsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get Products list.
     * @return \Funk\SbzImport\Api\Data\ProductsInterface[]
     */
    public function getItems();

    /**
     * Set product_id list.
     * @param \Funk\SbzImport\Api\Data\ProductsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
