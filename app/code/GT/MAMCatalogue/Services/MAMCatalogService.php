<?php

namespace GT\MAMCatalogue\Services;

use \GT\Api\Service\ApiService;
use \Magento\Framework\App\DeploymentConfig;

class MAMCatalogService
{

    /**
     * @var \GT\Api\Service\ApiService
     */
    protected $apiService;

    /**
     * @var array
     */
    protected $catalogue = [];

    /**
     * @param \GT\Api\Service\ApiService $apiService
     * @param \Magento\Framework\App\DeploymentConfig $deploymentConfig
     */
    public function __construct(
        ApiService $apiService,
        DeploymentConfig $deploymentConfig
    ) {
        $this->apiService = $apiService;

        $this->catalogue['wsdl'] = $deploymentConfig->get('mam/catalogue/wsdl');
        $this->catalogue['username'] = $deploymentConfig->get('mam/catalogue/userName');
        $this->catalogue['password'] = $deploymentConfig->get('mam/catalogue/password');
        $this->catalogue['mamCode'] = $deploymentConfig->get('mam/catalogue/mamCode');
    }

    /**
     * return manufacturers list.
     *
     * @return array
     */
    public function getMakes()
    {
        $requestArray = [
            'WebGetMfg' => [
                'UserName' => $this->catalogue['username'],
                'PassWord' => $this->catalogue['password']
            ]
        ];

        $makes = $this->apiService->processRequest($this->catalogue['wsdl'], 'WebGetMfg', $requestArray, true);

        $makesArr = [];
        foreach ($makes['normalized']['1']['NewDataSet']['AC'] as $make) {
            array_push($makesArr, $make['manuf']);
        }

        return $makesArr;
    }

    /**
     * return models list.
     *
     * @param string $make
     * @return array
     */
    public function getModels($make = '')
    {
        $requestArray = [
            'WebGetModel' => [
                'UserName' => $this->catalogue['username'],
                'PassWord' => $this->catalogue['password'],
                'Manuf' => $make
            ]
        ];

        $models = $this->apiService->processRequest($this->catalogue['wsdl'], 'WebGetModel', $requestArray, true);

        $modelsArr = [];
        foreach ($models['normalized']['1']['NewDataSet']['AC'] as $model) {
            array_push($modelsArr, $model['model']);
        }

        return $modelsArr;
    }

    /**
     * return submodels list.
     *
     * @param string $make
     * @param string $model
     * @return array
     */
    public function getSubModels($make = '', $model = '')
    {
        $requestArray = [
            'WebGetSubModel' => [
                'UserName' => $this->catalogue['username'],
                'PassWord' => $this->catalogue['password'],
                'Manuf' => $make,
                'Model' => $model
            ]
        ];

        $submodels = $this->apiService->processRequest($this->catalogue['wsdl'], 'WebGetSubModel', $requestArray, true);

        $submodelsArr = [];
        if (isset($submodels)) {
            foreach ($submodels['normalized']['1']['NewDataSet']['AC'] as $submodel) {
                array_push($submodelsArr, $submodel['submodel']);
            }
        }

        return $submodelsArr;
    }

    /**
     * return enginesizes list.
     *
     * @param string $make
     * @param string $model
     * @param string $submodel
     * @return array
     */
    public function getEngineSizes($make = '', $model = '', $submodel = '')
    {
        $requestArray = [
            'WebGetEng' => [
                'UserName' => $this->catalogue['username'],
                'PassWord' => $this->catalogue['password'],
                'Manuf' => $make,
                'Model' => $model,
                'SubModel' => $submodel
            ]
        ];

        $enginesizes = $this->apiService->processRequest($this->catalogue['wsdl'], 'WebGetEng', $requestArray, true);

        $enginesizesArr = [];
        foreach ($enginesizes['normalized']['1']['NewDataSet']['AC'] as $enginesize) {
            array_push($enginesizesArr, $enginesize);
        }

        return $enginesizesArr;
    }

    /**
     * return years list.
     *
     * @param string $make
     * @param string $model
     * @param string $submodel
     * @param string $enginesize
     * @return array
     */
    public function getYears($make = '', $model = '', $submodel = '', $enginesize = '')
    {
        $requestArray = [
            'GetYears' => [
                'UserName' => $this->catalogue['username'],
                'PassWord' => $this->catalogue['password'],
                'mfg' => $make,
                'model' => $model,
                'smod' => $submodel,
                'engs' => $enginesize
            ]
        ];

        $years = $this->apiService->processRequest($this->catalogue['wsdl'], 'GetYears', $requestArray, true);

        $yearsArr = [];
        $yearsArr['styr'] = $years['normalized']['1']['NewDataSet']['AC']['a'];
        $yearsArr['endyr'] = $years['normalized']['1']['NewDataSet']['AC1']['b'];

        return $yearsArr;
    }

    /**
     * return parts data list.
     *
     * @param string $make
     * @param string $model
     * @param string $submodel
     * @param string $enginesize
     * @param string $subpg
     * @return array
     */
    public function getPartsData($make = '', $model = '', $submodel = '', $enginesize = '', $subpg = '')
    {
        $years = $this->getYears($make, $model, $submodel, $enginesize);

        $requestArray = [
            'GetPartsData' => [
                'UserName' => $this->catalogue['username'],
                'PassWord' => $this->catalogue['password'],
                'SubPg' => $subpg,
                'Manuf' => $make,
                'Model' => $model,
                'SModel' => $submodel,
                'EngS' => $enginesize,
                'StYr' => $years['styr']
            ]
        ];

        $parts = $this->apiService->processRequest($this->catalogue['wsdl'], 'GetPartsData', $requestArray, true);

        $partsArr = [];
        foreach ($parts['normalized']['1']['NewDataSet']['AC'] as $part) {
            array_push($partsArr, $part['Dt1']);
        }

        return $partsArr;
    }

    /**
     * return product and subproduct group list.
     *
     * @return array
     */
    public function getAllPG()
    {
        $requestArray = [
            'GetAllPG' => [
                'UserName' => $this->catalogue['username'],
                'PassWord' => $this->catalogue['password']
            ]
        ];

        $productGroups = $this->apiService->processRequest($this->catalogue['wsdl'], 'GetAllPG', $requestArray, true);

        return json_decode(json_encode($productGroups['raw']->GetAllPGResult->PGType), true);
    }

    /**
     * return suppliers list.
     *
     * @return array
     */
    public function getSupplierInfo()
    {
        $requestArray = [
            'GetSInfo' => [
                'MamCode' => $this->catalogue['mamCode']
            ]
        ];

        $suppliers = $this->apiService->processRequest($this->catalogue['wsdl'], 'GetSInfo', $requestArray, true);

        return json_decode(json_encode($suppliers['raw']->GetSInfoResult->OneField->a), true);
    }
}
