<?php

namespace WechatOfficialAccountCardBundle\Request\Stats;

use WechatOfficialAccountBundle\Request\WithAccountRequest;

/**
 * 拉取单张会员卡数据接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Managing_Coupons_Vouchers_and_Cards.html#拉取单张会员卡数据接口
 */
class GetMemberCardDetailRequest extends WithAccountRequest
{
    /**
     * @var \DateTimeInterface 查询数据的起始时间
     */
    private \DateTimeInterface $beginDate;

    /**
     * @var \DateTimeInterface 查询数据的结束时间
     */
    private \DateTimeInterface $endDate;

    /**
     * @var string 卡券ID
     */
    private string $cardId;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/datacube/getcardmembercarddetail';
    }

    /**
     * @return array<string, mixed>|null
     */
    public function getRequestOptions(): ?array
    {
        return [
            'json' => [
                'begin_date' => $this->getBeginDate()->format('Y-m-d'),
                'end_date' => $this->getEndDate()->format('Y-m-d'),
                'card_id' => $this->getCardId(),
            ],
        ];
    }

    public function getBeginDate(): \DateTimeInterface
    {
        return $this->beginDate;
    }

    public function setBeginDate(\DateTimeInterface $beginDate): void
    {
        $this->beginDate = $beginDate;
    }

    public function getEndDate(): \DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): void
    {
        $this->endDate = $endDate;
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
