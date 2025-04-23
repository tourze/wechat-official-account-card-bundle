<?php

namespace WechatOfficialAccountCardBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 卡券状态
 */
enum CardStateEnum: int implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case NORMAL = 0;
    case GATHER = 1;
    case DELETE = -1;
    case VERIFIED = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::NORMAL => '待领取',
            self::GATHER => '已领取',
            self::DELETE => '已删除',
            self::VERIFIED => '已核销',
        };
    }

    public static function getList(): array
    {
        return [
            self::NORMAL->value => '待领取',
            self::GATHER->value => '已领取',
            self::DELETE->value => '已删除',
            self::VERIFIED->value => '已核销',
        ];
    }
}
