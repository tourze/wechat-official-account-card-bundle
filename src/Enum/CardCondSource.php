<?php

namespace WechatOfficialAccountCardBundle\Enum;

enum CardCondSource: int
{
    case PLATFORM = 0;  // 公众平台创建的卡券数据
    case API = 1;       // API创建的卡券数据
}
