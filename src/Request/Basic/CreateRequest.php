<?php

namespace WechatOfficialAccountCardBundle\Request\Basic;

use WechatOfficialAccountBundle\Request\WithAccountRequest;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;
use WechatOfficialAccountCardBundle\Enum\DateType;

class CreateRequest extends WithAccountRequest
{
    private CardType $cardType;

    private string $logoUrl;

    private string $brandName;

    private CodeType $codeType;

    private string $title;

    private CardColor $color;

    private string $notice;

    private string $description;

    private int $quantity;

    private DateType $dateType;

    private int $useLimit;

    private int $getLimit;

    private bool $canShare = true;

    private bool $canGiveFriend = true;

    private ?int $beginTimestamp = null;

    private ?int $endTimestamp = null;

    private ?int $fixedTerm = null;

    private ?int $fixedBeginTerm = null;

    public function getRequestPath(): string
    {
        return 'card/create';
    }

    public function getRequestOptions(): array
    {
        $baseInfo = [
            'logo_url' => $this->logoUrl,
            'brand_name' => $this->brandName,
            'code_type' => $this->codeType->value,
            'title' => $this->title,
            'color' => $this->color->value,
            'notice' => $this->notice,
            'description' => $this->description,
            'sku' => ['quantity' => $this->quantity],
            'date_info' => [
                'type' => $this->dateType->value,
            ],
            'use_limit' => $this->useLimit,
            'get_limit' => $this->getLimit,
            'can_share' => $this->canShare,
            'can_give_friend' => $this->canGiveFriend,
        ];

        // 根据时间类型设置具体时间
        if (DateType::DATE_TYPE_FIX_TIME_RANGE === $this->dateType) {
            $baseInfo['date_info']['begin_timestamp'] = $this->beginTimestamp;
            $baseInfo['date_info']['end_timestamp'] = $this->endTimestamp;
        } elseif (DateType::DATE_TYPE_FIX_TERM === $this->dateType) {
            $baseInfo['date_info']['fixed_term'] = $this->fixedTerm;
            $baseInfo['date_info']['fixed_begin_term'] = $this->fixedBeginTerm;
        }

        return [
            'card' => [
                'card_type' => $this->cardType->value,
                strtolower($this->cardType->value) => [
                    'base_info' => $baseInfo,
                ],
            ],
        ];
    }

    public function setCardType(CardType $cardType): self
    {
        $this->cardType = $cardType;

        return $this;
    }

    public function setLogoUrl(string $logoUrl): self
    {
        $this->logoUrl = $logoUrl;

        return $this;
    }

    public function setBrandName(string $brandName): self
    {
        $this->brandName = $brandName;

        return $this;
    }

    public function setCodeType(CodeType $codeType): self
    {
        $this->codeType = $codeType;

        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function setColor(CardColor $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function setNotice(string $notice): self
    {
        $this->notice = $notice;

        return $this;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setQuantity(int $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function setDateType(DateType $dateType): self
    {
        $this->dateType = $dateType;

        return $this;
    }

    public function setUseLimit(int $useLimit): self
    {
        $this->useLimit = $useLimit;

        return $this;
    }

    public function setGetLimit(int $getLimit): self
    {
        $this->getLimit = $getLimit;

        return $this;
    }

    public function setCanShare(bool $canShare): self
    {
        $this->canShare = $canShare;

        return $this;
    }

    public function setCanGiveFriend(bool $canGiveFriend): self
    {
        $this->canGiveFriend = $canGiveFriend;

        return $this;
    }

    public function setBeginTimestamp(?int $beginTimestamp): self
    {
        $this->beginTimestamp = $beginTimestamp;

        return $this;
    }

    public function setEndTimestamp(?int $endTimestamp): self
    {
        $this->endTimestamp = $endTimestamp;

        return $this;
    }

    public function setFixedTerm(?int $fixedTerm): self
    {
        $this->fixedTerm = $fixedTerm;

        return $this;
    }

    public function setFixedBeginTerm(?int $fixedBeginTerm): self
    {
        $this->fixedBeginTerm = $fixedBeginTerm;

        return $this;
    }
}
