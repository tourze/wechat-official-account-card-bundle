<?php

namespace WechatOfficialAccountCardBundle\Entity\Embed;

use Doctrine\ORM\Mapping as ORM;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CodeType;

#[ORM\Embeddable]
class CardBaseInfo
{
    #[ORM\Column(type: 'string', length: 255, options: ['comment' => '商户logo'])]
    private string $logoUrl;

    #[ORM\Column(type: 'string', length: 36, options: ['comment' => '商户名称'])]
    private string $brandName;

    #[ORM\Column(type: 'string', enumType: CodeType::class, options: ['comment' => '码类型'])]
    private CodeType $codeType;

    #[ORM\Column(type: 'string', length: 27, options: ['comment' => '卡券名'])]
    private string $title;

    #[ORM\Column(type: 'string', enumType: CardColor::class, options: ['comment' => '卡券颜色'])]
    private CardColor $color;

    #[ORM\Column(type: 'string', length: 48, options: ['comment' => '使用提醒'])]
    private string $notice;

    #[ORM\Column(type: 'string', length: 3072, options: ['comment' => '使用说明'])]
    private string $description;

    #[ORM\Column(type: 'integer', options: ['comment' => '库存数量'])]
    private int $quantity;

    #[ORM\Column(type: 'integer', options: ['comment' => '每人使用次数限制'])]
    private int $useLimit;

    #[ORM\Column(type: 'integer', options: ['comment' => '每人领取次数限制'])]
    private int $getLimit;

    #[ORM\Column(type: 'boolean', options: ['comment' => '是否可分享'])]
    private bool $canShare = true;

    #[ORM\Column(type: 'boolean', options: ['comment' => '是否可转赠'])]
    private bool $canGiveFriend = true;

    #[ORM\Column(type: 'string', length: 20, nullable: true, options: ['comment' => '客服电话'])]
    private ?string $servicePhone = null;

    #[ORM\Embedded(class: CardDateInfo::class)]
    private CardDateInfo $dateInfo;

    public function __construct()
    {
        $this->dateInfo = new CardDateInfo();
    }

    public function getLogoUrl(): string
    {
        return $this->logoUrl;
    }

    public function setLogoUrl(string $logoUrl): void
    {
        $this->logoUrl = $logoUrl;
    }

    public function getBrandName(): string
    {
        return $this->brandName;
    }

    public function setBrandName(string $brandName): void
    {
        $this->brandName = $brandName;
    }

    public function getCodeType(): CodeType
    {
        return $this->codeType;
    }

    public function setCodeType(CodeType $codeType): void
    {
        $this->codeType = $codeType;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getColor(): CardColor
    {
        return $this->color;
    }

    public function setColor(CardColor $color): void
    {
        $this->color = $color;
    }

    public function getNotice(): string
    {
        return $this->notice;
    }

    public function setNotice(string $notice): void
    {
        $this->notice = $notice;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getUseLimit(): int
    {
        return $this->useLimit;
    }

    public function setUseLimit(int $useLimit): void
    {
        $this->useLimit = $useLimit;
    }

    public function getGetLimit(): int
    {
        return $this->getLimit;
    }

    public function setGetLimit(int $getLimit): void
    {
        $this->getLimit = $getLimit;
    }

    public function isCanShare(): bool
    {
        return $this->canShare;
    }

    public function setCanShare(bool $canShare): void
    {
        $this->canShare = $canShare;
    }

    public function isCanGiveFriend(): bool
    {
        return $this->canGiveFriend;
    }

    public function setCanGiveFriend(bool $canGiveFriend): void
    {
        $this->canGiveFriend = $canGiveFriend;
    }

    public function getServicePhone(): ?string
    {
        return $this->servicePhone;
    }

    public function setServicePhone(?string $servicePhone): void
    {
        $this->servicePhone = $servicePhone;
    }

    public function getDateInfo(): CardDateInfo
    {
        return $this->dateInfo;
    }

    public function setDateInfo(CardDateInfo $dateInfo): void
    {
        $this->dateInfo = $dateInfo;
    }
}
