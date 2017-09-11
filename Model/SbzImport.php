<?php
namespace Funk\SbzImport\Model;
use Magento\Framework\App\Filesystem\DirectoryList;

class SbzImport
{
  private $_request;
  protected $_logger;
  protected $_scopeConfig;
  protected $_categoryFactory;
  protected $_category;
  protected $_keyword_model;
  protected $_product_model;
  protected $_Db;

  public function __construct(
      \Funk\SbzImport\Model\SbzRequest $sbzRequest,
      \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
      \Magento\Catalog\Model\CategoryFactory $categoryFactory,
      \Funk\SbzImport\Model\Keywords $keywordModel,
      \Funk\SbzImport\Model\ProductKeyword $productModel,
      \Psr\Log\LoggerInterface $logger
  ) {
    $this->_request = $sbzRequest;
    $this->_scopeConfig = $scopeConfig;
    $this->_categoryFactory = $categoryFactory;
    $this->_Db = $this->connectionDB();
    $this->_keyword_model = $keywordModel;
    $this->_product_model = $productModel;
    $this->_logger = $logger;
  }

  private function connectionDB() {
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
    $connection = $resource->getConnection();
    return $connection;
  }

  public function getAndStoreProductSkuByKeyword(){
    $bResult = true;
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    //1- empty temporary data table
    $sql_empty_product_table = "TRUNCATE TABLE funk_sbz_import_product_keyword;";
    $this->_Db->query($sql_empty_product_table);
    $this->deleteAllImageAtImportFolder();
    //2- get sku  base on keywords
    //2.1- get all keywords from db
    $keywords = $this->_keyword_model->getCollection();
    $request = $this->_request;
    $productType = $this->listProductTypes();
    if($keywords->count()){

      foreach($keywords as $keyword){
        foreach($productType as $type){
          if ($type['value'] != 'download') {
            $data =  $request->clearResults()->getArticle('sw="'.$keyword->getKeyword().'"', $type['sbz_code'])->getResults();
            foreach ($data as $item) {
              $product_keyword = $objectManager->create('\Funk\SbzImport\Model\ProductKeyword');
              $product_keyword->setSku($item['Artikel']);
              $product_keyword->setKeyword($keyword->getKwdId());
              $product_keyword->save();

            }
          }
        }
      }
    }
    //2.2 get all sku from download type

    $data = $request->clearResults()->getArticle('', $productType['download']['sbz_code'])->getResults();
    foreach ($data as $item) {
      $product_keyword = $objectManager->create('\Funk\SbzImport\Model\ProductKeyword');
      $product_keyword->setSku($item['Artikel']);
      $product_keyword->setKeyword('download');
      $product_keyword->save();
    }
    return $bResult;
  }

  public function getAllProductFromCloudSystem(){
    $bResult = true;
    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    //1- empty temporary data table
    $sql_empty_temporary_table = "TRUNCATE TABLE funk_sbz_import_data_tpm;";
    $this->_Db->query($sql_empty_temporary_table);
    //2- get sku  base on keywords
    //2.1- get all keywords from db
    $products = $this->_product_model->getCollection();
    $request = $this->_request;

    $allow_download_type = $this->allowedDownloadTypes();

    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $ProductFactory = $objectManager->create('\Magento\Catalog\Model\ProductFactory')->create();

    $i=0;
    foreach($products as $product){
      if($i>5)
        break;
      $data =  $request->clearResults()->getArticleDetail($product->getSku())->getResults();
      $productModel  = $ProductFactory->loadByAttribute('sku',$product->getSku());
      $product_id = 0;
       if(is_object($productModel)){
         $product_id = $productModel->getEntityId();
       }

      $temp_data_model = $objectManager->create('\Funk\SbzImport\Model\DataTemporary');
      $content = $this->flatData($data);
      // download image to sbz_import folder

      $artikelart = $content['Artikelart'];
      if (isset($artikelart) && in_array($artikelart, $allow_download_type)) {
        $image_id = $content["Titelbild"];
        if(!empty($image_id)){
          $file_name = $this->downloadImageFromAPI($image_id);
        }
      }
      $content['images'] = $file_name ;
      $content = serialize($content);
      $temp_data_model->setDataContent($content);
      $temp_data_model->setSku ($product->getSku());
      $temp_data_model->setProductId($product_id);
      $temp_data_model->setCreateDate(date('Y-m-d H:i:s'));
      $temp_data_model->save();
      $i++;
    }
    return $bResult;

  }
  // implement get data from sbzonline.sbz.ch/ site via api and store data to local db
  function execute(){
    //$this->getAndStoreProductSkuByKeyword();
    $this->getAllProductFromCloudSystem();
  }

  function downloadImageFromAPI($image_id){
    $request = $this->_request;
    $result = $request->clearResults()->getAnnotation($image_id)->getResults();
    return $result ;
  }

  function deleteAllImageAtImportFolder(){

    $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
    $directory = $objectManager->get('Magento\Framework\Filesystem')->getDirectoryWrite(DirectoryList::MEDIA);

    $path = $directory->getAbsolutePath('sbz_image') . DIRECTORY_SEPARATOR . "*";
    $files = glob($path); // get all file names
    foreach($files as $file){ // iterate files
      if(is_file($file))
        unlink($file); // delete file
    }
    return true ;
  }

  public function listProductTypes()
  {
    return array(
      'in_stock' => array(
        'value' => 'in_stock',
        'sbz_code' => 1,
        'name' => 'In stock',
      ),
      'new' => array(
        'value' => 'new',
        'sbz_code' => 3,
        'name' => 'New',
      ),
      'download' => array(
        'value' => 'download',
        'sbz_code' => 4,
        'name' => 'Download',
      ),
    );
  }

  // flat data to array

  function flatData($datas){
    $new_data = array();
    foreach($datas as $data){
      $key = $data["Artikeldetailname"];
      $value = $data["Artikeldetail"];
      $new_data[$key] = $value ;

    }
    return $new_data;
  }

  function allowedDownloadTypes(){
    return array('Lager', 'Neu', 'Download');
  }
}

