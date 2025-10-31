<?php

namespace WechatOfficialAccountCardBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

enum CardCondSource: int implements Itemable, Labelable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case PLATFORM = 0;  // 公众平台创建的卡券数据
    case API = 1;       // API创建的卡券数据

    public function getLabel(): string
    {
        return match ($this) {
            self::PLATFORM => '公众平台创建',
            self::API => 'API创建',
        };
    }
}
