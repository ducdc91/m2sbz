<?php
namespace Funk\SbzImport\Model;

class FieldsMapping extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
  const CACHE_TAG       = 'funk_sbz_import_fields_mapping';

  protected function _construct()
  {
    $this->_init('Funk\SbzImport\Model\ResourceModel\FieldsMapping');
  }

  public function getIdentities()
  {
    return [self::CACHE_TAG . '_' . $this->getId()];
  }

}