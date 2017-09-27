<?php

namespace Funk\SbzImport\Model;

use Magento\Framework\App\ObjectManagerFactory;

class UpdateStock
{
    const BZ_PRODUCTS_VALUE_TO_ADD_TO_STOCK = 1000;

    public $REQUEST_TIME ;
    /**
     * @var Sbz\Request
     */
    protected $_sbzRequest;

    /**
     * @var Import\UpdateStock
     */
    protected $_updateStock;

    /**
     * @var \Funk\SbzImport\Model\DataTemporaryFactory
     */
    protected $_dataTemporaryFactory;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    protected $_resourceConnection;

    /**
     * @var \Funk\SbzImport\Api\Data\ProductsInterface
     */
    protected $_productsInterface;

    /**
     * @var \Funk\SbzImport\Api\ProductsRepositoryInterface
     */
    protected $_productsRepositoryInterface;

    /**
     * @var ProductsFactory
     */
    protected $_productsFactory;

    protected $_objectManager;

    protected $_objectManagerFactory;
    /**
     * Product constructor.
     * @param \Funk\SbzImport\Model\Sbz\Import $sbzImport
     * @param \Funk\SbzImport\Model\Import\Product $productImport
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Funk\SbzImport\Model\DataTemporaryFactory $dataTemporaryFactory
     */
    public function __construct(
        \Funk\SbzImport\Model\Sbz\Request $sbzRequest,
        \Funk\SbzImport\Model\Import\UpdateStock $updateStock,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Funk\SbzImport\Api\Data\ProductsInterface $productsInterface,
        \Funk\SbzImport\Api\ProductsRepositoryInterface $productsRepositoryInterface,
        \Funk\SbzImport\Model\ProductsFactory $productsFactory
    )
    {
        $this->REQUEST_TIME = (int) $_SERVER['REQUEST_TIME'];
        $this->_sbzRequest = $sbzRequest;
        $this->_updateStock = $updateStock;
        $this->_resourceConnection = $resourceConnection;
        $this->_productsInterface = $productsInterface;
        $this->_productsRepositoryInterface = $productsRepositoryInterface;
        $this->_productsFactory = $productsFactory;
        $this->_logger = $logger;

    }

    public function execute(){
        $collection = $this->getStockItems();
        foreach ($collection as $item){
            //Get stock from sbz API
            $sku = $item->getSku();
            $qty = $this->getStockFromSbz($sku);
            $data["product_id"] =   $item->getProductId();
            $data["_is_new"] =  false;
            $data["qty"] =   $qty;
            $this->_updateStock->import($data);
            $sbz_stock_rules  = $this->getSbzStockUpdateRules();
            foreach ($sbz_stock_rules as $rule){
                if ($qty >= $rule['min'] && $qty <= $rule['max']){
                    $next_update = $this->REQUEST_TIME + $rule['frequency'];
                    $item->setNextStockUpdate($next_update);
                    $item->save();
                    break;
                }
            }
        }
    }


    public function getStockItems(){
        $collection = $this->_productsFactory->create()->getCollection();
        $collection->addFieldToFilter('next_stock_update',array('lt' => $this->REQUEST_TIME));
        $collection->addFieldToFilter('product_id',array('gt' => 0));
        $collection->addFieldToFilter('product_db_type',array('nlike' => '%download%'));
        $collection->setOrder('next_stock_update','ASC');
        return $collection;
    }

    /**
     * @param $sku
     * Get stock from sbz API
     * @return bool
     */
    private function getStockFromSbz($sku){
        $results = $this->_sbzRequest->clearResults()->getSalesinfo($sku)->getResults();
        if (isset($results['Bestand']) && is_numeric($results['Bestand'])){
            return $results['Bestand'] + self::BZ_PRODUCTS_VALUE_TO_ADD_TO_STOCK;
        }

        return FALSE;
    }

    private function getSbzStockUpdateRules(){
        return array(
            array(
                'min' => 0,
                'max' => 0 + self::BZ_PRODUCTS_VALUE_TO_ADD_TO_STOCK,
                'frequency' => 60 * 60 * 24 * 3,
            ),
            array(
                'min' => 1 + self::BZ_PRODUCTS_VALUE_TO_ADD_TO_STOCK,
                'max' => 3 + self::BZ_PRODUCTS_VALUE_TO_ADD_TO_STOCK,
                'frequency' => 60 * 60 * 3,
            ),
            array(
                'min' => 4 + self::BZ_PRODUCTS_VALUE_TO_ADD_TO_STOCK,
                'max' => 10 + self::BZ_PRODUCTS_VALUE_TO_ADD_TO_STOCK,
                'frequency' => 60 * 60 * 24,
            ),
            array(
                'min' => 11 + self::BZ_PRODUCTS_VALUE_TO_ADD_TO_STOCK,
                'max' => 50 + self::BZ_PRODUCTS_VALUE_TO_ADD_TO_STOCK,
                'frequency' => 60 * 60 * 24 * 2,
            ),
            array(
                'min' => 51 + self::BZ_PRODUCTS_VALUE_TO_ADD_TO_STOCK,
                'max' => PHP_INT_MAX,
                'frequency' => 60 * 60 * 24 * 4,
            ),
        );
    }
}