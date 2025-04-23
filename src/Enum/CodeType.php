<?php

namespace WechatOfficialAccountCardBundle\Enum;

enum CodeType: string
{
    case CODE_TYPE_TEXT = 'CODE_TYPE_TEXT';           // 文本
    case CODE_TYPE_BARCODE = 'CODE_TYPE_BARCODE';     // 一维码
    case CODE_TYPE_QRCODE = 'CODE_TYPE_QRCODE';       // 二维码
    case CODE_TYPE_ONLY_QRCODE = 'CODE_TYPE_ONLY_QRCODE'; // 仅显示二维码
    case CODE_TYPE_ONLY_BARCODE = 'CODE_TYPE_ONLY_BARCODE'; // 仅显示一维码
    case CODE_TYPE_NONE = 'CODE_TYPE_NONE';           // 不显示任何码型
}
