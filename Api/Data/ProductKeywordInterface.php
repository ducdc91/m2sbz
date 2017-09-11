<?php


namespace Funk\SbzImport\Api\Data;

interface ProductKeywordInterface
{

    const KEYWORD = 'keyword';
    const ENTITY_ID = 'entity_id';
    const SKU = 'sku';


    /**
     * Get entity_id
     * @return string|null
     */
    public function getId();

    /**
     * Set entity_id
     * @param string $entity_id
     * @return \Funk\SbzImport\Api\Data\ProductKeywordInterface
     */
    public function setId($entity_id);

    /**
     * Get sku
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     * @param string $sku
     * @return \Funk\SbzImport\Api\Data\ProductKeywordInterface
     */
    public function setSku($sku);

    /**
     * Get keyword
     * @return string|null
     */
    public function getKeyword();

    /**
     * Set keyword
     * @param string $keyword
     * @return \Funk\SbzImport\Api\Data\ProductKeywordInterface
     */
    public function setKeyword($keyword);
}
