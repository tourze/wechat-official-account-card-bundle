<?php

namespace WechatOfficialAccountCardBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;

#[ORM\Entity]
#[ORM\Table(name: 'woa_card_stats', options: ['comment' => '微信卡券统计数据'])]
class CardStat implements \Stringable
{
    use TimestampableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    public function getId(): int
    {
        return $this->id;
    }

    #[ORM\ManyToOne(targetEntity: Card::class)]
    #[ORM\JoinColumn(nullable: false)]
    private Card $card;

    #[ORM\Column(type: Types::DATE_IMMUTABLE, options: ['comment' => '统计日期'])]
    #[Assert\NotNull]
    #[Assert\Type(type: '\DateTimeImmutable')]
    private ?\DateTimeImmutable $statsDate = null;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '领取次数'])]
    #[Assert\PositiveOrZero]
    private int $receiveCount = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '使用次数'])]
    #[Assert\PositiveOrZero]
    private int $useCount = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '转赠次数'])]
    #[Assert\PositiveOrZero]
    private int $giveCount = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '浏览次数'])]
    #[Assert\PositiveOrZero]
    private int $viewCount = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '新增关注数'])]
    #[Assert\PositiveOrZero]
    private int $newFollowCount = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '取消关注数'])]
    #[Assert\PositiveOrZero]
    private int $unfollowCount = 0;

    #[ORM\Column(type: Types::INTEGER, options: ['comment' => '转赠领取数'])]
    #[Assert\PositiveOrZero]
    private int $giveReceiveCount = 0;

    public function getCard(): Card
    {
        return $this->card;
    }

    public function setCard(Card $card): void
    {
        $this->card = $card;
    }

    public function getStatsDate(): ?\DateTimeImmutable
    {
        return $this->statsDate;
    }

    public function setStatsDate(?\DateTimeImmutable $statsDate): void
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

    public function __toString(): string
    {
        return ($this->statsDate?->format('Y-m-d') ?? 'No Date') . ' - ' . $this->card->getCardId();
    }
}
