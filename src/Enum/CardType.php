<?php

namespace WechatOfficialAccountCardBundle\Enum;

enum CardType: string
{
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
}
