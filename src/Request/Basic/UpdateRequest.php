<?php

namespace WechatOfficialAccountCardBundle\Request\Basic;

use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardType;

class UpdateRequest extends WithAccountRequest
{
    private string $cardId;

    private CardType $cardType;

    private array $baseInfo;

    public function getRequestPath(): string
    {
        return 'card/update';
    }

    public function getRequestOptions(): array
    {
        return [
            'card_id' => $this->cardId,
            strtolower($this->cardType->value) => [
                'base_info' => $this->baseInfo,
            ],
        ];
    }

    public function setCardId(string $cardId): self
    {
        $this->cardId = $cardId;

        return $this;
    }

    public function setCardType(CardType $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }

    public function setBaseInfo(array $baseInfo): self
    {
        $this->baseInfo = $baseInfo;

        return $this;
    }
}
