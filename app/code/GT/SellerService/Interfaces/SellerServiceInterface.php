<?php

namespace GT\SellerService\Interfaces;

interface SellerServiceInterface
{
    /**
     * Retrives account list information.
     *
     * @param string $account
     * @param int $topX
     * @param boolean $showZero
     * @return array
     */
    public function getAccountList(string $account = '', int $topX, bool $showZero): array;

    /**
     * Retrives account details.
     *
     * @param string $account
     * @param string $pg
     * @param string $dateFrom
     * @param string $dateTo
     * @return array
     */
    public function getAcctDetails(string $account = '', string $pg = '', string $dateFrom = '', string $dateTo = ''): array;

    /**
     * Gets document details.
     *
     * @param string $prefix
     * @param string $documentNumber
     * @return array
     */
    public function getDocumentDetails(string $prefix = '', string $documentNumber = ''): array;

    /**
     * Gets Invoice list.
     *
     * @param string $account
     * @param int $topX
     * @param boolean $showZero
     * @return array
     */
    public function getInvoiceList(string $account = '', int $topX, bool $showZero): array;

    /**
     * Gets invoice details.
     *
     * @param string $prefix
     * @param string $documentNumber
     * @param string $account
     * @return array
     */
    public function getInvoiceDetails(string $prefix = '', string $documentNumber = '', string $account = ''): array;

    /**
     * Searches parts.
     *
     * @param string $part
     * @param string $account
     * @param string $category
     * @param string $type
     * @param string $exclStock
     * @param string $exclPrices
     * @return array
     */
    public function searchPart(string $part = '', string $account, string $category = '', string $searchType, string $exclStock = '', string $exclPrices = ''): array;

    /**
     * Search part prices.
     *
     * @param string $part
     * @param string $account
     * @param string $category
     * @param string $type
     * @param string $exclStock
     * @param string $exclPrices
     * @return array
     */
    public function searchPartPrice(string $part = '', string $account, string $category = '', string $searchType, string $exclStock = '', string $exclPrices = ''): array;

    /**
     * Used to identify if the server is responding.
     *
     * @return string
     */
    public function ping(): string;

    /**
     * Pings ERP response.
     *
     * @param string $sellerLocationId
     * @return array
     */
    public function pingForward(string $sellerLocationId = ''): array;

    /**
     * Gets VOC details.
     *
     * @param string $account
     * @param string $pg
     * @param string $dateFrom
     * @param string $dateTo
     * @return array
     */
    public function vocRequest(string $account = '', string $pg = '', string $dateFrom = '', string $dateTo = ''): array;
}
