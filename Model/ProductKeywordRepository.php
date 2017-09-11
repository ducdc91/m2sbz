<?php


namespace Funk\SbzImport\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Funk\SbzImport\Api\Data\ProductKeywordSearchResultsInterfaceFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Funk\SbzImport\Api\ProductKeywordRepositoryInterface;
use Funk\SbzImport\Model\ResourceModel\ProductKeyword as ResourceProductKeyword;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\DataObjectHelper;
use Funk\SbzImport\Api\Data\ProductKeywordInterfaceFactory;
use Funk\SbzImport\Model\ResourceModel\ProductKeyword\CollectionFactory as ProductKeywordCollectionFactory;

class ProductKeywordRepository implements productKeywordRepositoryInterface
{

    private $storeManager;

    protected $productKeywordCollectionFactory;

    protected $searchResultsFactory;

    protected $dataProductKeywordFactory;

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $productKeywordFactory;

    protected $resource;


    /**
     * @param ResourceProductKeyword $resource
     * @param ProductKeywordFactory $productKeywordFactory
     * @param ProductKeywordInterfaceFactory $dataProductKeywordFactory
     * @param ProductKeywordCollectionFactory $productKeywordCollectionFactory
     * @param ProductKeywordSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceProductKeyword $resource,
        ProductKeywordFactory $productKeywordFactory,
        ProductKeywordInterfaceFactory $dataProductKeywordFactory,
        ProductKeywordCollectionFactory $productKeywordCollectionFactory,
        ProductKeywordSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->productKeywordFactory = $productKeywordFactory;
        $this->productKeywordCollectionFactory = $productKeywordCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataProductKeywordFactory = $dataProductKeywordFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Funk\SbzImport\Api\Data\ProductKeywordInterface $productKeyword
    ) {
        /* if (empty($productKeyword->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $productKeyword->setStoreId($storeId);
        } */
        try {
            $productKeyword->getResource()->save($productKeyword);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the productKeyword: %1',
                $exception->getMessage()
            ));
        }
        return $productKeyword;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($entityId)
    {
        $productKeyword = $this->productKeywordFactory->create();
        $productKeyword->getResource()->load($productKeyword, $entityId);
        if (!$productKeyword->getId()) {
            throw new NoSuchEntityException(__('ProductKeyword with id "%1" does not exist.', $entityId));
        }
        return $productKeyword;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->productKeywordCollectionFactory->create();
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
        \Funk\SbzImport\Api\Data\ProductKeywordInterface $productKeyword
    ) {
        try {
            $productKeyword->getResource()->delete($productKeyword);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the ProductKeyword: %1',
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
