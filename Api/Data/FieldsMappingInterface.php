<?php


namespace Funk\SbzImport\Api\Data;

interface FieldsMappingInterface
{

    const ENTITY_ID = 'entity_id';
    const BSZ_FIELD = 'bsz_field';
    const MAGE_FIELD = 'mage_field';

    /**
     * Get entity_id
     * @return string|null
     */
    public function getId();

    /**
     * Set entity_id
     * @param string $entity_id
     * @return \Funk\SbzImport\Api\Data\FieldsMappingInterface
     */
    public function setId($entity_id);

    /**
     * Get mage_field
     * @return string|null
     */
    public function getMageField();

    /**
     * Set mage_field
     * @param string $mage_field
     * @return \Funk\SbzImport\Api\Data\FieldsMappingInterface
     */
    public function setMageField($mage_field);

    /**
     * Get bsz_field
     * @return string|null
     */
    public function getBszField();

    /**
     * Set bsz_field
     * @param string $bsz_field
     * @return \Funk\SbzImport\Api\Data\FieldsMappingInterface
     */
    public function setBszField($bsz_field);
}
