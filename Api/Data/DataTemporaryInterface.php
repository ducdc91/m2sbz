<?php


namespace Funk\SbzImport\Api\Data;

interface DataTemporaryInterface
{

    const CREATE_DATE = 'create_date';
    const ENTITY_ID = 'entity_id';
    const SKU = 'sku';
    const PRODUCT_ID = 'product_id';
    const DATA_CONTENT = 'data_content';
    const MAIN_CATEGORY = 'main_category';
    const SUB_CATEGORY = 'sub_category';
    const PRODUCT_TYPE = 'product_type';

    /**
     * Get entity_id
     * @return string|null
     */
    public function getId();

    /**
     * Set entity_id
     * @param string $entity_id
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
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
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
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
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setProductId($product_id);

    /**
     * Get main_category
     * @return string|null
     */
    public function getMainCategory();

    /**
     * Set main_category
     * @param string $main_category
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setMainCategory($main_category);

    /**
     * Get sub_category
     * @return string|null
     */
    public function getSubCategory();

    /**
     * Set sub_category
     * @param string $sub_category
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setSubCategory($sub_category);

    /**
     * Get data_content
     * @return string|null
     */
    public function getDataContent();

    /**
     * Set data_content
     * @param string $data_content
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setDataContent($data_content);

    /**
     * Get product_type
     * @return string
     */
    public function getProductType();

    /**
     * Set product_type
     * @param string $product_type
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setProductType($product_type);

    /**
     * Get create_date
     * @return string|null
     */
    public function getCreateDate();

    /**
     * Set create_date
     * @param string $create_date
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setCreateDate($create_date);
}
