<?php

/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace GT\StoreLocator\Model;

use Magento\InventoryApi\Api\SourceRepositoryInterface;
use Magento\InventoryInStorePickupApi\Api\Data\SearchRequestInterface;
use Magento\InventoryInStorePickupApi\Api\Data\SearchResultInterface;
use Magento\InventoryInStorePickupApi\Api\Data\SearchResultInterfaceFactory;
use Magento\InventoryInStorePickupApi\Api\GetPickupLocationsInterface;
use Magento\InventoryInStorePickupApi\Model\Mapper;
use Magento\InventoryInStorePickupApi\Model\SearchCriteriaResolverInterface;
use Magento\InventoryInStorePickupApi\Model\SearchResult\ExtractorInterface;

/**
 * @inheritdoc
 */
class GetPickupLocations implements GetPickupLocationsInterface
{
    /**
     * @var Mapper
     */
    private $mapper;

    /**
     * @var SourceRepositoryInterface
     */
    private $sourceRepository;

    /**
     * @var SearchCriteriaResolverInterface
     */
    private $searchCriteriaResolver;

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @var SearchResultInterfaceFactory
     */
    private $searchResultFactory;

    /**
     * @param Mapper $mapper
     * @param SourceRepositoryInterface $sourceRepository
     * @param ExtractorInterface $extractor
     * @param SearchCriteriaResolverInterface $searchCriteriaResolver
     * @param SearchResultInterfaceFactory $searchResultFactory
     */
    public function __construct(
        Mapper $mapper,
        SourceRepositoryInterface $sourceRepository,
        ExtractorInterface $extractor,
        SearchCriteriaResolverInterface $searchCriteriaResolver,
        SearchResultInterfaceFactory $searchResultFactory
    ) {
        $this->mapper = $mapper;
        $this->sourceRepository = $sourceRepository;
        $this->searchCriteriaResolver = $searchCriteriaResolver;
        $this->extractor = $extractor;
        $this->searchResultFactory = $searchResultFactory;
    }

    /**
     * @inheritdoc
     */
    public function execute(SearchRequestInterface $searchRequest): SearchResultInterface
    {
        $searchCriteria = $this->searchCriteriaResolver->resolve($searchRequest);
        $searchResult = $this->sourceRepository->getList($searchCriteria);

        $sources = $this->extractor->getSources($searchRequest, $searchResult);

        $pickupLocations = [];

        foreach ($sources as $source) {
            $data = $this->mapper->map($source);
            (isset($source->distance)) ? $data->getExtensionAttributes()->setDistance($source->distance) : $data->getExtensionAttributes()->setDistance('');
            $pickupLocations[] = $data;
        }

        return $this->searchResultFactory->create(
            [
                'items' => $pickupLocations,
                'totalCount' => $searchResult->getTotalCount(),
                'searchRequest' => $searchRequest
            ]
        );
    }
}




// /**
//  * Copyright © Magento, Inc. All rights reserved.
//  * See COPYING.txt for license details.
//  */

// declare(strict_types=1);

// namespace GT\StoreLocator\Model;

// use Magento\InventoryApi\Api\SourceRepositoryInterface;
// use Magento\InventoryInStorePickupApi\Api\Data\SearchRequestInterface;
// use Magento\InventoryInStorePickupApi\Api\Data\SearchResultInterface;
// use Magento\InventoryInStorePickupApi\Api\Data\SearchResultInterfaceFactory;
// use Magento\InventoryInStorePickupApi\Api\GetPickupLocationsInterface;
// use Magento\InventoryInStorePickupApi\Model\Mapper;
// use Magento\InventoryInStorePickupApi\Model\SearchCriteriaResolverInterface;
// use Magento\InventoryInStorePickupApi\Model\SearchResult\ExtractorInterface;

// use \Magento\Customer\Model\SessionFactory;
// use \Magento\Customer\Model\AddressFactory;
// use \GT\StoreLocator\Model\PostcodeFactory;
// use \Magento\Framework\Api\SearchCriteriaBuilder;

// /**
//  * @inheritdoc
//  */
// class GetPickupLocations implements GetPickupLocationsInterface
// {
//     /**
//      * @var Mapper
//      */
//     private $mapper;

//     /**
//      * @var SourceRepositoryInterface
//      */
//     private $sourceRepository;

