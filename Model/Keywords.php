<?php

namespace Funk\SbzImport\Model;

class Keywords extends \Magento\Framework\Model\AbstractModel implements \Magento\Framework\DataObject\IdentityInterface
{
    const CACHE_TAG = 'sbz_import_articles';

    protected function _construct()
    {
        $this->_init('Funk\SbzImport\Model\ResourceModel\Keywords');
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

}