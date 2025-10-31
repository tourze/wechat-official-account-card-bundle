<?php

namespace WechatOfficialAccountCardBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Repository\CardRepository;

#[ORM\Entity(repositoryClass: CardRepository::class)]
#[ORM\Table(name: 'woa_card', options: ['comment' => '微信卡券'])]
class Card implements \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    use SnowflakeKeyAware;

    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id', nullable: false)]
    private Account $account;

    #[ORM\Column(type: Types::STRING, length: 50, options: ['comment' => '微信返回的卡券ID'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 50)]
    #[IndexColumn]
    private string $cardId;

    #[ORM\Column(type: Types::STRING, enumType: CardType::class, options: ['comment' => '卡券类型'])]
    #[Assert\NotNull]
    #[Assert\Choice(choices: [CardType::GROUPON, CardType::CASH, CardType::DISCOUNT, CardType::GIFT, CardType::GENERAL_COUPON, CardType::MEMBER_CARD, CardType::SCENIC_TICKET, CardType::MOVIE_TICKET, CardType::BOARDING_PASS, CardType::MEETING_TICKET, CardType::BUS_TICKET])]
    private CardType $cardType;

    #[ORM\Column(type: Types::STRING, enumType: CardStatus::class, options: ['comment' => '卡券状态'])]
    #[Assert\NotNull]
    #[Assert\Choice(choices: [CardStatus::NOT_VERIFY, CardStatus::VERIFY_FAIL, CardStatus::VERIFY_OK, CardStatus::DELETE, CardStatus::DISPATCH])]
    private CardStatus $status;

    #[ORM\Embedded(class: CardBaseInfo::class)]
    #[Assert\Valid]
    private CardBaseInfo $baseInfo;

    #[ORM\Column(type: Types::BOOLEAN, options: ['comment' => '是否正在同步', 'default' => false])]
    #[Assert\Type(type: 'bool')]
    private bool $syncing = false;

    public function __construct()
    {
        $this->baseInfo = new CardBaseInfo();
    }

    public function isSyncing(): bool
    {
        return $this->syncing;
    }

    public function setSyncing(bool $syncing): void
    {
        $this->syncing = $syncing;
    }

    public function getAccount(): Account
    {
        return $this->account;
    }

    public function setAccount(Account $account): void
    {
        $this->account = $account;
    }

    public function getCardId(): string
    {
        return $this->cardId;
    }

    public function setCardId(string $cardId): void
    {
        $this->cardId = $cardId;
    }

    public function getCardType(): CardType
    {
        return $this->cardType;
    }

    public function setCardType(CardType $cardType): void
    {
        $this->cardType = $cardType;
    }

    public function getStatus(): CardStatus
    {
        return $this->status;
    }

    public function setStatus(CardStatus $status): void
    {
        $this->status = $status;
    }

    public function getBaseInfo(): CardBaseInfo
    {
        return $this->baseInfo;
    }

    public function setBaseInfo(CardBaseInfo $baseInfo): void
    {
        $this->baseInfo = $baseInfo;
    }

    public function __toString(): string
    {
        return $this->cardId ?? 'New Card';
    }
}