//     /**
//      * @var SearchCriteriaResolverInterface
//      */
//     private $searchCriteriaResolver;

//     /**
//      * @var ExtractorInterface
//      */
//     private $extractor;

//     /**
//      * @var SearchResultInterfaceFactory
//      */
//     private $searchResultFactory;

//     /**
//      * @var SessionFactory
//      */
//     protected $customerSessionFactory;

//     /**
//      * @var AddressFactory
//      */
//     protected $customerAddressFactory;

//     /**
//      * @var PostcodeFactory
//      */
//     protected $postcodeFactory;

//     /**
//      * @var PostcodeFactory
//      */
//     protected $searchCriteriaBuilder;

//     /**
//      * @param Mapper $mapper
//      * @param SourceRepositoryInterface $sourceRepository
//      * @param ExtractorInterface $extractor
//      * @param SearchCriteriaResolverInterface $searchCriteriaResolver
//      * @param SearchResultInterfaceFactory $searchResultFactory
//      */
//     public function __construct(
//         Mapper $mapper,
//         SourceRepositoryInterface $sourceRepository,
//         ExtractorInterface $extractor,
//         SearchCriteriaResolverInterface $searchCriteriaResolver,
//         SearchResultInterfaceFactory $searchResultFactory,
//         SessionFactory $customerSessionFactory,
//         AddressFactory $customerAddressFactory,
//         PostcodeFactory $postcodeFactory,
//         SearchCriteriaBuilder $searchCriteriaBuilder
//     ) {
//         $this->mapper = $mapper;
//         $this->sourceRepository = $sourceRepository;
//         $this->searchCriteriaResolver = $searchCriteriaResolver;
//         $this->extractor = $extractor;
//         $this->searchResultFactory = $searchResultFactory;
//         $this->customerSessionFactory = $customerSessionFactory;
//         $this->customerAddressFactory = $customerAddressFactory;
//         $this->postcodeFactory = $postcodeFactory;
//         $this->searchCriteriaBuilder = $searchCriteriaBuilder;
//     }

//     /**
//      * @inheritdoc
//      */
//     public function execute(SearchRequestInterface $searchRequest): SearchResultInterface
//     {
//         // Get ID of default billing address
//         $customerSessionFactory =  $this->customerSessionFactory->create();
//         $billingID = $customerSessionFactory->getCustomer()->getDefaultBilling();

//         // Get current logged in user billing address
//         $customerAddressFactory = $this->customerAddressFactory->create();
//         $customerAddressObj = $customerAddressFactory->load($billingID);

//         // Get lat-long from custom table
//         $postcodeFactory = $this->postcodeFactory->create();
//         $customer = $postcodeFactory->load($customerAddressObj->getPostcode(), 'postcode');
//         $postcodeDetails = $customer->getData();

//         $searchCriteria = $this->searchCriteriaResolver->resolve($searchRequest);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$()', $searchCriteria->getFilterGroups()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$()', $searchCriteria->getFilterGroups()[0]->getFilters()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$()', $searchCriteria->getFilterGroups()[0]->getFilters()[0]->getField()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$()', $searchCriteria->getFilterGroups()[0]->getFilters()[0]->getValue()]);

//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[1]->getFilters()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[1]->getFilters()[0]->getField()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[1]->getFilters()[0]->getValue()]);

//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[2]->getFilters()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[2]->getFilters()[0]->getField()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[2]->getFilters()[0]->getValue()]);

//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[3]->getFilters()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[3]->getFilters()[0]->getField()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[4]->getFilters()[0]->getValue()]);

//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[4]->getFilters()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[4]->getFilters()[0]->getField()]);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$(1)', $searchCriteria->getFilterGroups()[4]->getFilters()[0]->getValue()]);

//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => 'hello1', $searchRequest->getFilters()]);
//         // $termData = ($searchRequest->getArea() != null) ? $searchRequest->getArea()->getSearchTerm() : '';
//         // $searchCriteria = $this->searchCriteriaBuilder->addFilter('postcode', '%%', 'like')->create();
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => 'hello2', $searchRequest->getArea()->getSearchTerm()]);

//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$searchCriteria', $searchCriteria]);

//         // $objectManager = \Magento\Framework\App\ObjectManager::getInstance(); 
//         // $filter = $objectManager->create('Magento\Framework\Api\Filter');
//         // $filter1 = $filter->setField("postcode")->setValue("%A%")->setConditionType("like");
//         // $filter2 = $filter->setField("enabled")->setValue("1")->setConditionType("eq");

