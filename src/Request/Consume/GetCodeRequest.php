<?php

namespace WechatOfficialAccountCardBundle\Request\Consume;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 查询Code接口
 * 我们强烈建议开发者在调用核销code接口之前调用查询code接口，并在核销之前对非法状态的code(如转赠中、已删除、已核销等)做出处理。
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Redeeming_a_coupon_voucher_or_card.html
 */
class GetCodeRequest extends WithAccountRequest
{
    /**
     * @var string 卡券ID代表一类卡券。自定义code卡券必填。
     */
    private string $code;

    /**
     * @var string|null 卡券ID。示例值：pFS7Fjg8kV1IdDz01r4SQwMkuCKc
     */
    private ?string $cardId = null;

    /**
     * @var bool|null 是否校验code核销状态，填入true和false时的code异常状态返回数据不同
     */
    private ?bool $checkConsume = null;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/code/get';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        $json = [
            'code' => $this->getCode(),
        ];
        if (null !== $this->getCardId()) {
            $json['card_id'] = $this->getCardId();
        }
        if (null !== $this->getCheckConsume()) {
            $json['check_consume'] = $this->getCheckConsume();
        }

        return [
            'json' => $json,
        ];
    }

    public function getRequestMethod(): ?string
    {
        return 'POST';
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

    public function getCheckConsume(): ?bool
    {
        return $this->checkConsume;
    }

    public function setCheckConsume(?bool $checkConsume): void
    {
        $this->checkConsume = $checkConsume;
    }
}
