<?php

namespace Funk\SbzImport\Model;

use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\ObjectManagerFactory;

class Import
{
    /**
     * @var \Funk\SbzImport\Model\Sbz\Import
     */
    protected $_sbzImport;

    /**
     * @var \Funk\SbzImport\Model\Import\Product
     */
    protected $_productImport;

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
        \Funk\SbzImport\Model\Sbz\Import $sbzImport,
        \Funk\SbzImport\Model\Import\Product $productImport,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Funk\SbzImport\Api\Data\ProductsInterface $productsInterface,
        \Funk\SbzImport\Api\ProductsRepositoryInterface $productsRepositoryInterface,
        \Funk\SbzImport\Model\DataTemporaryFactory $dataTemporaryFactory,
        \Funk\SbzImport\Model\ProductsFactory $productsFactory,
        ObjectManagerFactory $objectManagerFactory
    )
    {
        $this->_sbzImport = $sbzImport;
        $this->_productImport = $productImport;
        $this->_dataTemporaryFactory = $dataTemporaryFactory;
        $this->_resourceConnection = $resourceConnection;
        $this->_productsInterface = $productsInterface;
        $this->_productsRepositoryInterface = $productsRepositoryInterface;
        $this->_productsFactory = $productsFactory;
        $this->_objectManagerFactory = $objectManagerFactory;
        $this->_logger = $logger;
    }

    public function execute(){
        $this->downloadSbzData();
        $this->importProduct();
        $this->importStockSetting();
    }

    private function downloadSbzData(){
        $this->_sbzImport->execute();
    }

    private function importProduct(){
        $dataTemporary = $this->_dataTemporaryFactory->create();
        $dataCollection = $dataTemporary->getCollection();
        foreach ($dataCollection as $item) {
            $data = unserialize($item->getDataContent());
            $data["product_id"] = $item->getProductId();
            $data["sku"] = $item->getSku();
            $data["main_category"] = $item->getMainCategory();
            $data["sub_category"] = $item->getSubCategory();
            $data["product_type"] =$item->getProductType() ;
            $this->_productImport->import($data);
        }
        $this->reindexSystemData();
    }

    private function importStockSetting(){
        $products = $this->getNewStockProducts();
        foreach ($products as $product){
            $productSku = $product['sku'];
            $newProduct = $this->_productsFactory->create()->loadBySku($productSku);
            $newProduct->setProductId($product['entity_id']);
            $newProduct->setSku($product['sku']);
            $productType = '';
            if($product['keyword'] == 'download'){
                $productType = 'download';
            }
            $newProduct->setProductDbType($productType);
            $this->_productsRepositoryInterface->save($newProduct);
        }
    }

    private function getNewStockProducts(){
        $resource = $this->_resourceConnection;
        $connection = $resource->getConnection();
        $productTable = $resource->getTableName('catalog_product_entity');
        $sql = "
              SELECT
                m.entity_id,
                m.sku,
                pk.keyword
                FROM
                {$productTable} as m
                INNER JOIN funk_sbz_import_product_keyword as pk ON m.sku = pk.sku 
                where pk.sku  not in (Select sku from funk_sbz_import_products)
              ";
        $result = $connection->fetchAll($sql);
        return $result;
    }

    protected function reindexSystemData()
    {
        $indexer = $this->getObjectManager()->create(
            'Magento\Indexer\Model\Indexer'
        );
        $indexerList = array(
            'catalog_category_product',
            'catalog_product_category',
            'catalog_product_price',
            'catalog_product_attribute',
            'cataloginventory_stock',
            'catalogrule_product',
            'catalogsearch_fulltext'

        );
        foreach ($indexerList as $item) {
            $indexer->load($item);
            $indexer->reindexAll();
        }
    }


    protected function getObjectManager()
    {
        if (null == $this->_objectManager) {
            $area = FrontNameResolver::AREA_CODE;
            $this->_objectManager = $this->_objectManagerFactory->create($_SERVER);
            /** @var \Magento\Framework\App\State $appState */
            $appState = $this->_objectManager->get('Magento\Framework\App\State');
            $appState->setAreaCode($area);
            $configLoader = $this->_objectManager->get('Magento\Framework\ObjectManager\ConfigLoaderInterface');
            $this->_objectManager->configure($configLoader->load($area));
        }
        return $this->_objectManager;
    }
}