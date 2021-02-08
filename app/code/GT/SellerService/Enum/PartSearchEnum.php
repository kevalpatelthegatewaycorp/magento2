<?php

namespace GT\SellerService\Enum;

final class PartSearchEnum
{
    public const KEYCODE = 'Keycode';
    public const DESC = 'Desc';
    public const USER = 'User';
    public const CARRIAGE = 'Carriage';

    public const ALL_VALUES = [
        self::KEYCODE, self::DESC, self::USER, self::CARRIAGE
    ];
}
