<?php

namespace WechatOfficialAccountCardBundle\Enum;

enum CardStatus: string
{
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
}
