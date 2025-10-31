<?php

namespace WechatOfficialAccountCardBundle\Request\Stats;

use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardCondSource;

/**
 * 拉取卡券概况数据接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Managing_Coupons_Vouchers_and_Cards.html#拉取卡券概况数据接口
 */
class GetBizuinInfoRequest extends WithAccountRequest
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
     * @var CardCondSource 卡券来源，0为公众平台创建的卡券数据、1是API创建的卡券数据
     */
    private CardCondSource $condSource;

    public function getRequestPath(): string
    {
        return 'https://api.weixin.qq.com/datacube/getcardbizuininfo';
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
                'cond_source' => $this->getCondSource()->value,
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

    public function getCondSource(): CardCondSource
    {
        return $this->condSource;
    }

    public function setCondSource(CardCondSource $condSource): void
    {
        $this->condSource = $condSource;
    }
}
