<?php
namespace Funk\SbzImport\Controller\Index;

use Funk\SbzImport\Model\SbzImport;
use Funk\SbzImport\Model\Import\Product;
use Magento\Framework\App\Filesystem\DirectoryList;

class Test extends \Magento\Framework\App\Action\Action {

  protected $resultPageFactory;
  protected $sbzImport;

  /**
   * Index constructor.
   *
   * @param \Magento\Framework\App\Action\Context $context
   * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
   * @param \Funk\SbzImport\Model\SbzImport $sbzImport
   */
  public function __construct(
    \Magento\Framework\App\Action\Context $context,
    \Magento\Framework\View\Result\PageFactory $resultPageFactory,
    SbzImport $sbzImport,
    \Magento\DownloadableImportExport\Helper\Uploader $uploaderHelper,
    Product $productImport
  )
  {
    parent::__construct($context);
    $this->resultPageFactory = $resultPageFactory;
    $this->sbzImport = $sbzImport;
    $this->productImport = $productImport;
    $this->uploaderHelper = $uploaderHelper;

  }

  /**
   * Execute view action
   *
   * @return \Magento\Framework\Controller\ResultInterface
   */
  public function execute()
  {
	  $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $dataCollection = $objectManager->create('\Funk\SbzImport\Model\ResourceModel\DataTemporary\Collection');

    foreach($dataCollection as $item){
      $data = unserialize($item->getDataContent());

      $data["product_id"] = $item->getProductId();
      $this->productImport->import($data);
    }
    exit;




    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();


    $uploader = $objectManager->create(
      'Magento\CatalogImportExport\Model\Import\Uploader'
    );
    $uploader->init();
    $dirConfig = DirectoryList::getDefaultConfig();
    $dirAddon = $dirConfig[DirectoryList::MEDIA][DirectoryList::PATH];
    $DS = DIRECTORY_SEPARATOR;

    $directory = $objectManager->get('Magento\Framework\Filesystem')->getDirectoryWrite(DirectoryList::ROOT);
    $tmpPath = $dirAddon . $DS . $directory->getRelativePath('import');


    if (!$uploader->setTmpDir($tmpPath)) {
      throw new \Magento\Framework\Exception\LocalizedException(
        __('File directory \'%1\' is not readable.', $tmpPath)
      );
    }

    $destinationDir = "catalog/product";
    $destinationPath = $dirAddon . $DS . $directory->getRelativePath($destinationDir);

    $directory->create($destinationPath);

    if (!$uploader->setDestDir($destinationPath)) {
      throw new \Magento\Framework\Exception\LocalizedException(
        __('File directory \'%1\' is not writable.', $destinationPath)
      );
    }


    $type = 'links';
    $fileName = "aaa.jpg";
    $res = $uploader->move($fileName,true);
    print_r($res);exit;


    return $this->resultPageFactory->create();
  }
}