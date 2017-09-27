<?php


namespace Funk\SbzImport\Api\Data;

interface OrderedProductsInterface
{

    const ENTITY_ID = 'entity_id';
    const URL = 'url';
    const PRODUCT_TYPE = 'product_type';
    const SKU = 'sku';
    const POSITION = 'position';
    const PRODUCT_ID = 'product_id';
    const SBZ_ORDER_ID = 'sbz_order_id';
    const QTY = 'qty';


    /**
     * Get entity_id
     * @return string|null
     */
    public function getId();

    /**
     * Set entity_id
     * @param string $entity_id
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
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
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setSku($sku);

    /**
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $product_id
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setProductId($product_id);

    /**
     * Get sbz_order_id
     * @return string|null
     */
    public function getSbzOrderId();

    /**
     * Set sbz_order_id
     * @param string $sbz_order_id
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setSbzOrderId($sbz_order_id);

    /**
     * Get position
     * @return string|null
     */
    public function getPosition();

    /**
     * Set position
     * @param string $position
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setPosition($position);

    /**
     * Get product_type
     * @return string|null
     */
    public function getProductType();

    /**
     * Set product_type
     * @param string $product_type
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setProductType($product_type);

    /**
     * Get url
     * @return string|null
     */
    public function getUrl();

    /**
     * Set url
     * @param string $url
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setUrl($url);

    /**
     * Get qty
     * @return string
     */
    public function getQty();

    /**
     * Set qty
     * @param string $qty
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setQty($qty);
}
