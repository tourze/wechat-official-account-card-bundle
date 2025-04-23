<?php

namespace WechatOfficialAccountCardBundle\Request\Consume;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * Code解码接口
 * 支持两种场景：
 * 1. 商家获取choos_card_info后，将card_id和encrypt_code字段通过解码接口，获取真实code
 * 2. 卡券内跳转外链的签名中会对code进行加密处理，通过调用解码接口获取真实code
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Redeeming_a_coupon_voucher_or_card.html#_2-2-Code解码接口
 */
class DecryptCardCodeRequest extends WithAccountRequest
{
    /**
     * @var string 经过加密的Code码
     */
    private string $encryptCode;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/code/decrypt';
    }

    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'encrypt_code' => $this->getEncryptCode(),
            ],
        ];
    }

    public function getEncryptCode(): string
    {
        return $this->encryptCode;
    }

    public function setEncryptCode(string $encryptCode): void
    {
        $this->encryptCode = $encryptCode;
    }
}
