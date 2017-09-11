<?php


namespace Funk\SbzImport\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Funk\SbzImport\Model\ResourceModel\Keywords as ResourceKeywords;
use Funk\SbzImport\Api\Data\KeywordsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Funk\SbzImport\Api\KeywordsRepositoryInterface;
use Funk\SbzImport\Api\Data\KeywordsSearchResultsInterfaceFactory;
use Funk\SbzImport\Model\ResourceModel\Keywords\CollectionFactory as KeywordsCollectionFactory;

class KeywordsRepository implements keywordsRepositoryInterface
{

    private $storeManager;

    protected $dataKeywordsFactory;

    protected $searchResultsFactory;

    protected $keywordsFactory;

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $keywordsCollectionFactory;

    protected $resource;


    /**
     * @param ResourceKeywords $resource
     * @param KeywordsFactory $keywordsFactory
     * @param KeywordsInterfaceFactory $dataKeywordsFactory
     * @param KeywordsCollectionFactory $keywordsCollectionFactory
     * @param KeywordsSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceKeywords $resource,
        KeywordsFactory $keywordsFactory,
        KeywordsInterfaceFactory $dataKeywordsFactory,
        KeywordsCollectionFactory $keywordsCollectionFactory,
        KeywordsSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->keywordsFactory = $keywordsFactory;
        $this->keywordsCollectionFactory = $keywordsCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataKeywordsFactory = $dataKeywordsFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Funk\SbzImport\Api\Data\KeywordsInterface $keywords
    ) {
        /* if (empty($keywords->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $keywords->setStoreId($storeId);
        } */
        try {
            $keywords->getResource()->save($keywords);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the keywords: %1',
                $exception->getMessage()
            ));
        }
        return $keywords;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($entityId)
    {
        $keywords = $this->keywordsFactory->create();
        $keywords->getResource()->load($keywords, $entityId);
        if (!$keywords->getId()) {
            throw new NoSuchEntityException(__('Keywords with id "%1" does not exist.', $entityId));
        }
        return $keywords;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->keywordsCollectionFactory->create();
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
        \Funk\SbzImport\Api\Data\KeywordsInterface $keywords
    ) {
        try {
            $keywords->getResource()->delete($keywords);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Keywords: %1',
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
