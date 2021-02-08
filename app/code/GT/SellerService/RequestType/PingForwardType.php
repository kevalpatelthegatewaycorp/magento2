<?php

namespace GT\SellerService\RequestType;

class PingForwardType
{
    public static function create(string $type, string $sellerLocationId = ''): array
    {
        return array(
            $type => array(
                'sellerLocationId' => $sellerLocationId,
            )
        );
    }
}
