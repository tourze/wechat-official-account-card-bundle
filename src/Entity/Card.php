<?php

namespace WechatOfficialAccountCardBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;
use Tourze\EasyAdmin\Attribute\Column\ExportColumn;
use Tourze\EasyAdmin\Attribute\Column\ListColumn;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Repository\CardRepository;

#[ORM\Entity(repositoryClass: CardRepository::class)]
#[ORM\Table(name: 'woa_card')]
#[ORM\Index(columns: ['card_id'], name: 'idx_card_id')]
#[ORM\Index(columns: ['account_id'], name: 'idx_account_id')]
class Card
{
    use TimestampableAware;
    use BlameableAware;
    #[ExportColumn]
    #[ListColumn(order: -1, sorter: true)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;


    #[ORM\ManyToOne(targetEntity: Account::class)]
    #[ORM\JoinColumn(name: 'account_id', referencedColumnName: 'id', nullable: false)]
    private Account $account;

    #[ORM\Column(type: Types::STRING, length: 50, options: ['comment' => '微信返回的卡券ID'])]
    private string $cardId;

    #[ORM\Column(type: Types::STRING, enumType: CardType::class, options: ['comment' => '卡券类型'])]
    private CardType $cardType;

    #[ORM\Column(type: Types::STRING, enumType: CardStatus::class, options: ['comment' => '卡券状态'])]
    private CardStatus $status;

    #[ORM\Embedded(class: CardBaseInfo::class)]
    private CardBaseInfo $baseInfo;

    private bool $syncing = false;

    public function getId(): ?string
    {
        return $this->id;
    }


    public function isSyncing(): bool
    {
        return $this->syncing;
    }

    public function setSyncing(bool $syncing): static
    {
        $this->syncing = $syncing;

        return $this;
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

}
