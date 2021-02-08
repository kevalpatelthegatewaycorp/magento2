<?php

namespace GT\MAMCatalogue\Cron;

use Exception;

class GetAllPG
{
    const Default_Root_Category_ID = 2;

    protected $_logger;

    protected $_mamService;

    protected $_categoryFactory;

    protected $_category;

    protected $_repository;

    public function __construct(
        \GT\MAMCatalogue\Services\MamCatalogService $mamCatalogService,
        \Magento\Catalog\Model\CategoryFactory $categoryFactory,
        \Magento\Catalog\Model\Category $category,
        \Magento\Catalog\Api\CategoryRepositoryInterface $repository,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->_mamService = $mamCatalogService;
        $this->_categoryFactory = $categoryFactory;
        $this->_category = $category;
        $this->_repository = $repository;
        $this->_logger = $logger;
    }

    public function execute()
    {
        try {
            // get data from mam catalogue api.
            $arrResult = $this->_mamService->getAllPG();

            // create root category.
            $rootCategoryId = $this->handleRootCatagory('Mam Catalogue Category', 'MAM');

            // store category and it's subcategory in magento DB.
            foreach ($arrResult as $value) {
                $parentId = $this->handleProductGroupModel($value['pg'], $rootCategoryId);
                $this->handleSubProductGroupModel($value['spg'], $parentId);
            }
        } catch (Exception $exception) {
            $this->_logger->error('Error on line ' . $exception->getLine() . ' in ' . $exception->getFile() . '.Exception : ' . $exception->getMessage());
        }
    }

    private function handleRootCatagory($name, $code)
    {
        $collection = $this->_categoryFactory->create()->getCollection()->addFieldToFilter('mam_category_code', ['eq' => $code])->addFieldToFilter('parent_id', ['eq' => self::Default_Root_Category_ID]);

        if ($collection->getSize()) {
            return $collection->getFirstItem()->getId();
        } else {
            return $this->createCategory($name, self::Default_Root_Category_ID, true, $code);
        }
    }

    private function handleProductGroupModel($pgItem, $parentId)
    {
        $pgTypeArr = explode('^', $pgItem);
        $pgTypeCode = $pgTypeArr[0];
        $pgTypeName = $pgTypeArr[1];

        $collection = $this->_categoryFactory->create()->getCollection()->addFieldToFilter('mam_category_code', ['eq' => $pgTypeCode])->addFieldToFilter('parent_id', ['eq' => $parentId]);

        if ($collection->getSize()) {
            return $collection->getFirstItem()->getId();
        } else {
            return $this->createCategory($pgTypeName, $parentId, true, $pgTypeCode);
        }
    }

    private function handleSubProductGroupModel($spgItems, $parentId)
    {
        if (is_array($spgItems)) {
            foreach ($spgItems as $spgItem) {
                $this->createSubProductGroup($spgItem, $parentId);
            }
        } else {
            $this->createSubProductGroup($spgItems, $parentId);
        }
    }

    private function createSubProductGroup($spgItem, $parentId)
    {
        $spgTypeArr = explode('^', $spgItem);
        $spgTypeCode = $spgTypeArr[0];
        $spgTypeName = $spgTypeArr[1];

        $collection = $this->_categoryFactory->create()->getCollection()->addFieldToFilter('mam_category_code', ['eq' => $spgTypeCode])->addFieldToFilter('parent_id', ['eq' => $parentId]);

        if ($collection->getSize()) {
            return $collection->getFirstItem()->getId();
        } else {
            $this->createCategory($spgTypeName, $parentId, true, $spgTypeCode);
        }
    }

    private function createCategory($name = '', $parentId = 1, $isActive = true, $code = '')
    {
        $data = [
            'data' => [
                "parent_id" => $parentId,
                'name' => $name,
                "is_active" => $isActive,
                "position" => 10,
                "include_in_menu" => true,
                "mam_category_code" => $code
            ]
        ];

        $category = $this->_categoryFactory->create($data);
        $result = $this->_repository->save($category);

        return $result->getId();
    }
}
