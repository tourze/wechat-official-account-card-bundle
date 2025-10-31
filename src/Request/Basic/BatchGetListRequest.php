<?php

namespace WechatOfficialAccountCardBundle\Request\Basic;

use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardStatus;

/**
 * 批量查询卡券列表接口
 *
 * @see https://developers.weixin.qq.com/doc/offiaccount/Cards_and_Offer/Managing_Coupons_Vouchers_and_Cards.html#3
 */
class BatchGetListRequest extends WithAccountRequest
{
    private int $offset = 0;

    private int $count = 50;

    /**
     * @var CardStatus[]|null
     */
    private ?array $statusList = null;

    public function getRequestPath(): string
    {
        return 'card/batchget';
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequestOptions(): array
    {
        $options = [
            'offset' => $this->offset,
            'count' => $this->count,
        ];

        if (null !== $this->statusList) {
            $options['status_list'] = array_map(fn (CardStatus $status) => $status->value, $this->statusList);
        }

        return $options;
    }

    public function setOffset(int $offset): void
    {
        $this->offset = $offset;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    /**
     * @param CardStatus[]|null $statusList
     */
    public function setStatusList(?array $statusList): void
    {
        $this->statusList = $statusList;
    }
}
