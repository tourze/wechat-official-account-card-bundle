<?php

namespace WechatOfficialAccountCardBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity]
#[ORM\Table(name: 'woa_card_receive', options: ['comment' => '微信卡券领取记录'])]
class CardReceive implements \Stringable
{
    use TimestampableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private ?int $id = 0;

    public function getId(): ?int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: Card::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Card $card;

    #[ORM\ManyToOne(targetEntity: CardCode::class)]
    #[ORM\JoinColumn(nullable: false)]
    private CardCode $cardCode;

    #[ORM\Column(type: Types::STRING, length: 50, options: ['comment' => '用户openid'])]
    private string $openId;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否已使用'])]
    private bool $isUsed = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '使用时间'])]
    private ?\DateTimeImmutable $usedAt = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否已失效'])]
    private bool $isUnavailable = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '失效时间'])]
    private ?\DateTimeImmutable $unavailableAt = null;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否已转赠'])]
    private bool $isGiven = false;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true, options: ['comment' => '转赠时间'])]
    private ?\DateTimeImmutable $givenAt = null;

    #[ORM\Column(type: Types::STRING, length: 50, nullable: true, options: ['comment' => '转赠目标用户openid'])]
    private ?string $givenToOpenId = null;

    public function getCard(): Card
    {
        return $this->card;
    }

    public function setCard(Card $card): void
    {
        $this->card = $card;
    }

    public function getCardCode(): CardCode
    {
        return $this->cardCode;
    }

    public function setCardCode(CardCode $cardCode): void
    {
        $this->cardCode = $cardCode;
    }

    public function getOpenId(): string
    {
        return $this->openId;
    }

    public function setOpenId(string $openId): void
    {
        $this->openId = $openId;
    }

    public function isUsed(): bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(bool $isUsed): void
    {
        $this->isUsed = $isUsed;
        if ($isUsed) {
            $this->usedAt = new \DateTimeImmutable();
        }
    }

    public function getUsedAt(): ?\DateTimeImmutable
    {
        return $this->usedAt;
    }

    public function setUsedAt(?\DateTimeImmutable $usedAt): void
    {
        $this->usedAt = $usedAt;
    }

    public function isUnavailable(): bool
    {
        return $this->isUnavailable;
    }

    public function setIsUnavailable(bool $isUnavailable): void
    {
        $this->isUnavailable = $isUnavailable;
        if ($isUnavailable) {
            $this->unavailableAt = new \DateTimeImmutable();
        }
    }

    public function getUnavailableAt(): ?\DateTimeImmutable
    {
        return $this->unavailableAt;
    }

    public function setUnavailableAt(?\DateTimeImmutable $unavailableAt): void
    {
        $this->unavailableAt = $unavailableAt;
    }

    public function isGiven(): bool
    {
        return $this->isGiven;
    }

    public function setIsGiven(bool $isGiven): void
    {
        $this->isGiven = $isGiven;
        if ($isGiven) {
            $this->givenAt = new \DateTimeImmutable();
        }
    }

    public function getGivenAt(): ?\DateTimeImmutable
    {
        return $this->givenAt;
    }

    public function setGivenAt(?\DateTimeImmutable $givenAt): void
    {
        $this->givenAt = $givenAt;
    }

    public function getGivenToOpenId(): ?string
    {
        return $this->givenToOpenId;
    }

    public function setGivenToOpenId(?string $givenToOpenId): void
    {
        $this->givenToOpenId = $givenToOpenId;
    }

    public function __toString(): string
    {
        return $this->openId ?? 'New CardReceive';
    }
}
