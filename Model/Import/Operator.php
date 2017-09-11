<?php
/**
 * SupplierConnect - Magento Extension
 *
 * @package SupplierConnect
 * @category FunkExtensions
 * @copyright Copyright 2014 FunkExtensions.
 * @version: 0.1.0
 */
namespace Funk\SbzImport\Model\Import;
use \Magento\Framework\DataObject;

class Operator
  extends \Funk\SbzImport\Model\Import\Operator\ClassAbstract
{

    protected $_config;
    protected $_temporary_storage;

    public function __construct(\Funk\SbzImport\Model\Import\Operator\Config $config) {
      $this->_config = $config;
    }


    public function run($data) {
      $result  = new DataObject();
      try {
        // Prepare before import
        $this->_prepare($result);
        // Import data
        $this->_process($result,$data);
      } catch (Exception $e) {
        $this->logException($e);
        throw $e;
      }
      return $result;
    }
    protected function logException(Exception $e)
    {
      /** @var $session Mage_Adminhtml_Model_Session */
      //$session = Mage::getSingleton('adminhtml/session');
      // $session->addError($e->getMessage());
      return $this;
    }
    protected function _validate() {
      if (!$this->_config->getAdapter() instanceof \Funk\SbzImport\Model\Import\Adapter\ImportInterface) {
        $this->_throwException('Wrong adapter model.');
      }
    }
    protected function _prepare(DataObject &$result) {

      $adapter    = $this->_config->getAdapter();
      $adapter->beforePrepare();
      // Initialize after prepare complete
      $adapter->afterPrepare();


      return $result;
    }

    protected function _process(DataObject &$result,$data) {

      $adapter     = $this->_config->getAdapter();

      // Initialize before import begin
      $adapter->setImportData($data);
      $adapter->beforeProcess();
      try {
        // Import item data
        if ($adapter->processData($data)) {

        }
      } catch (FunkExtensions_SupplierConnect_Exception $e) {

      }
      // Initialize after import complete
      $adapter->afterProcess();
      /** @var $session Mage_Adminhtml_Model_Session */

      return $result;
    }
  public function logRecordException( Exception $e)
  {
    return $this;
  }


}
