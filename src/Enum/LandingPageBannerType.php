<?php

namespace WechatOfficialAccountCardBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 货架页面样式枚举
 */
enum LandingPageBannerType: int implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;
    /**
     * 主要用于图文消息场景。
     * 用户点击图文消息会打开一个卡券列表页面，展示优惠券和通用优惠券。
     */
    case URL = 0;

    /**
     * 主要用于朋友圈场景。
     * 用户点击会打开一个卡券列表页面，展示优惠券和通用优惠券。
     */
    case BANNER = 1;

    /**
     * 主要用于单张卡券页面场景。
     * 页面上方显示一个正方形的卡券。
     */
    case CELL = 2;

    public function getLabel(): string
    {
        return match ($this) {
            self::URL => '图文消息场景',
            self::BANNER => '朋友圈场景',
            self::CELL => '单张卡券页面场景',
        };
    }
}
