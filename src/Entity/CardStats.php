<?php

namespace WechatOfficialAccountCardBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Attribute\CreateTimeColumn;
use Tourze\DoctrineTimestampBundle\Attribute\UpdateTimeColumn;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use Tourze\EasyAdmin\Attribute\Filter\Filterable;

#[ORM\Entity]
#[ORM\Table(name: 'woa_card_stats')]
class CardStats
{
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

    #[ORM\Column(type: 'date', options: ['comment' => '统计日期'])]
    private \DateTimeInterface $statsDate;

    #[ORM\Column(type: 'integer', options: ['comment' => '领取次数'])]
    private int $receiveCount = 0;

    #[ORM\Column(type: 'integer', options: ['comment' => '使用次数'])]
    private int $useCount = 0;

    #[ORM\Column(type: 'integer', options: ['comment' => '转赠次数'])]
    private int $giveCount = 0;

    #[ORM\Column(type: 'integer', options: ['comment' => '浏览次数'])]
    private int $viewCount = 0;

    #[ORM\Column(type: 'integer', options: ['comment' => '新增关注数'])]
    private int $newFollowCount = 0;

    #[ORM\Column(type: 'integer', options: ['comment' => '取消关注数'])]
    private int $unfollowCount = 0;

    #[ORM\Column(type: 'integer', options: ['comment' => '转赠领取数'])]
    private int $giveReceiveCount = 0;

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

    public function getStatsDate(): \DateTimeInterface
    {
        return $this->statsDate;
    }

    public function setStatsDate(\DateTimeInterface $statsDate): void
    {
        $this->statsDate = $statsDate;
    }

    public function getReceiveCount(): int
    {
        return $this->receiveCount;
    }

    public function setReceiveCount(int $receiveCount): void
    {
        $this->receiveCount = $receiveCount;
    }

    public function getUseCount(): int
    {
        return $this->useCount;
    }

    public function setUseCount(int $useCount): void
    {
        $this->useCount = $useCount;
    }

    public function getGiveCount(): int
    {
        return $this->giveCount;
    }

    public function setGiveCount(int $giveCount): void
    {
        $this->giveCount = $giveCount;
    }

    public function getViewCount(): int
    {
        return $this->viewCount;
    }

    public function setViewCount(int $viewCount): void
    {
        $this->viewCount = $viewCount;
    }

    public function getNewFollowCount(): int
    {
        return $this->newFollowCount;
    }

    public function setNewFollowCount(int $newFollowCount): void
    {
        $this->newFollowCount = $newFollowCount;
    }

    public function getUnfollowCount(): int
    {
        return $this->unfollowCount;
    }

    public function setUnfollowCount(int $unfollowCount): void
    {
        $this->unfollowCount = $unfollowCount;
    }

    public function getGiveReceiveCount(): int
    {
        return $this->giveReceiveCount;
    }

    public function setGiveReceiveCount(int $giveReceiveCount): void
    {
        $this->giveReceiveCount = $giveReceiveCount;
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
