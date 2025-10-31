<?php

namespace WechatOfficialAccountCardBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardCode;
use WechatOfficialAccountCardBundle\Entity\CardReceive;

#[When(env: 'test')]
#[When(env: 'dev')]
class CardReceiveFixtures extends Fixture implements DependentFixtureInterface
{
    public const CARD_RECEIVE_UNUSED_REFERENCE = 'card-receive-unused';
    public const CARD_RECEIVE_USED_REFERENCE = 'card-receive-used';
    public const CARD_RECEIVE_GIVEN_REFERENCE = 'card-receive-given';

    public function load(ObjectManager $manager): void
    {
        // 获取依赖的 Card 和 CardCode 记录
        /** @var Card $card */
        $card = $this->getReference(CardFixtures::CARD_BASIC_REFERENCE, Card::class);
        /** @var CardCode $cardCode */
        $cardCode = $this->getReference(CardCodeFixtures::CARD_CODE_UNUSED_REFERENCE, CardCode::class);

        // 创建未使用的领取记录
        $cardReceiveUnused = new CardReceive();
        $cardReceiveUnused->setCard($card);
        $cardReceiveUnused->setCardCode($cardCode);
        $cardReceiveUnused->setOpenId('test_openid_unused_' . uniqid());
        $cardReceiveUnused->setIsUsed(false);
        $cardReceiveUnused->setIsUnavailable(false);
        $cardReceiveUnused->setIsGiven(false);

        $manager->persist($cardReceiveUnused);
        $this->addReference(self::CARD_RECEIVE_UNUSED_REFERENCE, $cardReceiveUnused);

        // 创建已使用的领取记录
        $cardReceiveUsed = new CardReceive();
        $cardReceiveUsed->setCard($card);
        $cardReceiveUsed->setCardCode($cardCode);
        $cardReceiveUsed->setOpenId('test_openid_used_' . uniqid());
        $cardReceiveUsed->setIsUsed(true);
        $cardReceiveUsed->setUsedAt(new \DateTimeImmutable('-1 day'));
        $cardReceiveUsed->setIsUnavailable(false);
        $cardReceiveUsed->setIsGiven(false);

        $manager->persist($cardReceiveUsed);
        $this->addReference(self::CARD_RECEIVE_USED_REFERENCE, $cardReceiveUsed);

        // 创建已转赠的领取记录
        $cardReceiveGiven = new CardReceive();
        $cardReceiveGiven->setCard($card);
        $cardReceiveGiven->setCardCode($cardCode);
        $cardReceiveGiven->setOpenId('test_openid_given_' . uniqid());
        $cardReceiveGiven->setIsUsed(false);
        $cardReceiveGiven->setIsUnavailable(false);
        $cardReceiveGiven->setIsGiven(true);
        $cardReceiveGiven->setGivenAt(new \DateTimeImmutable('-2 hours'));
        $cardReceiveGiven->setGivenToOpenId('test_openid_receiver_' . uniqid());

        $manager->persist($cardReceiveGiven);
        $this->addReference(self::CARD_RECEIVE_GIVEN_REFERENCE, $cardReceiveGiven);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CardFixtures::class, CardCodeFixtures::class];
    }
}
