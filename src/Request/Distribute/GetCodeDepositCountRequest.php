<?php

namespace WechatOfficialAccountCardBundle\Request\Distribute;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 查询导入code数目接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Distributing_Coupons_Vouchers_and_Cards.html#_4-1-导入自定义code-仅对自定义code商户
 */
class GetCardCodeDepositCountRequest extends WithAccountRequest
{
    /**
     * @var string 进行导入code的卡券ID
     */
    private string $cardId;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/code/getdepositcount';
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
