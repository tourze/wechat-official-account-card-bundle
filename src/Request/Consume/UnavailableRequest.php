<?php

namespace WechatOfficialAccountCardBundle\Request\Consume;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 设置卡券失效接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Managing_Coupons_Vouchers_and_Cards.html#8
 */
class UnavailableRequest extends WithAccountRequest
{
    /**
     * @var string 需要设置为失效的code序列号
     */
    private string $code;

    /**
     * @var string|null 自定义code卡券的card_id，非自定义code可不填写
     */
    private ?string $cardId = null;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/code/unavailable';
    }

    public function getRequestOptions(): ?array
    {
        $data = [
            'code' => $this->getCode(),
        ];

        if ($this->getCardId() !== null) {
            $data['card_id'] = $this->getCardId();
        }

        return [
            'json' => $data,
        ];
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
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
