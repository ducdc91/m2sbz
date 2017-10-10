<?php

namespace Funk\SbzImport\Cron\Import;


class GetDownloads
{
    /**
     * @var \Funk\SbzImport\Model\GetDownloads
     */
    protected $_getDownloads;

    /**
     * GetDownloads constructor.
     * @param \Funk\SbzImport\Model\GetDownloads $getDownloads
     */
    public function __construct(
        \Funk\SbzImport\Model\GetDownloads $getDownloads
    )
    {
        $this->_getDownloads = $getDownloads;

    }

    public function execute()
    {
        $this->_getDownloads->execute();
    }
}