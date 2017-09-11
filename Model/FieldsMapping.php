<?php


namespace Funk\SbzImport\Model;

use Funk\SbzImport\Api\Data\FieldsMappingInterface;

class FieldsMapping extends \Magento\Framework\Model\AbstractModel implements FieldsMappingInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Funk\SbzImport\Model\ResourceModel\FieldsMapping');
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
     * @return \Funk\SbzImport\Api\Data\FieldsMappingInterface
     */
    public function setId($entity_id)
    {
        return $this->setData(self::ENTITY_ID, $entity_id);
    }

    /**
     * Get mage_field
     * @return string
     */
    public function getMageField()
    {
        return $this->getData(self::MAGE_FIELD);
    }

    /**
     * Set mage_field
     * @param string $mage_field
     * @return \Funk\SbzImport\Api\Data\FieldsMappingInterface
     */
    public function setMageField($mage_field)
    {
        return $this->setData(self::MAGE_FIELD, $mage_field);
    }

    /**
     * Get bsz_field
     * @return string
     */
    public function getBszField()
    {
        return $this->getData(self::BSZ_FIELD);
    }

    /**
     * Set bsz_field
     * @param string $bsz_field
     * @return \Funk\SbzImport\Api\Data\FieldsMappingInterface
     */
    public function setBszField($bsz_field)
    {
        return $this->setData(self::BSZ_FIELD, $bsz_field);
    }
}
