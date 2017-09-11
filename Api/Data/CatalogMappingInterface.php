<?php


namespace Funk\SbzImport\Api\Data;

interface CatalogMappingInterface
{

    const SBZ_MAIN_CATEGORY = 'sbz_main_category';
    const ENTITY_ID = 'entity_id';
    const SBZ_SUB_CATEGORY = 'sbz_sub_category';
    const MAGE_CAT_ID = 'mage_cat_id';
	
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
     * Get mage_cat_id
     * @return string|null
     */
    public function getMageCatId();

    /**
     * Set mage_cat_id
     * @param string $mage_cat_id
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
     */
    public function setMageCatId($mage_cat_id);

    /**
     * Get sbz_main_category
     * @return string|null
     */
    public function getSbzMainCategory();

    /**
     * Set sbz_main_category
     * @param string $sbz_main_category
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
     */
    public function setSbzMainCategory($sbz_main_category);

    /**
     * Get sbz_sub_category
     * @return string|null
     */
    public function getSbzSubCategory();

    /**
     * Set sbz_sub_category
     * @param string $sbz_sub_category
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
     */
    public function setSbzSubCategory($sbz_sub_category);
}
