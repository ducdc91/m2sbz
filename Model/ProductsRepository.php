<?php


namespace Funk\SbzImport\Model;

use Funk\SbzImport\Model\ResourceModel\Products\CollectionFactory as ProductsCollectionFactory;
use Funk\SbzImport\Api\ProductsRepositoryInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Funk\SbzImport\Api\Data\ProductsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Funk\SbzImport\Api\Data\ProductsSearchResultsInterfaceFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Funk\SbzImport\Model\ResourceModel\Products as ResourceProducts;
use Magento\Framework\Api\DataObjectHelper;

class ProductsRepository implements productsRepositoryInterface
{

    protected $productsCollectionFactory;

    protected $productsFactory;

    private $storeManager;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $dataProductsFactory;

    protected $resource;


    /**
     * @param ResourceProducts $resource
     * @param ProductsFactory $productsFactory
     * @param ProductsInterfaceFactory $dataProductsFactory
     * @param ProductsCollectionFactory $productsCollectionFactory
     * @param ProductsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceProducts $resource,
        ProductsFactory $productsFactory,
        ProductsInterfaceFactory $dataProductsFactory,
        ProductsCollectionFactory $productsCollectionFactory,
        ProductsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->productsFactory = $productsFactory;
        $this->productsCollectionFactory = $productsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataProductsFactory = $dataProductsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Funk\SbzImport\Api\Data\ProductsInterface $products
    ) {
        /* if (empty($products->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $products->setStoreId($storeId);
        } */
        try {
            $products->getResource()->save($products);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the products: %1',
                $exception->getMessage()
            ));
        }
        return $products;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($entityId)
    {
        $products = $this->productsFactory->create();
        $products->getResource()->load($products, $entityId);
        if (!$products->getId()) {
            throw new NoSuchEntityException(__('Products with id "%1" does not exist.', $entityId));
        }
        return $products;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->productsCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setTotalCount($collection->getSize());
        $searchResults->setItems($collection->getItems());
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \Funk\SbzImport\Api\Data\ProductsInterface $products
    ) {
        try {
            $products->getResource()->delete($products);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Products: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($entityId)
    {
        return $this->delete($this->getById($entityId));
    }
}
