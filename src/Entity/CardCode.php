<?php

namespace WechatOfficialAccountCardBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DoctrineEnhanceBundle\Traits\PrimaryKeyAware;
use DoctrineEnhanceBundle\Traits\TimestampableAware;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\DoctrineTimestampBundle\Attribute\UpdateTimeColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;

#[ORM\Entity]
#[ORM\Table(name: 'woa_card_code')]
class CardCode
{
    use PrimaryKeyAware;

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

    #[Filterable]
    #[IndexColumn]
    #[ListColumn(order: 98, sorter: true)]
    #[ExportColumn]
    #[CreateTimeColumn]
    #[Groups(['restful_read', 'admin_curd', 'restful_read'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '创建时间'])]
    private ?\DateTimeInterface $createTime = null;

    #[UpdateTimeColumn]
    #[ListColumn(order: 99, sorter: true)]
    #[Groups(['restful_read', 'admin_curd', 'restful_read'])]
    #[Filterable]
    #[ExportColumn]
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, options: ['comment' => '更新时间'])]
    private ?\DateTimeInterface $updateTime = null;

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

    public function setCreateTime(?\DateTimeInterface $createdAt): void
    {
        $this->createTime = $createdAt;
    }

    public function getCreateTime(): ?\DateTimeInterface
    {
        return $this->createTime;
    }

    public function setUpdateTime(?\DateTimeInterface $updateTime): void
    {
        $this->updateTime = $updateTime;
    }

    public function getUpdateTime(): ?\DateTimeInterface
    {
        return $this->updateTime;
    }
}
