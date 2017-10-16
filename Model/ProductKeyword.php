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

    /**
     * @return \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
     */
    public function getAll(){
        $keywordJoinConditions = 'main_table.keyword = keyword.kwd_id';
        $productJoinConditions = 'main_table.sku = pro.sku';

        $keywordTable = $this->getResource()->getTable('funk_sbz_import_keywords');
        $productTable = $this->getResource()->getTable('funk_sbz_import_products');

        $collection = $this->getCollection();
        $collection->getSelect()->joinLeft(
            ['keyword' => $keywordTable],
            $keywordJoinConditions,
            ['sub_category','main_category']
        );
        $collection->getSelect()->joinLeft(
            ['pro' => $productTable],
            $productJoinConditions,
            ['without_sbzimport','product_db_type']
        );

        $collection->addFieldToFilter(
			['pro.without_sbzimport', 'pro.without_sbzimport'],
			[
				['null' => true],
				['neq' => 1]
			]
		);
        $collection->getSelect()->group(array());
        return $collection;
    }
}
