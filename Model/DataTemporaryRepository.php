<?php


namespace Funk\SbzImport\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Funk\SbzImport\Api\Data\DataTemporaryInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Funk\SbzImport\Api\DataTemporaryRepositoryInterface;
use Funk\SbzImport\Model\ResourceModel\DataTemporary\CollectionFactory as DataTemporaryCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Funk\SbzImport\Model\ResourceModel\DataTemporary as ResourceDataTemporary;
use Funk\SbzImport\Api\Data\DataTemporarySearchResultsInterfaceFactory;

class DataTemporaryRepository implements dataTemporaryRepositoryInterface
{

    protected $dataTemporaryFactory;

    private $storeManager;

    protected $searchResultsFactory;

    protected $dataDataTemporaryFactory;

    protected $dataObjectProcessor;

    protected $dataTemporaryCollectionFactory;

    protected $dataObjectHelper;

    protected $resource;


    /**
     * @param ResourceDataTemporary $resource
     * @param DataTemporaryFactory $dataTemporaryFactory
     * @param DataTemporaryInterfaceFactory $dataDataTemporaryFactory
     * @param DataTemporaryCollectionFactory $dataTemporaryCollectionFactory
     * @param DataTemporarySearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceDataTemporary $resource,
        DataTemporaryFactory $dataTemporaryFactory,
        DataTemporaryInterfaceFactory $dataDataTemporaryFactory,
        DataTemporaryCollectionFactory $dataTemporaryCollectionFactory,
        DataTemporarySearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->dataTemporaryFactory = $dataTemporaryFactory;
        $this->dataTemporaryCollectionFactory = $dataTemporaryCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataDataTemporaryFactory = $dataDataTemporaryFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Funk\SbzImport\Api\Data\DataTemporaryInterface $dataTemporary
    ) {
        /* if (empty($dataTemporary->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $dataTemporary->setStoreId($storeId);
        } */
        try {
            $dataTemporary->getResource()->save($dataTemporary);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the dataTemporary: %1',
                $exception->getMessage()
            ));
        }
        return $dataTemporary;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($entityId)
    {
        $dataTemporary = $this->dataTemporaryFactory->create();
        $dataTemporary->getResource()->load($dataTemporary, $entityId);
        if (!$dataTemporary->getId()) {
            throw new NoSuchEntityException(__('DataTemporary with id "%1" does not exist.', $entityId));
        }
        return $dataTemporary;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->dataTemporaryCollectionFactory->create();
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
        \Funk\SbzImport\Api\Data\DataTemporaryInterface $dataTemporary
    ) {
        try {
            $dataTemporary->getResource()->delete($dataTemporary);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the DataTemporary: %1',
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
