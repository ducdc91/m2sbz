<?php


namespace Funk\SbzImport\Api\Data;

interface OrderedProductsSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{


    /**
     * Get OrderedProducts list.
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface[]
     */
    public function getItems();

    /**
     * Set sku list.
     * @param \Funk\SbzImport\Api\Data\OrderedProductsInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
