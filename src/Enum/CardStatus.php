<?php

namespace WechatOfficialAccountCardBundle\Enum;

use Tourze\EnumExtra\BadgeInterface;
use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum CardStatus: string implements Itemable, Labelable, Selectable, BadgeInterface
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

    /**
     * 待审核
     */
    case NOT_VERIFY = 'CARD_STATUS_NOT_VERIFY';

    /**
     * 审核失败
     */
    case VERIFY_FAIL = 'CARD_STATUS_VERIFY_FAIL';

    /**
     * 通过审核
     */
    case VERIFY_OK = 'CARD_STATUS_VERIFY_OK';

    /**
     * 已删除
     */
    case DELETE = 'CARD_STATUS_DELETE';

    /**
     * 已投放
     */
    case DISPATCH = 'CARD_STATUS_DISPATCH';

    public function getLabel(): string
    {
        return match ($this) {
            self::NOT_VERIFY => '待审核',
            self::VERIFY_FAIL => '审核失败',
            self::VERIFY_OK => '通过审核',
            self::DELETE => '已删除',
            self::DISPATCH => '已投放',
        };
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::NOT_VERIFY => self::WARNING,
            self::VERIFY_FAIL => self::DANGER,
            self::VERIFY_OK => self::SUCCESS,
            self::DELETE => self::SECONDARY,
            self::DISPATCH => self::PRIMARY,
        };
    }
}
