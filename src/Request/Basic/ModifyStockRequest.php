<?php

namespace WechatOfficialAccountCardBundle\Request\Basic;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 修改库存接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Managing_Coupons_Vouchers_and_Cards.html#5
 */
class ModifyStockRequest extends WithAccountRequest
{
    /**
     * @var string 卡券ID
     */
    private string $cardId;

    /**
     * @var int 增加库存数量
     */
    private int $increaseStockValue = 0;

    /**
     * @var int 减少库存数量
     */
    private int $reduceStockValue = 0;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/card/modifystock';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        $data = [
            'card_id' => $this->getCardId(),
        ];

        if ($this->getIncreaseStockValue() > 0) {
            $data['increase_stock_value'] = $this->getIncreaseStockValue();
        }

        if ($this->getReduceStockValue() > 0) {
            $data['reduce_stock_value'] = $this->getReduceStockValue();
        }

        return [
            'json' => $data,
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

    public function getIncreaseStockValue(): int
    {
        return $this->increaseStockValue;
    }

    public function setIncreaseStockValue(int $increaseStockValue): void
    {
        if ($increaseStockValue < 0) {
            $increaseStockValue = 0;
        }
        $this->increaseStockValue = $increaseStockValue;
    }

    public function getReduceStockValue(): int
    {
        return $this->reduceStockValue;
    }

    public function setReduceStockValue(int $reduceStockValue): void
    {
        if ($reduceStockValue < 0) {
            $reduceStockValue = 0;
        }
        $this->reduceStockValue = $reduceStockValue;
    }
}
