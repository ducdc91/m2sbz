<?php


namespace Funk\SbzImport\Model;

use Funk\SbzImport\Api\Data\OrdersInterfaceFactory;
use Funk\SbzImport\Api\OrdersRepositoryInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Funk\SbzImport\Model\ResourceModel\Orders as ResourceOrders;
use Magento\Framework\Api\DataObjectHelper;
use Funk\SbzImport\Model\ResourceModel\Orders\CollectionFactory as OrdersCollectionFactory;
use Funk\SbzImport\Api\Data\OrdersSearchResultsInterfaceFactory;

class OrdersRepository implements ordersRepositoryInterface
{

    protected $ordersCollectionFactory;

    protected $ordersFactory;

    private $storeManager;

    protected $searchResultsFactory;

    protected $dataOrdersFactory;

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $resource;


    /**
     * @param ResourceOrders $resource
     * @param OrdersFactory $ordersFactory
     * @param OrdersInterfaceFactory $dataOrdersFactory
     * @param OrdersCollectionFactory $ordersCollectionFactory
     * @param OrdersSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceOrders $resource,
        OrdersFactory $ordersFactory,
        OrdersInterfaceFactory $dataOrdersFactory,
        OrdersCollectionFactory $ordersCollectionFactory,
        OrdersSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->ordersFactory = $ordersFactory;
        $this->ordersCollectionFactory = $ordersCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataOrdersFactory = $dataOrdersFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Funk\SbzImport\Api\Data\OrdersInterface $orders
    ) {
        /* if (empty($orders->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $orders->setStoreId($storeId);
        } */
        try {
            $orders->getResource()->save($orders);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the orders: %1',
                $exception->getMessage()
            ));
        }
        return $orders;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($entityId)
    {
        $orders = $this->ordersFactory->create();
        $orders->getResource()->load($orders, $entityId);
        if (!$orders->getId()) {
            throw new NoSuchEntityException(__('Orders with id "%1" does not exist.', $entityId));
        }
        return $orders;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->ordersCollectionFactory->create();
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
        \Funk\SbzImport\Api\Data\OrdersInterface $orders
    ) {
        try {
            $orders->getResource()->delete($orders);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Orders: %1',
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
