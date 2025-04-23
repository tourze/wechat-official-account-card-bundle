<?php

namespace WechatOfficialAccountCardBundle\Request\Basic;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 查看卡券详情接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Managing_Coupons_Vouchers_and_Cards.html#2
 */
class GetDetailRequest extends WithAccountRequest
{
    /**
     * @var string 卡券ID
     */
    private string $cardId;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/get';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'card_id' => $this->getCardId(),
            ],
        ];
    }

    public function getCardId(): string
    {
        return $this->cardId;
    }

    public function setCardId(string $cardId): void
    {
        $this->cardId = $cardId;
    }
}
