<?php


namespace Funk\SbzImport\Model;

use Funk\SbzImport\Api\Data\ProductKeywordInterface;

class ProductKeyword extends \Magento\Framework\Model\AbstractModel implements ProductKeywordInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Funk\SbzImport\Model\ResourceModel\ProductKeyword');
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
     * @return \Funk\SbzImport\Api\Data\ProductKeywordInterface
     */
    public function setId($entity_id)
    {
        return $this->setData(self::ENTITY_ID, $entity_id);
    }

    /**
     * Get sku
     * @return string
     */
    public function getSku()
    {
        return $this->getData(self::SKU);
    }

    /**
     * Set sku
     * @param string $sku
     * @return \Funk\SbzImport\Api\Data\ProductKeywordInterface
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
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
     * @return \Funk\SbzImport\Api\Data\ProductKeywordInterface
     */
    public function setKeyword($keyword)
    {
        return $this->setData(self::KEYWORD, $keyword);
    }
}
