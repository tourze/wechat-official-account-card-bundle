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

    /**
     * @return array<string, mixed>
     */
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

    public function setCardType(CardType $cardType): void
    {
        $this->cardType = $cardType;
    }

    public function setLogoUrl(string $logoUrl): void
    {
        $this->logoUrl = $logoUrl;
    }

    public function setBrandName(string $brandName): void
    {
        $this->brandName = $brandName;
    }

    public function setCodeType(CodeType $codeType): void
    {
        $this->codeType = $codeType;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function setColor(CardColor $color): void
    {
        $this->color = $color;
    }

    public function setNotice(string $notice): void
    {
        $this->notice = $notice;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function setDateType(DateType $dateType): void
    {
        $this->dateType = $dateType;
    }

    public function setUseLimit(int $useLimit): void
    {
        $this->useLimit = $useLimit;
    }

    public function setGetLimit(int $getLimit): void
    {
        $this->getLimit = $getLimit;
    }

    public function setCanShare(bool $canShare): void
    {
        $this->canShare = $canShare;
    }

    public function setCanGiveFriend(bool $canGiveFriend): void
    {
        $this->canGiveFriend = $canGiveFriend;
    }

    public function setBeginTimestamp(?int $beginTimestamp): void
    {
        $this->beginTimestamp = $beginTimestamp;
    }

    public function setEndTimestamp(?int $endTimestamp): void
    {
        $this->endTimestamp = $endTimestamp;
    }

    public function setFixedTerm(?int $fixedTerm): void
    {
        $this->fixedTerm = $fixedTerm;
    }

    public function setFixedBeginTerm(?int $fixedBeginTerm): void
    {
        $this->fixedBeginTerm = $fixedBeginTerm;
    }
}
