<?php

namespace WechatOfficialAccountCardBundle\Enum;

use Tourze\EnumExtra\Itemable;
use Tourze\EnumExtra\ItemTrait;
use Tourze\EnumExtra\Labelable;
use Tourze\EnumExtra\Selectable;
use Tourze\EnumExtra\SelectTrait;

/**
 * 卡券事件推送
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Coupons_Vouchers_and_Cards_Event_Push_Messages.html
 */
enum EventTypeEnum: string implements Labelable, Itemable, Selectable
{
    use ItemTrait;
    use SelectTrait;

    case USER_GET_CARD = 'user_get_card';
    case USER_DELETE_CARD = 'user_del_card';
    case USER_CONSUME_CARD = 'user_consume_card';

    public function getLabel(): string
    {
        return match ($this) {
            self::USER_GET_CARD => '用户领取卡券',
            self::USER_DELETE_CARD => '用户删除卡券',
            self::USER_CONSUME_CARD => '用户核销卡券',
        };
    }
}
