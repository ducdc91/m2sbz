<?php


namespace Funk\SbzImport\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Funk\SbzImport\Api\Data\OrderedProductsSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Funk\SbzImport\Model\ResourceModel\OrderedProducts\CollectionFactory as OrderedProductsCollectionFactory;
use Magento\Framework\Api\SortOrder;
use Funk\SbzImport\Api\Data\OrderedProductsInterfaceFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Funk\SbzImport\Api\OrderedProductsRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Funk\SbzImport\Model\ResourceModel\OrderedProducts as ResourceOrderedProducts;

class OrderedProductsRepository implements orderedProductsRepositoryInterface
{

    private $storeManager;

    protected $searchResultsFactory;

    protected $orderedProductsFactory;

    protected $dataObjectProcessor;

    protected $orderedProductsCollectionFactory;

    protected $dataObjectHelper;

    protected $dataOrderedProductsFactory;

    protected $resource;


    /**
     * @param ResourceOrderedProducts $resource
     * @param OrderedProductsFactory $orderedProductsFactory
     * @param OrderedProductsInterfaceFactory $dataOrderedProductsFactory
     * @param OrderedProductsCollectionFactory $orderedProductsCollectionFactory
     * @param OrderedProductsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceOrderedProducts $resource,
        OrderedProductsFactory $orderedProductsFactory,
        OrderedProductsInterfaceFactory $dataOrderedProductsFactory,
        OrderedProductsCollectionFactory $orderedProductsCollectionFactory,
        OrderedProductsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->orderedProductsFactory = $orderedProductsFactory;
        $this->orderedProductsCollectionFactory = $orderedProductsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataOrderedProductsFactory = $dataOrderedProductsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Funk\SbzImport\Api\Data\OrderedProductsInterface $orderedProducts
    ) {
        /* if (empty($orderedProducts->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $orderedProducts->setStoreId($storeId);
        } */
        try {
            $orderedProducts->getResource()->save($orderedProducts);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the orderedProducts: %1',
                $exception->getMessage()
            ));
        }
        return $orderedProducts;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($entityId)
    {
        $orderedProducts = $this->orderedProductsFactory->create();
        $orderedProducts->getResource()->load($orderedProducts, $entityId);
        if (!$orderedProducts->getId()) {
            throw new NoSuchEntityException(__('OrderedProducts with id "%1" does not exist.', $entityId));
        }
        return $orderedProducts;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->orderedProductsCollectionFactory->create();
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
        \Funk\SbzImport\Api\Data\OrderedProductsInterface $orderedProducts
    ) {
        try {
            $orderedProducts->getResource()->delete($orderedProducts);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the OrderedProducts: %1',
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
