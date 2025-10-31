<?php

namespace WechatOfficialAccountCardBundle\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use WechatOfficialAccountBundle\Service\OfficialAccountClient;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Request\Basic\CreateRequest;
use WechatOfficialAccountCardBundle\Request\Basic\UpdateRequest;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: Card::class)]
#[AsEntityListener(event: Events::postPersist, method: 'postPersist', entity: Card::class)]
#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: Card::class)]
#[AsEntityListener(event: Events::postUpdate, method: 'postUpdate', entity: Card::class)]
class CardListener
{
    public function __construct(
        private readonly OfficialAccountClient $client,
    ) {
    }

    public function prePersist(Card $card, PrePersistEventArgs $args): void
    {
        if ($card->isSyncing()) {
            return;
        }

        // 创建卡券
        $createRequest = new CreateRequest();
        $createRequest->setAccount($card->getAccount());
        $createRequest->setCardType($card->getCardType());
        $createRequest->setLogoUrl($card->getBaseInfo()->getLogoUrl());
        $createRequest->setBrandName($card->getBaseInfo()->getBrandName());
        $createRequest->setCodeType($card->getBaseInfo()->getCodeType());
        $createRequest->setTitle($card->getBaseInfo()->getTitle());
        $createRequest->setColor($card->getBaseInfo()->getColor());
        $createRequest->setNotice($card->getBaseInfo()->getNotice());
        $createRequest->setDescription($card->getBaseInfo()->getDescription());
        $createRequest->setQuantity($card->getBaseInfo()->getQuantity());
        $createRequest->setDateType($card->getBaseInfo()->getDateInfo()->getType());
        $createRequest->setUseLimit($card->getBaseInfo()->getUseLimit());
        $createRequest->setGetLimit($card->getBaseInfo()->getGetLimit());
        $createRequest->setCanShare($card->getBaseInfo()->isCanShare());
        $createRequest->setCanGiveFriend($card->getBaseInfo()->isCanGiveFriend());

        // 根据时间类型设置具体时间
        $dateInfo = $card->getBaseInfo()->getDateInfo();
        if (null !== $dateInfo->getBeginTimestamp()) {
            $createRequest->setBeginTimestamp($dateInfo->getBeginTimestamp());
        }
        if (null !== $dateInfo->getEndTimestamp()) {
            $createRequest->setEndTimestamp($dateInfo->getEndTimestamp());
        }
        if (null !== $dateInfo->getFixedTerm()) {
            $createRequest->setFixedTerm($dateInfo->getFixedTerm());
        }
        if (null !== $dateInfo->getFixedBeginTerm()) {
            $createRequest->setFixedBeginTerm($dateInfo->getFixedBeginTerm());
        }

        $response = $this->client->request($createRequest);
        if (!\is_array($response) || !isset($response['card_id']) || !\is_string($response['card_id'])) {
            throw new \RuntimeException('Invalid response from WeChat API: missing card_id');
        }
        $card->setCardId($response['card_id']);
    }

    public function postPersist(Card $card, PostPersistEventArgs $args): void
    {
    }

    public function preUpdate(Card $card, PreUpdateEventArgs $args): void
    {
        if ($card->isSyncing()) {
            return;
        }

        // 更新卡券
        $updateRequest = new UpdateRequest();
        $updateRequest->setAccount($card->getAccount());
        $updateRequest->setCardId($card->getCardId());
        $updateRequest->setCardType($card->getCardType());

        // 设置基本信息
        $baseInfo = [
            'logo_url' => $card->getBaseInfo()->getLogoUrl(),
            'brand_name' => $card->getBaseInfo()->getBrandName(),
            'code_type' => $card->getBaseInfo()->getCodeType()->value,
            'title' => $card->getBaseInfo()->getTitle(),
            'color' => $card->getBaseInfo()->getColor()->value,
            'notice' => $card->getBaseInfo()->getNotice(),
            'description' => $card->getBaseInfo()->getDescription(),
            'service_phone' => $card->getBaseInfo()->getServicePhone(),
            'use_limit' => $card->getBaseInfo()->getUseLimit(),
            'get_limit' => $card->getBaseInfo()->getGetLimit(),
            'can_share' => $card->getBaseInfo()->isCanShare(),
            'can_give_friend' => $card->getBaseInfo()->isCanGiveFriend(),
        ];

        $updateRequest->setBaseInfo($baseInfo);
        $this->client->request($updateRequest);
    }

    public function postUpdate(Card $card, PostUpdateEventArgs $args): void
    {
    }
}
