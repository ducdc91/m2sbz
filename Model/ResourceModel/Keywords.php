<?php


namespace Funk\SbzImport\Model\ResourceModel;

use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\DirectoryList;

class Keywords extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    protected $csvProcessor;
    /**
     * Filesystem instance
     *
     * @var \Magento\Framework\Filesystem
     */
    protected $filesystem;

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\File\Csv $csvProcessor,
        $connectionName = null
    )
    {
        parent::__construct($context, $connectionName);
        $this->logger = $logger;
        $this->storeManager = $storeManager;
        $this->filesystem = $filesystem;
        $this->csvProcessor = $csvProcessor;
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('funk_sbz_import_keywords', 'entity_id');
    }

    public function uploadAndImport(\Magento\Framework\DataObject $object)
    {
        /**
         * @var \Magento\Framework\App\Config\Value $object
         */
        if (empty($_FILES['groups']['tmp_name']['filterdata']['fields']['import']['value'])) {
            return $this;
        }
        $filePath = $_FILES['groups']['tmp_name']['filterdata']['fields']['import']['value'];

        try {
            $this->deleteAllData();
            $data = $this->csvProcessor->getData($filePath);
            $this->getConnection()->insertArray(
                $this->getMainTable(),
                array('kwd_id','keyword','main_category','sub_category'),    //column names
                $data
            );
        } catch (\Exception $e) {
            $this->logger->critical($e);
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while importing keywords.')
            );
        }
    }

    /**
     * @param array $condition
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function deleteAllData()
    {
        $connection = $this->getConnection();
        $connection->beginTransaction();
        $connection->delete($this->getMainTable());
        $connection->commit();
        return $this;
    }
}
