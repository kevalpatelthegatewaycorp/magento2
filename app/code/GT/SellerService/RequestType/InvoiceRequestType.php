<?php

namespace GT\SellerService\RequestType;

class InvoiceRequestType
{
    public static function create(string $type, string $userName = '', string $password = '', string $prefix = '', string $documentNumber = '', string $account = ''): array
    {
        return array(
            $type => array(
                'ir' => array(
                    'User' => $userName,
                    'Pass' => $password,
                    'Prefix' => $prefix,
                    'DocNo' => $documentNumber,
                    'Acct' => $account,
                )
            )
        );
    }
}
