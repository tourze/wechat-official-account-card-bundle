<?php

namespace WechatOfficialAccountCardBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum DateType: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    case DATE_TYPE_FIX_TIME_RANGE = 'DATE_TYPE_FIX_TIME_RANGE'; // 固定日期区间
    case DATE_TYPE_FIX_TERM = 'DATE_TYPE_FIX_TERM';            // 固定时长（自领取后按天算）
    case DATE_TYPE_PERMANENT = 'DATE_TYPE_PERMANENT';           // 永久有效

    public function getLabel(): string
    {
        return match ($this) {
            self::DATE_TYPE_FIX_TIME_RANGE => '固定日期区间',
            self::DATE_TYPE_FIX_TERM => '固定时长（自领取后按天算）',
            self::DATE_TYPE_PERMANENT => '永久有效',
        };
    }
}
