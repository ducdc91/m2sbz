<?php
/**
 * SupplierConnect - Magento Extension
 *
 * @package SupplierConnect
 * @category FunkExtensions
 * @copyright Copyright 2014 FunkExtensions.
 * @version: 0.1.0
 */
namespace Funk\SbzImport\Model\Import\Adapter\Type\Product\Data;

use Magento\Framework\App\Filesystem\DirectoryList;

class Media
  extends \Funk\SbzImport\Model\Import\Adapter\Type\Product\Data\ClassAbstract
{
    protected $_gallery_table;
    protected $_gallery_value_table;
    protected $_media_attribute_collection;
    protected $_gallery_attribute;
    // static varible
    static $GALLERY_TABLE ;
    static $GALLERY_VALUE_TABLE ;
    static $MEDIA_ATTRIBUTE_COLLECTION ;
    static $GALLERY_PRODUCT_TABLE ;
    static $PRODUCT_ENTITY_VARCHAR_TABLE ;
    static $EAV_TABLE ;
    static $CATEGORY_EAV_TABLE ;
    static $GALLERY_ATTRIBUTE_ID ;
    static $IMAGE_TYPES ;


    protected function _construct() {
        if(!isset(self::$GALLERY_TABLE)){

            self::$GALLERY_TABLE            = $this->_resource->getTableName('catalog_product_entity_media_gallery');
            self::$GALLERY_VALUE_TABLE      = $this->_resource->getTableName('catalog_product_entity_media_gallery_value');
            self::$GALLERY_PRODUCT_TABLE    = $this->_resource->getTableName('catalog_product_entity_media_gallery_value_to_entity');
            self::$PRODUCT_ENTITY_VARCHAR_TABLE    = $this->_resource->getTableName('catalog_product_entity_varchar');

            self::$EAV_TABLE                 = $this->_resource->getTableName('eav_attribute');
            self::$CATEGORY_EAV_TABLE        = $this->_resource->getTableName('catalog_eav_attribute');
            if(!self::$GALLERY_ATTRIBUTE_ID){
                $attribute_id  = self::getGalleryAttributeId();
                self::$GALLERY_ATTRIBUTE_ID = $attribute_id;
            }
            if(!self::$IMAGE_TYPES){
                $attribute_ids  = self::getImageTypes();
                self::$IMAGE_TYPES = $attribute_ids;
            }
        }

        $this->_gallery_table 				= self::$GALLERY_TABLE ;
        $this->_gallery_value_table 			= self::$GALLERY_VALUE_TABLE ;
        $this->_media_attribute_collection 	= array();//self::$MEDIAATTRIBUTECOLLECTION ;
        $this->_gallery_attribute 			= self::$GALLERY_PRODUCT_TABLE ;
    }

    static  function getGalleryAttributeId() {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
      $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
      $connection = $resource->getConnection();
      $eav_table    = self::$EAV_TABLE ;
      $category_attribute_table = self::$CATEGORY_EAV_TABLE;
      $sql = "
              SELECT
                main_table.attribute_id
              FROM
              {$category_attribute_table} as main_table
              INNER JOIN {$eav_table} as eav_table ON main_table.attribute_id = eav_table.attribute_id
              WHERE eav_table.attribute_code='media_gallery'
              ";
      $result = $connection->fetchOne($sql);
      return $result;
    }

    static  function getImageTypes() {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
      $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
      $connection = $resource->getConnection();
      $eav_table    = self::$EAV_TABLE ;
      $sql = "
                SELECT
                  attribute_id
                FROM
                {$eav_table} 
                
                WHERE frontend_input='media_image'
                ";
      $result = $connection->fetchAll($sql);
      return $result;
    }

    public function prepareData(array &$data) {
        if ($this->_config->getCanDownloadMedia()) {

        }
    }

    public function processData(array &$data) {
        $galleryImages = array();
        if (isset($data['images'])) {
            $images  = explode(',', $data['images']);
            $galleryImages = $images;
        }
        array_walk($galleryImages, array($this, '_trimValue'));
        foreach ($galleryImages as $key => $value) {
            if (empty($value)) {
                unset($galleryImages[$key]);
            }
        }
        $galleryImages = array_unique($galleryImages);

        $excludeImages = isset($data['exclude_images']) ? explode(',', $data['exclude_images']) : array();
        array_walk($excludeImages, array($this, '_trimValue'));
        if(!empty($galleryImages)){
            foreach ($galleryImages as $key => $imageFile) {
                $filePath = $imageFile;
                if ($imageFile) {
                    // download image from url and save to media/catalog folder
                    $fileName = $this->moveImageFromTempFolderToMedia($filePath);
                   // save image path to db
                    $image_id =  $this->saveImageToDB($fileName) ;
                    $product_id =  $data['product_id'];
                    $label      = $data["name"];
                    // save image and mapping to product
                    $this->saveImageValueToDB($image_id,$product_id,$fileName,$label);

                }
            }
        }
        return $this;
    }

    private function saveImageToDB($fileName){
      $attribute_id = self::$GALLERY_ATTRIBUTE_ID ;
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); // Instance of object manager
      $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
      $connection = $resource->getConnection();
      $gallery_table    = self::$GALLERY_TABLE ;

      $sql = "
              SELECT
                value_id
              FROM
              {$gallery_table}
              WHERE value='{$fileName}' and attribute_id={$attribute_id}
              ";
      $result   = (int)  $connection->fetchOne($sql);
      if($result){
        $image_id = $result;
      }else{
          $value = array(
            'attribute_id'  => $attribute_id ,
            'value'     =>  $fileName,
            'media_type'         => 'image',
            'disabled'         => 0,
          );
          $this->_writeConnection->insert($gallery_table, $value);
          $image_id =  $this->_writeConnection->lastInsertId() ;
      }
      return $image_id ;
    }
    protected function saveImageValueToDB($image_id,$product_id,$fileName, $label){

      $store_id = $this->_store->getId();
      // insert to gallery  value table
      $gallery_value_table = self::$GALLERY_VALUE_TABLE ;
      $sql = "SELECT value_id 
                  FROM {$gallery_value_table} 
                    WHERE value_id = {$image_id} AND store_id = {$store_id} AND entity_id = {$product_id}";
       if(!$this->_writeConnection->fetchOne($sql)) {
             $image_values = array(
               'value_id' => $image_id,
               'store_id' => $store_id,
               'label' =>    $label,
               'entity_id' => $product_id,
               'position' => 1,
               'disabled' => 0,
             );
             $this->_writeConnection->insert($gallery_value_table, $image_values);
       }
      // insert to gallery entiry value table
      $gallery_entity_value_table = self::$GALLERY_PRODUCT_TABLE ;
      $sql = "SELECT value_id  FROM {$gallery_entity_value_table} WHERE value_id={$image_id} and entity_id={$product_id}";
      if(!$this->_writeConnection->fetchOne($sql)) {
          $image_values = array(
            'value_id' => $image_id,
            'entity_id' => $product_id
          );
          $this->_writeConnection->insert($gallery_entity_value_table, $image_values);
      }
      // assign image type to product
      $this->insertImageProduct($product_id,$fileName,$store_id);
    }

    function insertImageProduct($product_id,$file_name,$store_id){
      // insert to product varchar table
      $attribute_ids = self::$IMAGE_TYPES;
      $product_entity_varchar_table = self::$PRODUCT_ENTITY_VARCHAR_TABLE;

      foreach($attribute_ids as $id){
        $attribute_id = $id["attribute_id"];
        $sql = "SELECT value_id 
                  FROM {$product_entity_varchar_table} 
                    WHERE attribute_id = {$attribute_id} AND store_id = {$store_id} AND entity_id = {$product_id}";

        if(!$this->_writeConnection->fetchOne($sql)){
          $image_values = array(
            'attribute_id' =>  $attribute_id,
            'store_id' =>  $store_id,
            'entity_id' => $product_id,
            'value' => $file_name
          );
          $this->_writeConnection->insert($product_entity_varchar_table , $image_values);
        }
      }

    }
    protected function _isHttpImage($imageFile)
    {
        return !(strpos($imageFile, 'http:') === false);
    }


    public function moveImageFromTempFolderToMedia($file) {

        $result = $this->moveFileToMediaImage($file);
        $file = $result['file'];

        return $file;
    }
    protected function _trimValue(&$value)
    {
        $value = trim(trim($value), '/');

        return $value;
    }

    function moveFileToMediaImage($filePath)
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $uploader = $objectManager->create(
          'Magento\CatalogImportExport\Model\Import\Uploader'
        );
        $uploader->init();
        $dirConfig = DirectoryList::getDefaultConfig();
        $dirAddon = $dirConfig[DirectoryList::MEDIA][DirectoryList::PATH];
        $DS = DIRECTORY_SEPARATOR;
        $directory = $objectManager->get('Magento\Framework\Filesystem')->getDirectoryWrite(DirectoryList::ROOT);
        $tmpPath = $dirAddon . $DS . $directory->getRelativePath('sbz_image');

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
        return $uploader->move($filePath,true);
    }
}