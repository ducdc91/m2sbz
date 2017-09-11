<?php


namespace Funk\SbzImport\Model;

use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Funk\SbzImport\Api\FieldsMappingRepositoryInterface;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Funk\SbzImport\Api\Data\FieldsMappingSearchResultsInterfaceFactory;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;
use Funk\SbzImport\Api\Data\FieldsMappingInterfaceFactory;
use Funk\SbzImport\Model\ResourceModel\FieldsMapping\CollectionFactory as FieldsMappingCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Funk\SbzImport\Model\ResourceModel\FieldsMapping as ResourceFieldsMapping;

class FieldsMappingRepository implements fieldsMappingRepositoryInterface
{

    private $storeManager;

    protected $fieldsMappingFactory;

    protected $searchResultsFactory;

    protected $fieldsMappingCollectionFactory;

    protected $dataFieldsMappingFactory;

    protected $dataObjectProcessor;

    protected $dataObjectHelper;

    protected $resource;


    /**
     * @param ResourceFieldsMapping $resource
     * @param FieldsMappingFactory $fieldsMappingFactory
     * @param FieldsMappingInterfaceFactory $dataFieldsMappingFactory
     * @param FieldsMappingCollectionFactory $fieldsMappingCollectionFactory
     * @param FieldsMappingSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceFieldsMapping $resource,
        FieldsMappingFactory $fieldsMappingFactory,
        FieldsMappingInterfaceFactory $dataFieldsMappingFactory,
        FieldsMappingCollectionFactory $fieldsMappingCollectionFactory,
        FieldsMappingSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->fieldsMappingFactory = $fieldsMappingFactory;
        $this->fieldsMappingCollectionFactory = $fieldsMappingCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataFieldsMappingFactory = $dataFieldsMappingFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \Funk\SbzImport\Api\Data\FieldsMappingInterface $fieldsMapping
    ) {
        /* if (empty($fieldsMapping->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $fieldsMapping->setStoreId($storeId);
        } */
        try {
            $fieldsMapping->getResource()->save($fieldsMapping);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the fieldsMapping: %1',
                $exception->getMessage()
            ));
        }
        return $fieldsMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($entityId)
    {
        $fieldsMapping = $this->fieldsMappingFactory->create();
        $fieldsMapping->getResource()->load($fieldsMapping, $entityId);
        if (!$fieldsMapping->getId()) {
            throw new NoSuchEntityException(__('FieldsMapping with id "%1" does not exist.', $entityId));
        }
        return $fieldsMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $collection = $this->fieldsMappingCollectionFactory->create();
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
        \Funk\SbzImport\Api\Data\FieldsMappingInterface $fieldsMapping
    ) {
        try {
            $fieldsMapping->getResource()->delete($fieldsMapping);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the FieldsMapping: %1',
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
