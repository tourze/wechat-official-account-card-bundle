<?php

namespace WechatOfficialAccountCardBundle\Enum;

use Tourze\EnumExtra\BadgeInterface;
use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum CardType: string implements Itemable, Labelable, Selectable, BadgeInterface
{
    use ItemTrait;
    use SelectTrait;

    // BadgeInterface 常量定义
    public const SUCCESS = 'success';
    public const WARNING = 'warning';
    public const DANGER = 'danger';
    public const INFO = 'info';
    public const PRIMARY = 'primary';
    public const SECONDARY = 'secondary';
    public const LIGHT = 'light';
    public const DARK = 'dark';
    public const OUTLINE = 'outline';

    case GROUPON = 'GROUPON';         // 团购券
    case CASH = 'CASH';               // 代金券
    case DISCOUNT = 'DISCOUNT';       // 折扣券
    case GIFT = 'GIFT';               // 兑换券
    case GENERAL_COUPON = 'GENERAL_COUPON'; // 优惠券
    case MEMBER_CARD = 'MEMBER_CARD'; // 会员卡
    case SCENIC_TICKET = 'SCENIC_TICKET'; // 景点门票
    case MOVIE_TICKET = 'MOVIE_TICKET'; // 电影票
    case BOARDING_PASS = 'BOARDING_PASS'; // 飞机票
    case MEETING_TICKET = 'MEETING_TICKET'; // 会议门票
    case BUS_TICKET = 'BUS_TICKET';   // 汽车票

    public function getLabel(): string
    {
        return match ($this) {
            self::GROUPON => '团购券',
            self::CASH => '代金券',
            self::DISCOUNT => '折扣券',
            self::GIFT => '兑换券',
            self::GENERAL_COUPON => '优惠券',
            self::MEMBER_CARD => '会员卡',
            self::SCENIC_TICKET => '景点门票',
            self::MOVIE_TICKET => '电影票',
            self::BOARDING_PASS => '飞机票',
            self::MEETING_TICKET => '会议门票',
            self::BUS_TICKET => '汽车票',
        };
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::GROUPON => self::SUCCESS,
            self::CASH => self::PRIMARY,
            self::DISCOUNT => self::WARNING,
            self::GIFT => self::INFO,
            self::GENERAL_COUPON => self::SECONDARY,
            self::MEMBER_CARD => self::DARK,
            self::SCENIC_TICKET => self::SUCCESS,
            self::MOVIE_TICKET => self::INFO,
            self::BOARDING_PASS => self::PRIMARY,
            self::MEETING_TICKET => self::WARNING,
            self::BUS_TICKET => self::SECONDARY,
        };
    }
}
