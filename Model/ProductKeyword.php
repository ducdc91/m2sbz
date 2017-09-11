<?php

namespace Funk\SbzImport\Model;

class ProductKeyword extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'funk_sbz_import_product_keyword';

    protected function _construct()
    {
        $this->_init('Funk\SbzImport\Model\ResourceModel\ProductKeyword');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

}