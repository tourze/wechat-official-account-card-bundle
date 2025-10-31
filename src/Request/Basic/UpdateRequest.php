<?php

namespace WechatOfficialAccountCardBundle\Request\Basic;

use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardType;

class UpdateRequest extends WithAccountRequest
{
    private string $cardId;

    private CardType $cardType;

    /**
     * @var array<string, mixed>
     */
    private array $baseInfo;

    public function getRequestPath(): string
    {
        return 'card/update';
    }

    /**
     * @return array<string, mixed>
     */
    public function getRequestOptions(): array
    {
        return [
            'card_id' => $this->cardId,
            strtolower($this->cardType->value) => [
                'base_info' => $this->baseInfo,
            ],
        ];
    }

    public function setCardId(string $cardId): void
    {
        $this->cardId = $cardId;
    }

    public function setCardType(CardType $cardType): void
    {
        $this->cardType = $cardType;
    }

    /**
     * @param array<string, mixed> $baseInfo
     */
    public function setBaseInfo(array $baseInfo): void
    {
        $this->baseInfo = $baseInfo;
    }
}
