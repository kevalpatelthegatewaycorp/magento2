<?php

namespace GT\SellerService\RequestType;

class ListRequestType
{
    public static function create(string $type, string $userName = '', string $password = '', string $account = '', string $topX, bool $showZero): array
    {
        return array(
            $type => array(
                'lr' => array(
                    'User' => $userName,
                    'Pass' => $password,
                    'Acct' => $account,
                    'TopX' => $topX,
                    'ShowZero' => $showZero,
                )
            )
        );
    }
}
