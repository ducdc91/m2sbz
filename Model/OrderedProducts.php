<?php


namespace Funk\SbzImport\Model;

use Funk\SbzImport\Api\Data\OrderedProductsInterface;

class OrderedProducts extends \Magento\Framework\Model\AbstractModel implements OrderedProductsInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Funk\SbzImport\Model\ResourceModel\OrderedProducts');
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
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
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
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
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
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setProductId($product_id)
    {
        return $this->setData(self::PRODUCT_ID, $product_id);
    }

    /**
     * Get sbz_order_id
     * @return string
     */
    public function getSbzOrderId()
    {
        return $this->getData(self::SBZ_ORDER_ID);
    }

    /**
     * Set sbz_order_id
     * @param string $sbz_order_id
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setSbzOrderId($sbz_order_id)
    {
        return $this->setData(self::SBZ_ORDER_ID, $sbz_order_id);
    }

    /**
     * Get position
     * @return string
     */
    public function getPosition()
    {
        return $this->getData(self::POSITION);
    }

    /**
     * Set position
     * @param string $position
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setPosition($position)
    {
        return $this->setData(self::POSITION, $position);
    }

    /**
     * Get product_type
     * @return string
     */
    public function getProductType()
    {
        return $this->getData(self::PRODUCT_TYPE);
    }

    /**
     * Set product_type
     * @param string $product_type
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setProductType($product_type)
    {
        return $this->setData(self::PRODUCT_TYPE, $product_type);
    }

    /**
     * Get url
     * @return string
     */
    public function getUrl()
    {
        return $this->getData(self::URL);
    }

    /**
     * Set url
     * @param string $url
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setUrl($url)
    {
        return $this->setData(self::URL, $url);
    }

    /**
     * Get qty
     * @return string
     */
    public function getQty()
    {
        return $this->getData(self::QTY);
    }

    /**
     * Set qty
     * @param string $qty
     * @return \Funk\SbzImport\Api\Data\OrderedProductsInterface
     */
    public function setQty($qty)
    {
        return $this->setData(self::QTY, $qty);
    }

    public function loadByOrderIdAndSku($orderId, $sku){
        $collection = $this->getCollection();
        $collection->addFieldToFilter('sbz_order_id',array('eq' => $orderId));
        $collection->addFieldToFilter('sku',array('eq' => $sku));

        return $collection->getFirstItem();
    }
}
