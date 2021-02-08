<?php

namespace GT\SellerService\Integration;

use Exception;
use GT\Api\Exception\SoapException;
use GT\Api\Service\ApiService;
use GT\SellerService\Enum\PartSearchEnum;
use GT\SellerService\Exception\SellerServiceIntegrationException;
use GT\SellerService\Interfaces\SellerServiceInterface;
use GT\SellerService\RequestType\DocumentRequestType;
use GT\SellerService\RequestType\InvoiceRequestType;
use GT\SellerService\RequestType\ListRequestType;
use GT\SellerService\RequestType\PartSearchRequestType;
use GT\SellerService\RequestType\PingForwardType;
use GT\SellerService\RequestType\VocRequestType;

class SellerIntegration implements SellerServiceInterface
{
    private $apiService;

    private $wsdl;

    private $userName;

    private $password;

    private $logger;

    public function __construct(
        ApiService $apiService,
        \Magento\Framework\App\DeploymentConfig $deploymentConfig,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->apiService = $apiService;
        $this->wsdl = $deploymentConfig->get('mam/sellerService/wsdl');
        $this->userName = $deploymentConfig->get('mam/sellerService/userName');
        $this->password = $deploymentConfig->get('mam/sellerService/password');
        $this->logger = $logger;
    }

    /**
     * {@inheritDoc}
     * @throws SellerServiceIntegrationException
     */
    public function getAccountList(string $account = '', int $topX, bool $showZero): array
    {
        $parameters = ListRequestType::create('AccountListRequest', $this->userName, $this->password, $account, $topX, $showZero);

        try {
            $response = $this->apiService->processRequest($this->wsdl, 'AccountListRequest', $parameters);

            return json_decode(json_encode($response['raw']->AccountListRequestResult->ReturnValue->Alr), true);
        } catch (SoapException | Exception $exception) {
            $this->logger->error('An error occured while fetching account list for account no : ' . $account, [$exception, $parameters]);
            throw new SellerServiceIntegrationException('An error occured while fetching account list for account no : ' . $account, 400, $exception);
        }
    }

    /**
     * {@inheritDoc}
     * @throws SellerServiceIntegrationException
     */
    public function getAcctDetails(string $account = '', string $pg = '', string $dateFrom = '', string $dateTo = ''): array
    {
        $parameters = VocRequestType::create('AcctDetails', $this->userName, $this->password, $account, $pg, $dateFrom, $dateTo);

        try {
            $response = $this->apiService->processRequest($this->wsdl, 'AcctDetails', $parameters, true);

            return $response['normalized'];
        } catch (SoapException | Exception $exception) {
            $this->logger->error('An error occured while fetching account details for account no : ' . $account, [$exception, $parameters]);
            throw new SellerServiceIntegrationException('An error occured while fetching account details for account no : ' . $account, 400, $exception);
        }
    }

    /**
     * {@inheritDoc}
     * @throws SellerServiceIntegrationException
     */
    public function getDocumentDetails(string $prefix = '', string $documentNumber = ''): array
    {
        $parameters = DocumentRequestType::create('DocumentRequest', $this->userName, $this->password, $prefix, $documentNumber);

        try {
            $response = $this->apiService->processRequest($this->wsdl, 'DocumentRequest', $parameters, true);

            return $response;
        } catch (SoapException | Exception $exception) {
            $this->logger->error('An error occured while fetching document details for document no : ' . $documentNumber, [$exception, $parameters]);
            throw new SellerServiceIntegrationException('An error occured while fetching document details for document no : ' . $documentNumber, 400, $exception);
        }
    }

    /**
     * {@inheritDoc}
     * @throws SellerServiceIntegrationException
     */
    public function getInvoiceList(string $account = '', int $topX, bool $showZero): array
    {
        $parameters = ListRequestType::create('InvoiceListRequest', $this->userName, $this->password, $account, $topX, $showZero);

        try {
            $response = $this->apiService->processRequest($this->wsdl, 'InvoiceListRequest', $parameters, true);

            return $response['raw']->InvoiceListRequestResult->ReturnValue->Ilr;
        } catch (SoapException | Exception $exception) {
            $this->logger->error('An error occured while fetching invoice list details for account no : ' . $account, [$exception, $parameters]);
            throw new SellerServiceIntegrationException('An error occured while fetching invoice list details for account no : ' . $account, 400, $exception);
        }
    }

