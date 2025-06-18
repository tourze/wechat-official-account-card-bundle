<?php

namespace WechatOfficialAccountCardBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum CardColor: string implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    case COLOR_010 = '#63b359';  // 淡绿色
    case COLOR_020 = '#2c9f67';  // 深绿色
    case COLOR_030 = '#509fc9';  // 浅蓝色
    case COLOR_040 = '#5885cf';  // 蓝色
    case COLOR_050 = '#9062c0';  // 紫色
    case COLOR_060 = '#d09a45';  // 棕色
    case COLOR_070 = '#e4b138';  // 黄色
    case COLOR_080 = '#ee903c';  // 橙色
    case COLOR_081 = '#f08500';  // 橙色
    case COLOR_082 = '#a9d92d';  // 绿色
    case COLOR_090 = '#dd6549';  // 红色
    case COLOR_100 = '#cc463d';  // 深红色
    case COLOR_101 = '#cf3e36';  // 深红色
    case COLOR_102 = '#5E6671';  // 灰色

    public function getLabel(): string
    {
        return match ($this) {
            self::COLOR_010 => '淡绿色',
            self::COLOR_020 => '深绿色',
            self::COLOR_030 => '浅蓝色',
            self::COLOR_040 => '蓝色',
            self::COLOR_050 => '紫色',
            self::COLOR_060 => '棕色',
            self::COLOR_070 => '黄色',
            self::COLOR_080 => '橙色',
            self::COLOR_081 => '橙色',
            self::COLOR_082 => '绿色',
            self::COLOR_090 => '红色',
            self::COLOR_100 => '深红色',
            self::COLOR_101 => '深红色',
            self::COLOR_102 => '灰色',
        };
    }
}
