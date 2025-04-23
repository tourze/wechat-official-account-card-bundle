<?php

namespace WechatOfficialAccountCardBundle\Enum;

enum DateType: string
{
    case DATE_TYPE_FIX_TIME_RANGE = 'DATE_TYPE_FIX_TIME_RANGE'; // 固定日期区间
    case DATE_TYPE_FIX_TERM = 'DATE_TYPE_FIX_TERM';            // 固定时长（自领取后按天算）
    case DATE_TYPE_PERMANENT = 'DATE_TYPE_PERMANENT';           // 永久有效
}
