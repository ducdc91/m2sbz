<?php
namespace Funk\SbzImport\Model;

class DataTemporary extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
  const CACHE_TAG       = 'funk_sbz_import_data_tpm';

  protected function _construct()
  {
    $this->_init('Funk\SbzImport\Model\ResourceModel\DataTemporary');
  }

  public function getIdentities()
  {
    return [self::CACHE_TAG . '_' . $this->getId()];
  }

}