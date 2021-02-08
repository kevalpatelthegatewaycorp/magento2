<?php

namespace GT\SellerService\RequestType;

class PartSearchRequestType
{
    public static function create(string $type, string $userName = '', string $password = '', string $part = '', string $account = '', string $category = '', string $searchType, string $exclStock = '', string $exclPrices = ''): array
    {
        return array(
            $type => array(
                'psr' => array(
                    'User' => $userName,
                    'Pass' => $password,
                    'Part' => $part,
                    'Acct' => $account,
                    'Cat' => $category,
                    'type' => $searchType,
                    'ExclStock' => $exclStock,
                    'ExclPrices' => $exclPrices,
                )
            )
        );
    }
}
