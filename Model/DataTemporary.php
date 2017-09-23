<?php


namespace Funk\SbzImport\Model;

use Funk\SbzImport\Api\Data\DataTemporaryInterface;

class DataTemporary extends \Magento\Framework\Model\AbstractModel implements DataTemporaryInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Funk\SbzImport\Model\ResourceModel\DataTemporary');
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
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
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
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }
	
    /**
     * Get product_id
     * @return string
     */
    public function getProductId()
    {
        return $this->getData(self::PRODUCT_ID);
    }

    /**
     * Set product_id
     * @param string $product_id
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setProductId($product_id)
    {
        return $this->setData(self::PRODUCT_ID, $product_id);
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
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
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
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setSubCategory($sub_category)
    {
        return $this->setData(self::SUB_CATEGORY, $sub_category);
    }

    /**
     * Get data_content
     * @return string
     */
    public function getDataContent()
    {
        return $this->getData(self::DATA_CONTENT);
    }

    /**
     * Set data_content
     * @param string $data_content
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setDataContent($data_content)
    {
        return $this->setData(self::DATA_CONTENT, $data_content);
    }

    /**
     * Get create_date
     * @return string
     */
    public function getCreateDate()
    {
        return $this->getData(self::CREATE_DATE);
    }

    /**
     * Set create_date
     * @param string $create_date
     * @return \Funk\SbzImport\Api\Data\DataTemporaryInterface
     */
    public function setCreateDate($create_date)
    {
        return $this->setData(self::CREATE_DATE, $create_date);
    }
}
