<?php

namespace GT\SellerService\RequestType;

class VocRequestType
{
    public static function create(string $type, string $userName = '', string $password = '', string $account = '', $pg = '', $dateFrom = '', $dateTo = ''): array
    {
        return array(
            $type => array(
                'vr' => array(
                    'User' => $userName,
                    'Pass' => $password,
                    'Acct' => $account,
                    'PG' => $pg,
                    'DateFrom' => $dateFrom,
                    'DateTo' => $dateTo,
                )
            )
        );
    }
}