//         // $filterGroup = $objectManager->create('Magento\Framework\Api\Search\FilterGroup');
//         // $filterGroup1 = $filterGroup->setFilters([$filter1, $filter2]);

//         // $searchCriteria->setFilterGroups([$filterGroup1]);

//         $searchResult = $this->sourceRepository->getList($searchCriteria);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$searchResult->getItems()', $searchResult->getItems()]);
//         $sources = $this->extractor->getSources($searchRequest, $searchResult);
//         // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$sources', $sources]);
//         $pickupLocations = [];
//         \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => '$doneeeeeeeeeeeeeeee']);
//         distance
//         foreach ($sources as $source) {
//             $distance = $this->getDistance($postcodeDetails['latitude'], $postcodeDetails['longitude'], $source->getLatitude(), $source->getLongitude());

//             $data = $this->mapper->map($source);
//             $data->getExtensionAttributes()->setDistance($distance);
//             $pickupLocations[] = $data;
//             // \Magento\Framework\App\ObjectManager::getInstance()->get('Psr\Log\LoggerInterface')->log(200, '', [1 => 'hello3', $data->getExtensionAttributes()->getDistance()]);
//         }

//         return $this->searchResultFactory->create(
//             [
//                 // 'items' => $this->sortLocations($pickupLocations),
//                 'items' => $pickupLocations,
//                 'totalCount' => $searchResult->getTotalCount(),
//                 'searchRequest' => $searchRequest
//             ]
//         );
//     }

//     public function getDistance($lat1, $lon1, $lat2, $lon2, $unit = 'K')
//     {
//         $theta = $lon1 - $lon2;
//         $dist = sin(deg2rad((float)$lat1)) * sin(deg2rad((float)$lat2)) +  cos(deg2rad((float)$lat1)) * cos(deg2rad((float)$lat2)) * cos(deg2rad($theta));
//         $dist = acos($dist);
//         $dist = rad2deg($dist);
//         $miles = $dist * 60 * 1.1515;
//         $unit = strtoupper($unit);

//         // 'M' is miles
//         // 'K' is kilometers
//         // 'N' is nautical miles
//         if ($unit == "K") {
//             return number_format((float)($miles * 1.609344), 2, '.', '');
//         } else if ($unit == "N") {
//             return number_format((float)($miles * 0.8684), 2, '.', '');
//         } else {
//             return number_format((float)($miles), 2, '.', '');
//         }
//     }

//     function sortLocations($array, $reverse = false)
//     {
//         $sorted = [];

//         foreach ($array as $item) {
//             $sorted[$item->getExtensionAttributes()->getDistance()][] = $item;
//         }

//         if ($reverse) krsort($sorted);
//         else ksort($sorted);
//         $result = [];

//         foreach ($sorted as $subArray) foreach ($subArray as $item) {
//             $result[] = $item;
//         }

//         return $result;
//     }
// }














// /**
//  * Copyright © Magento, Inc. All rights reserved.
//  * See COPYING.txt for license details.
//  */
// declare(strict_types=1);

// namespace Magento\InventoryInStorePickup\Model\SearchResult\Strategy;

// class DistanceBased implements StrategyInterface
// {

//     /**
//      * Sort Sources by Distance.
//      *
//      * @param array $sources
//      * @param array $distanceToSources
//      * @param string $sortDirection
//      *
//      * @return array
//      */
//     private function sortSourcesByDistance(
//         array $sources,
//         array $distanceToSources,
//         string $sortDirection = SortOrder::SORT_ASC
//     ): array {
//         foreach($sources as $source) {
//             $source->distance = $distanceToSources[$source->getSourceCode()];
//         }
//         $ascSort = function (SourceInterface $left, SourceInterface $right) use ($distanceToSources) {
//             return $distanceToSources[$left->getSourceCode()] <=> $distanceToSources[$right->getSourceCode()];
//         };

//         $descSort = function (SourceInterface $left, SourceInterface $right) use ($distanceToSources) {
//             return $distanceToSources[$right->getSourceCode()] <=> $distanceToSources[$left->getSourceCode()];
//         };

//         $sort = $sortDirection === SortOrder::SORT_ASC ? $ascSort : $descSort;

//         usort($sources, $sort);

//         return $sources;
//     }
