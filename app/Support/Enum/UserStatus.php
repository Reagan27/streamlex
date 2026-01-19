<?php

namespace Vanguard\Support\Enum;

enum UserStatus: string {
    case UNCONFIRMED = 'Unconfirmed';
    case ACTIVE = 'Active';
    case BANNED = 'Banned';
    case DISABLED = 'Disabled';

    public static function lists(): array
    {
        return [
            self::ACTIVE->value => trans('app.status.'.self::ACTIVE->value),
            self::BANNED->value => trans('app.status.'.self::BANNED->value),
            self::UNCONFIRMED->value => trans('app.status.'.self::UNCONFIRMED->value),
            self::DISABLED->value => trans('app.status.'.self::DISABLED->value),
        ];
    }
}