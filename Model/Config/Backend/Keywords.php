<?php
namespace Funk\SbzImport\Model\Config\Backend;

use Magento\Framework\Model\AbstractModel;

class Keywords extends \Magento\Framework\App\Config\Value
{
    /**
     * @var \Funk\SbzImport\Model\ResourceModel\KeywordsFactory
     */
    protected $_keywordsFactory;

    /**
     * Keywords constructor.
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $config
     * @param \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList
     * @param \Funk\SbzImport\Model\ResourceModel\KeywordsFactory $keywordsFactory
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource|null $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb|null $resourceCollection
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\App\Config\ScopeConfigInterface $config,
        \Magento\Framework\App\Cache\TypeListInterface $cacheTypeList,
        \Funk\SbzImport\Model\ResourceModel\KeywordsFactory $keywordsFactory,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->_keywordsFactory = $keywordsFactory;
        parent::__construct($context, $registry, $config, $cacheTypeList, $resource, $resourceCollection, $data);
    }

    /**
     * @return $this
     */
    public function afterSave()
    {
        /** @var \Funk\SbzImport\Model\ResourceModel\Keywords $keywords */
        $keywords = $this->_keywordsFactory->create();
        $keywords->uploadAndImport($this);
        return parent::afterSave();
    }
}
