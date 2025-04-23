<?php

namespace WechatOfficialAccountCardBundle\Request\Consume;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 更改Code接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Managing_Coupons_Vouchers_and_Cards.html#6
 */
class UpdateCardCodeRequest extends WithAccountRequest
{
    /**
     * @var string 需要改变的code序列号
     */
    private string $code;

    /**
     * @var string 卡券ID
     */
    private string $cardId;

    /**
     * @var string 新的卡券code序列号
     */
    private string $newCode;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/code/update';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'code' => $this->getCode(),
                'card_id' => $this->getCardId(),
                'new_code' => $this->getNewCode(),
            ],
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

    public function getCardId(): string
    {
        return $this->cardId;
    }

    public function setCardId(string $cardId): void
    {
        $this->cardId = $cardId;
    }

    public function getNewCode(): string
    {
        return $this->newCode;
    }

    public function setNewCode(string $newCode): void
    {
        $this->newCode = $newCode;
    }
}
