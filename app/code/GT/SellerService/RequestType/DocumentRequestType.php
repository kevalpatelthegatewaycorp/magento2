<?php

namespace GT\SellerService\RequestType;

class DocumentRequestType
{
    public static function create(string $type, string $userName = '', string $password = '', string $prefix = '', string $documentNumber = ''): array
    {
        return array(
            $type => array(
                'User' => $userName,
                'Pass' => $password,
                'Prefix' => $prefix,
                'Document' => $documentNumber,
            )
        );
    }
}
