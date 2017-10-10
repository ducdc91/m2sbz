<?php


namespace Funk\SbzImport\Api\Data;

interface ProductsInterface
{

    const PRODUCT_DB_TYPE = 'product_db_type';
    const LAST_UPDATE = 'last_update';
    const IMPORT = 'import';
    const LAST_UPDATE_SUCCESS = 'last_update_success';
    const SKU = 'sku';
    const PRODUCT_DISPLAY_NID = 'product_display_nid';
    const PRODUCT_ID = 'product_id';
    const ENTITY_ID = 'entity_id';
    const PRODUCT_CHANGED = 'product_changed';
    const NEXT_STOCK_UPDATE = 'next_stock_update';
    const WITHOUT_SBZIMPORT = 'without_sbzimport';


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
     * Get product_id
     * @return string|null
     */
    public function getProductId();

    /**
     * Set product_id
     * @param string $product_id
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setProductId($product_id);

    /**
     * Get sku
     * @return string|null
     */
    public function getSku();

    /**
     * Set sku
     * @param string $sku
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setSku($sku);

    /**
     * Get product_display_nid
     * @return string|null
     */
    public function getProductDisplayNid();

    /**
     * Set product_display_nid
     * @param string $product_display_nid
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setProductDisplayNid($product_display_nid);

    /**
     * Get product_db_type
     * @return string|null
     */
    public function getProductDbType();

    /**
     * Set product_db_type
     * @param string $product_db_type
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setProductDbType($product_db_type);

    /**
     * Get import
     * @return string|null
     */
    public function getImport();

    /**
     * Set import
     * @param string $import
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setImport($import);

    /**
     * Get last_update
     * @return string|null
     */
    public function getLastUpdate();

    /**
     * Set last_update
     * @param string $last_update
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setLastUpdate($last_update);

    /**
     * Get last_update_success
     * @return string|null
     */
    public function getLastUpdateSuccess();

    /**
     * Set last_update_success
     * @param string $last_update_success
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setLastUpdateSuccess($last_update_success);

    /**
     * Get product_changed
     * @return string|null
     */
    public function getProductChanged();

    /**
     * Set product_changed
     * @param string $product_changed
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setProductChanged($product_changed);

    /**
     * Get next_stock_update
     * @return string|null
     */
    public function getNextStockUpdate();

    /**
     * Set next_stock_update
     * @param string $next_stock_update
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setNextStockUpdate($next_stock_update);


    /**
     * Get without_sbzimport
     * @return string
     */
    public function getWithoutSbzimport();

    /**
     * Set without_sbzimport
     * @param string $without_sbzimport
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setWithoutSbzimport($without_sbzimport);
}
