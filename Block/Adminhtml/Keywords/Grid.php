<?php
namespace Funk\SbzImport\Block\Adminhtml\Keywords;

class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{

    /**
     * @var \Funk\SbzImport\Model\Keywords
     */
    protected $_keywords;

    /**
     * @var \Funk\SbzImport\Model\ResourceModel\Keywords\CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * Grid constructor.
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Helper\Data $backendHelper
     * @param \Funk\SbzImport\Model\ResourceModel\Keywords\CollectionFactory $collectionFactory
     * @param \Funk\SbzImport\Model\Keywords $keywords
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Funk\SbzImport\Model\ResourceModel\Keywords\CollectionFactory $collectionFactory,
        \Funk\SbzImport\Model\Keywords $keywords,
        array $data = []
    ) {
        $this->_collectionFactory = $collectionFactory;
        $this->_keywords = $keywords;
        parent::__construct($context, $backendHelper, $data);
    }

    /**
     * Define grid properties
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('sbzImportKeywordsGrid');
        $this->_exportPageSize = 10000;
    }


    protected function _prepareCollection()
    {
        /** @var $collection \Magento\OfflineShipping\Model\ResourceModel\Carrier\Tablerate\Collection */
        $collection = $this->_collectionFactory->create();

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Prepare table columns
     *
     * @return \Magento\Backend\Block\Widget\Grid\Extended
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'kwd_id',
            ['header' => __('kwd_id'), 'index' => 'kwd_id', 'default' => '*']
        );

        $this->addColumn(
            'keyword',
            ['header' => __('keyword'), 'index' => 'keyword', 'default' => '*']
        );

        $this->addColumn(
            'main_category',
            ['header' => __('main_category'), 'index' => 'main_category', 'default' => '*']
        );

        $this->addColumn(
            'sub_category',
            ['header' => __('sub_category'), 'index' => 'sub_category', 'default' => '*']
        );

        return parent::_prepareColumns();
    }
}
