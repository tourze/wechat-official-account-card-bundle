<?php

namespace WechatOfficialAccountCardBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;

#[ORM\Entity]
#[ORM\Table(name: 'woa_card_code')]
class CardCode
{
    use TimestampableAware;
    #[ListColumn(order: -1)]
    #[ExportColumn]
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

    #[ORM\Column(type: 'string', length: 50, options: ['comment' => '卡券code'])]
    private string $code;

    #[ORM\Column(type: 'boolean', options: ['comment' => '是否已使用'])]
    private bool $isUsed = false;

    #[ORM\Column(type: 'datetime', nullable: true, options: ['comment' => '使用时间'])]
    private ?\DateTimeInterface $usedAt = null;

    #[ORM\Column(type: 'boolean', options: ['comment' => '是否已失效'])]
    private bool $isUnavailable = false;

    #[ORM\Column(type: 'datetime', nullable: true, options: ['comment' => '失效时间'])]
    private ?\DateTimeInterface $unavailableAt = null;

    public function getCard(): Card
    {
        return $this->card;
    }

    public function setCard(Card $card): void
    {
        $this->card = $card;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function isUsed(): bool
    {
        return $this->isUsed;
    }

    public function setIsUsed(bool $isUsed): void
    {
        $this->isUsed = $isUsed;
        if ($isUsed) {
            $this->usedAt = new \DateTime();
        }
    }

    public function getUsedAt(): ?\DateTimeInterface
    {
        return $this->usedAt;
    }

    public function setUsedAt(?\DateTimeInterface $usedAt): void
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
            $this->unavailableAt = new \DateTime();
        }
    }

    public function getUnavailableAt(): ?\DateTimeInterface
    {
        return $this->unavailableAt;
    }

    public function setUnavailableAt(?\DateTimeInterface $unavailableAt): void
    {
        $this->unavailableAt = $unavailableAt;
    }

}
