<?php

namespace WechatOfficialAccountCardBundle\Request\Distribute;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 导入code接口
 *
 * 在自定义code卡券成功创建并且通过审核后，必须将自定义code按照与发券方的约定数量调用导入code接口导入微信后台。
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Distributing_Coupons_Vouchers_and_Cards.html#_4-1-导入自定义code-仅对自定义code商户
 */
class ImportCardCodeRequest extends WithAccountRequest
{
    /**
     * @var string 需要进行导入code的卡券ID
     */
    private string $cardId;

    /**
     * @var array<string> 需导入微信卡券后台的自定义code，上限为100个
     */
    private array $codeList = [];

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/code/deposit';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'card_id' => $this->getCardId(),
                'code' => $this->getCodeList(),
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

    /**
     * @return array<string>
     */
    public function getCodeList(): array
    {
        return $this->codeList;
    }

    /**
     * @param array<string> $codeList
     */
    public function setCodeList(array $codeList): void
    {
        if (count($codeList) > 100) {
            throw new \InvalidArgumentException('Code list cannot exceed 100 items');
        }
        $this->codeList = $codeList;
    }

    public function addCode(string $code): void
    {
        if (count($this->codeList) >= 100) {
            throw new \InvalidArgumentException('Code list cannot exceed 100 items');
        }
        $this->codeList[] = $code;
    }
}
