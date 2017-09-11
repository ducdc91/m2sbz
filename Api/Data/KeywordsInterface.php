<?php


namespace Funk\SbzImport\Api\Data;

interface KeywordsInterface
{

    const KWD_ID = 'kwd_id';
    const SUB_CATEGORY = 'sub_category';
    const KEYWORD = 'keyword';
    const MAIN_CATEGORY = 'main_category';
    const ENTITY_ID = 'entity_id';

    /**
     * Get entity_id
     * @return string|null
     */
    public function getId();

    /**
     * Set entity_id
     * @param string $entity_id
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     */
    public function setId($entity_id);

    /**
     * Get kwd_id
     * @return string|null
     */
    public function getKwdId();

    /**
     * Set kwd_id
     * @param string $kwd_id
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     */
    public function setKwdId($kwd_id);

    /**
     * Get keyword
     * @return string|null
     */
    public function getKeyword();

    /**
     * Set keyword
     * @param string $keyword
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     */
    public function setKeyword($keyword);

    /**
     * Get main_category
     * @return string|null
     */
    public function getMainCategory();

    /**
     * Set main_category
     * @param string $main_category
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
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
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     */
    public function setSubCategory($sub_category);
}
