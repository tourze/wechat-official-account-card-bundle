<?php

namespace WechatOfficialAccountCardBundle\Request\Consume;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 获取用户已领取卡券接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Managing_Coupons_Vouchers_and_Cards.html#1
 */
class GetUserListRequest extends WithAccountRequest
{
    /**
     * @var string 需要查询的用户openid
     */
    private string $openId;

    /**
     * @var string|null 卡券ID。不填写时默认查询当前appid下的卡券
     */
    private ?string $cardId = null;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/user/getcardlist';
    }

    public function getRequestOptions(): ?array
    {
        $data = [
            'openid' => $this->getOpenId(),
        ];

        if ($this->getCardId() !== null) {
            $data['card_id'] = $this->getCardId();
        }

        return [
            'json' => $data,
        ];
    }

    public function getOpenId(): string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): void
    {
        $this->openId = $openId;
    }

    public function getCardId(): ?string
    {
        return $this->cardId;
    }

    public function setCardId(?string $cardId): void
    {
        $this->cardId = $cardId;
    }
}
