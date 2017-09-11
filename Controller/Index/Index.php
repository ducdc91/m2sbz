<?php
namespace Funk\SbzImport\Controller\Index;

use Funk\SbzImport\Model\SbzImport;
class Index extends \Magento\Framework\App\Action\Action {

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
        SbzImport $sbzImport
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->sbzImport = $sbzImport;

    }

    /**
     * Execute view action
     * 
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
      $this->sbzImport->execute();
      return $this->resultPageFactory->create();
    }
}