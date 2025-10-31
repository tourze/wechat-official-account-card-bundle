<?php

namespace WechatOfficialAccountCardBundle\Request\Consume;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 核销Code接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Redeeming_a_coupon_voucher_or_card.html#_1-2-核销Code接口
 */
class ConsumeRequest extends WithAccountRequest
{
    /**
     * @var string 需核销的Code码
     */
    private string $code;

    /**
     * @var string|null 卡券ID。创建卡券时use_custom_code填写true时必填。非自定义Code不必填写。
     */
    private ?string $cardId = null;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/code/consume';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        $data = [
            'code' => $this->getCode(),
        ];

        if (null !== $this->getCardId()) {
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
