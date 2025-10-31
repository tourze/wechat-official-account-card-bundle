<?php

namespace WechatOfficialAccountCardBundle\Enum;

use Tourze\EnumExtra\BadgeInterface;
use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum CodeType: string implements Itemable, Labelable, Selectable, BadgeInterface
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

    case CODE_TYPE_TEXT = 'CODE_TYPE_TEXT';           // 文本
    case CODE_TYPE_BARCODE = 'CODE_TYPE_BARCODE';     // 一维码
    case CODE_TYPE_QRCODE = 'CODE_TYPE_QRCODE';       // 二维码
    case CODE_TYPE_ONLY_QRCODE = 'CODE_TYPE_ONLY_QRCODE'; // 仅显示二维码
    case CODE_TYPE_ONLY_BARCODE = 'CODE_TYPE_ONLY_BARCODE'; // 仅显示一维码
    case CODE_TYPE_NONE = 'CODE_TYPE_NONE';           // 不显示任何码型

    public function getLabel(): string
    {
        return match ($this) {
            self::CODE_TYPE_TEXT => '文本',
            self::CODE_TYPE_BARCODE => '一维码',
            self::CODE_TYPE_QRCODE => '二维码',
            self::CODE_TYPE_ONLY_QRCODE => '仅显示二维码',
            self::CODE_TYPE_ONLY_BARCODE => '仅显示一维码',
            self::CODE_TYPE_NONE => '不显示任何码型',
        };
    }

    public function getBadge(): string
    {
        return match ($this) {
            self::CODE_TYPE_TEXT => self::SECONDARY,
            self::CODE_TYPE_BARCODE => self::PRIMARY,
            self::CODE_TYPE_QRCODE => self::SUCCESS,
            self::CODE_TYPE_ONLY_QRCODE => self::INFO,
            self::CODE_TYPE_ONLY_BARCODE => self::WARNING,
            self::CODE_TYPE_NONE => self::LIGHT,
        };
    }
}
