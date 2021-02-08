<?php

namespace GT\MAMCatalogue\Controller\Adminhtml\Index;

use GT\MAMCatalogue\Services\MamCatalogService;
use \Magento\Backend\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;

class Index extends \Magento\Backend\App\Action
{
    protected $mamService;

    protected $resultPageFactory = false;

    public function __construct(
        Context $context,
        MamCatalogService $mamCatalogService,
        PageFactory $resultPageFactory
    ) {
        parent::__construct($context);
        $this->mamService = $mamCatalogService;
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        // $res = $this->mamService->getMakes();
        // $res = $this->mamService->getModels('BMW');
        // $res = $this->mamService->getSubModels('BMW', '1 Series');
        // $res = $this->mamService->getEngineSizes('BMW', '1 Series', 116);
        // $res = $this->mamService->getYears('BMW', '1 Series', 116, 2.000);
        // $res = $this->mamService->getPartsData('Nissan', 'Qashqai', '', 1.6000, 'EXHT-EX');
        // $res = $this->mamService->getSupplierInfo();
        // $res = $this->mamService->getAllPG();
        // var_dump($res);
        // exit();
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('GT_MAMCatalogue::menu');
        $resultPage->getConfig()->getTitle()->prepend(__('MAMCatalogue Menu'));
        return $resultPage;
    }

    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('GT_MAMCatalogue::menu');
    }
}
