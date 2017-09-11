<?php


namespace Funk\SbzImport\Model;

use Funk\SbzImport\Api\Data\KeywordsInterface;

class Keywords extends \Magento\Framework\Model\AbstractModel implements KeywordsInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Funk\SbzImport\Model\ResourceModel\Keywords');
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
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     */
    public function setId($entity_id)
    {
        return $this->setData(self::ENTITY_ID, $entity_id);
    }

    /**
     * Get kwd_id
     * @return string
     */
    public function getKwdId()
    {
        return $this->getData(self::KWD_ID);
    }

    /**
     * Set kwd_id
     * @param string $kwd_id
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     */
    public function setKwdId($kwd_id)
    {
        return $this->setData(self::KWD_ID, $kwd_id);
    }

    /**
     * Get keyword
     * @return string
     */
    public function getKeyword()
    {
        return $this->getData(self::KEYWORD);
    }

    /**
     * Set keyword
     * @param string $keyword
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     */
    public function setKeyword($keyword)
    {
        return $this->setData(self::KEYWORD, $keyword);
    }

    /**
     * Get main_category
     * @return string
     */
    public function getMainCategory()
    {
        return $this->getData(self::MAIN_CATEGORY);
    }

    /**
     * Set main_category
     * @param string $main_category
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     */
    public function setMainCategory($main_category)
    {
        return $this->setData(self::MAIN_CATEGORY, $main_category);
    }

    /**
     * Get sub_category
     * @return string
     */
    public function getSubCategory()
    {
        return $this->getData(self::SUB_CATEGORY);
    }

    /**
     * Set sub_category
     * @param string $sub_category
     * @return \Funk\SbzImport\Api\Data\KeywordsInterface
     */
    public function setSubCategory($sub_category)
    {
        return $this->setData(self::SUB_CATEGORY, $sub_category);
    }
}
