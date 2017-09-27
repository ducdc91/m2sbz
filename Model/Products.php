<?php


namespace Funk\SbzImport\Model;

use Funk\SbzImport\Api\Data\ProductsInterface;

class Products extends \Magento\Framework\Model\AbstractModel implements ProductsInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Funk\SbzImport\Model\ResourceModel\Products');
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
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setId($entity_id)
    {
        return $this->setData(self::ENTITY_ID, $entity_id);
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
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setProductId($product_id)
    {
        return $this->setData(self::PRODUCT_ID, $product_id);
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
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setSku($sku)
    {
        return $this->setData(self::SKU, $sku);
    }

    /**
     * Get product_display_nid
     * @return string
     */
    public function getProductDisplayNid()
    {
        return $this->getData(self::PRODUCT_DISPLAY_NID);
    }

    /**
     * Set product_display_nid
     * @param string $product_display_nid
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setProductDisplayNid($product_display_nid)
    {
        return $this->setData(self::PRODUCT_DISPLAY_NID, $product_display_nid);
    }

    /**
     * Get product_db_type
     * @return string
     */
    public function getProductDbType()
    {
        return $this->getData(self::PRODUCT_DB_TYPE);
    }

    /**
     * Set product_db_type
     * @param string $product_db_type
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setProductDbType($product_db_type)
    {
        return $this->setData(self::PRODUCT_DB_TYPE, $product_db_type);
    }

    /**
     * Get import
     * @return string
     */
    public function getImport()
    {
        return $this->getData(self::IMPORT);
    }

    /**
     * Set import
     * @param string $import
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setImport($import)
    {
        return $this->setData(self::IMPORT, $import);
    }

    /**
     * Get last_update
     * @return string
     */
    public function getLastUpdate()
    {
        return $this->getData(self::LAST_UPDATE);
    }

    /**
     * Set last_update
     * @param string $last_update
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setLastUpdate($last_update)
    {
        return $this->setData(self::LAST_UPDATE, $last_update);
    }

    /**
     * Get last_update_success
     * @return string
     */
    public function getLastUpdateSuccess()
    {
        return $this->getData(self::LAST_UPDATE_SUCCESS);
    }

    /**
     * Set last_update_success
     * @param string $last_update_success
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setLastUpdateSuccess($last_update_success)
    {
        return $this->setData(self::LAST_UPDATE_SUCCESS, $last_update_success);
    }

    /**
     * Get product_changed
     * @return string
     */
    public function getProductChanged()
    {
        return $this->getData(self::PRODUCT_CHANGED);
    }

    /**
     * Set product_changed
     * @param string $product_changed
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setProductChanged($product_changed)
    {
        return $this->setData(self::PRODUCT_CHANGED, $product_changed);
    }

    /**
     * Get next_stock_update
     * @return string
     */
    public function getNextStockUpdate()
    {
        return $this->getData(self::NEXT_STOCK_UPDATE);
    }

    /**
     * Set next_stock_update
     * @param string $next_stock_update
     * @return \Funk\SbzImport\Api\Data\ProductsInterface
     */
    public function setNextStockUpdate($next_stock_update)
    {
        return $this->setData(self::NEXT_STOCK_UPDATE, $next_stock_update);
    }
}