    /**
     * {@inheritDoc}
     * @throws SellerServiceIntegrationException
     */
    public function getInvoiceDetails(string $prefix = '', string $documentNumber = '', string $account = ''): array
    {
        $parameters = InvoiceRequestType::create('InvoiceRequest', $this->userName, $this->password, $prefix, $documentNumber, $account);

        try {
            $response = $this->apiService->processRequest($this->wsdl, 'InvoiceRequest', $parameters, true);

            return $response['normalized'];
        } catch (SoapException | Exception $exception) {
            $this->logger->error('An error occured while fetching invoice details for account no : ' . $account, [$exception, $parameters]);
            throw new SellerServiceIntegrationException('An error occured while fetching invoice details for account no : ' . $account, 400, $exception);
        }
    }

    /**
     * {@inheritDoc}
     * @throws SellerServiceIntegrationException
     */
    public function searchPart(string $part = '', string $account, string $category = '', string $searchType, string $exclStock = '', string $exclPrices = ''): array
    {
        if (!in_array($searchType, PartSearchEnum::ALL_VALUES)) {
            return [
                'error' => 'Please enter a valid search type.',
                'acceptedSearchTypes' => PartSearchEnum::ALL_VALUES
            ];
        }
        $parameters = PartSearchRequestType::create('PartSearch', $this->userName, $this->password, $part, $account, $category, $searchType, $exclStock, $exclPrices);

        try {
            $response = $this->apiService->processRequest($this->wsdl, 'PartSearch', $parameters, true);

            return $response['normalized'];
        } catch (SoapException | Exception $exception) {
            $this->logger->error('An error occured while searching part details for account no : ' . $account . ' with part : ' . $part, [$exception, $parameters]);
            throw new SellerServiceIntegrationException('An error occured while searching part details for account no : ' . $account . ' with part : ' . $part, 400, $exception);
        }
    }

    /**
     * {@inheritDoc}
     * @throws SellerServiceIntegrationException
     */
    public function searchPartPrice(string $part = '', string $account, string $category = '', string $searchType, string $exclStock = '', string $exclPrices = ''): array
    {
        if (!in_array($searchType, PartSearchEnum::ALL_VALUES)) {
            return [
                'error' => 'Please enter a valid search type.',
                'acceptedSearchTypes' => PartSearchEnum::ALL_VALUES
            ];
        }
        $parameters = PartSearchRequestType::create('PartSearchPrice', $this->userName, $this->password, $part, $account, $category, $searchType, $exclStock, $exclPrices);

        try {
            $response = $this->apiService->processRequest($this->wsdl, 'PartSearchPrice', $parameters, true);

            return $response['normalized'];
        } catch (SoapException | Exception $exception) {
            $this->logger->error('An error occured while searching part price details for account no : ' . $account . ' with part : ' . $part, [$exception, $parameters]);
            throw new SellerServiceIntegrationException('An error occured while searching part price details for account no : ' . $account . ' with part : ' . $part, 400, $exception);
        }
    }

    /**
     * {@inheritDoc}
     * @throws SellerServiceIntegrationException
     */
    public function ping(): string
    {
        try {
            $response = $this->apiService->processRequest($this->wsdl, 'Ping');

            return $response['raw']->PingResult;
        } catch (SoapException | Exception $exception) {
            $this->logger->error('An error occured while establishing communication with external server.');
            throw new SellerServiceIntegrationException('An error occured while establishing communication with external server.', 400, $exception);
        }
    }

    /**
     * {@inheritDoc}
     * @throws SellerServiceIntegrationException
     */
    public function pingForward(string $sellerLocationId = ''): array
    {
        $parameters = PingForwardType::create('sellerLocationId', $sellerLocationId);

        try {
            $response = $this->apiService->processRequest($this->wsdl, 'PingForward', $parameters, true);

            return $response;
        } catch (SoapException | Exception $exception) {
            $this->logger->error('An error occured while establishing forward communication with external server.');
            throw new SellerServiceIntegrationException('An error occured while establishing forward communication with external server.', 400, $exception);
        }
    }

    /**
     * {@inheritDoc}
     * @throws SellerServiceIntegrationException
     */
    public function vocRequest(string $account = '', string $pg = '', string $dateFrom = '', string $dateTo = ''): array
    {
        $parameters = VocRequestType::create('VOCRequest', $this->userName, $this->password, $account, $pg, $dateFrom, $dateTo);

        try {
            $response = $this->apiService->processRequest($this->wsdl, 'VOCRequest', $parameters, true);

            return $response['normalized'];
        } catch (SoapException | Exception $exception) {
            $this->logger->error('An error occured while fetching VOC details for account no : ' . $account, [$exception, $parameters]);
            throw new SellerServiceIntegrationException('An error occured while fetching VOC details for account no : ' . $account, 400, $exception);
        }
    }
}
