<?php

namespace WechatOfficialAccountCardBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardCode;

#[When(env: 'test')]
#[When(env: 'dev')]
class CardCodeFixtures extends Fixture implements DependentFixtureInterface
{
    public const CARD_CODE_UNUSED_REFERENCE = 'card-code-unused';
    public const CARD_CODE_USED_REFERENCE = 'card-code-used';
    public const CARD_CODE_UNAVAILABLE_REFERENCE = 'card-code-unavailable';

    public function load(ObjectManager $manager): void
    {
        /** @var Card $card */
        $card = $this->getReference(CardFixtures::CARD_BASIC_REFERENCE, Card::class);

        // 创建未使用的卡券码
        $cardCodeUnused = new CardCode();
        $cardCodeUnused->setCard($card);
        $cardCodeUnused->setCode('CODE_UNUSED_' . uniqid());
        $cardCodeUnused->setIsUsed(false);
        $cardCodeUnused->setIsUnavailable(false);

        $manager->persist($cardCodeUnused);
        $this->addReference(self::CARD_CODE_UNUSED_REFERENCE, $cardCodeUnused);

        // 创建已使用的卡券码
        $cardCodeUsed = new CardCode();
        $cardCodeUsed->setCard($card);
        $cardCodeUsed->setCode('CODE_USED_' . uniqid());
        $cardCodeUsed->setIsUsed(true);
        $cardCodeUsed->setIsUnavailable(false);
        $cardCodeUsed->setUsedAt(new \DateTimeImmutable());

        $manager->persist($cardCodeUsed);
        $this->addReference(self::CARD_CODE_USED_REFERENCE, $cardCodeUsed);

        // 创建已失效的卡券码
        $cardCodeUnavailable = new CardCode();
        $cardCodeUnavailable->setCard($card);
        $cardCodeUnavailable->setCode('CODE_UNAVAILABLE_' . uniqid());
        $cardCodeUnavailable->setIsUsed(false);
        $cardCodeUnavailable->setIsUnavailable(true);
        $cardCodeUnavailable->setUnavailableAt(new \DateTimeImmutable());

        $manager->persist($cardCodeUnavailable);
        $this->addReference(self::CARD_CODE_UNAVAILABLE_REFERENCE, $cardCodeUnavailable);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CardFixtures::class];
    }
}
