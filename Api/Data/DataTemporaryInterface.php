<?php


namespace Funk\SbzImport\Api\Data;

interface DataTemporaryInterface
{

    const CREATE_DATE = 'create_date';
    const ENTITY_ID = 'entity_id';
    const SKU = 'sku';
    const DATA_CONTENT = 'data_content';

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
