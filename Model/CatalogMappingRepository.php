<?php


namespace Funk\SbzImport\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Funk\SbzImport\Model\ResourceModel\CatalogMapping as ResourceCatalogMapping;
use Magento\Framework\Reflection\DataObjectProcessor;
use Funk\SbzImport\Api\Data\CatalogMappingSearchResultsInterfaceFactory;
use Magento\Store\Model\StoreManagerInterface;
use Funk\SbzImport\Api\CatalogMappingRepositoryInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Funk\SbzImport\Model\ResourceModel\CatalogMapping\CollectionFactory as CatalogMappingCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Funk\SbzImport\Api\Data\CatalogMappingInterfaceFactory;

class CatalogMappingRepository implements catalogMappingRepositoryInterface
{

    private $storeManager;

    protected $searchResultsFactory;

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $dataCatalogMappingFactory;

    protected $catalogMappingFactory;

    protected $catalogMappingCollectionFactory;

    protected $resource;


    /**
     * @param ResourceCatalogMapping $resource
     * @param CatalogMappingFactory $catalogMappingFactory
     * @param CatalogMappingInterfaceFactory $dataCatalogMappingFactory
     * @param CatalogMappingCollectionFactory $catalogMappingCollectionFactory
     * @param CatalogMappingSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceCatalogMapping $resource,
        CatalogMappingFactory $catalogMappingFactory,
        CatalogMappingInterfaceFactory $dataCatalogMappingFactory,
        CatalogMappingCollectionFactory $catalogMappingCollectionFactory,
        CatalogMappingSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->catalogMappingFactory = $catalogMappingFactory;
        $this->catalogMappingCollectionFactory = $catalogMappingCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataCatalogMappingFactory = $dataCatalogMappingFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Funk\SbzImport\Api\Data\CatalogMappingInterface $catalogMapping
    ) {
        /* if (empty($catalogMapping->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $catalogMapping->setStoreId($storeId);
        } */
        try {
            $catalogMapping->getResource()->save($catalogMapping);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the catalogMapping: %1',
                $exception->getMessage()
            ));
        }
        return $catalogMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($entityId)
    {
        $catalogMapping = $this->catalogMappingFactory->create();
        $catalogMapping->getResource()->load($catalogMapping, $entityId);
        if (!$catalogMapping->getId()) {
            throw new NoSuchEntityException(__('CatalogMapping with id "%1" does not exist.', $entityId));
        }
        return $catalogMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->catalogMappingCollectionFactory->create();
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
        \Funk\SbzImport\Api\Data\CatalogMappingInterface $catalogMapping
    ) {
        try {
            $catalogMapping->getResource()->delete($catalogMapping);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the CatalogMapping: %1',
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
