<?php


namespace Funk\SbzImport\Model;

use Funk\SbzImport\Api\Data\CatalogMappingInterface;

class CatalogMapping extends \Magento\Framework\Model\AbstractModel implements CatalogMappingInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Funk\SbzImport\Model\ResourceModel\CatalogMapping');
    }

    /**
     * Get entity_id
     * @return string
     */
    public function getId()
    {
        return $this->getData(self::ENTITY_ID);
    }

    /**
     * Set entity_id
     * @param string $entity_id
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
     */
    public function setId($entity_id)
    {
        return $this->setData(self::ENTITY_ID, $entity_id);
    }

    /**
     * Get mage_cat_id
     * @return string
     */
    public function getMageCatId()
    {
        return $this->getData(self::MAGE_CAT_ID);
    }

    /**
     * Set mage_cat_id
     * @param string $mage_cat_id
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
     */
    public function setMageCatId($mage_cat_id)
    {
        return $this->setData(self::MAGE_CAT_ID, $mage_cat_id);
    }

    /**
     * Get sbz_main_category
     * @return string
     */
    public function getSbzMainCategory()
    {
        return $this->getData(self::SBZ_MAIN_CATEGORY);
    }

    /**
     * Set sbz_main_category
     * @param string $sbz_main_category
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
     */
    public function setSbzMainCategory($sbz_main_category)
    {
        return $this->setData(self::SBZ_MAIN_CATEGORY, $sbz_main_category);
    }

    /**
     * Get sbz_sub_category
     * @return string
     */
    public function getSbzSubCategory()
    {
        return $this->getData(self::SBZ_SUB_CATEGORY);
    }

    /**
     * Set sbz_sub_category
     * @param string $sbz_sub_category
     * @return \Funk\SbzImport\Api\Data\CatalogMappingInterface
     */
    public function setSbzSubCategory($sbz_sub_category)
    {
        return $this->setData(self::SBZ_SUB_CATEGORY, $sbz_sub_category);
    }
}
