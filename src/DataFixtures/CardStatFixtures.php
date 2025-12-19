<?php

namespace WechatOfficialAccountCardBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatOfficialAccountBundle\DataFixtures\AccountFixtures;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;
use WechatOfficialAccountCardBundle\Entity\CardStat;

#[When(env: 'test')]
#[When(env: 'dev')]
class CardStatFixtures extends Fixture implements DependentFixtureInterface
{
    public const CARD_STATS_TODAY_REFERENCE = 'card-stats-today';
    public const CARD_STATS_YESTERDAY_REFERENCE = 'card-stats-yesterday';
    public const CARD_STATS_WEEK_AGO_REFERENCE = 'card-stats-week-ago';

    public function load(ObjectManager $manager): void
    {
        // 尝试获取 Account fixture
        try {
            /** @var Account $account */
            $account = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE, Account::class);
        } catch (\Exception $e) {
            // 如果 Account fixture 不存在，创建一个临时的 Account
            $account = new Account();
            $account->setAppId('test_app_id_' . uniqid());
            $account->setAppSecret('test_app_secret');
            $account->setName('Test Account');
            $manager->persist($account);
            $manager->flush();
        }

        // 创建一个 Card 用于统计数据
        $card = new Card();
        $card->setAccount($account);
        $card->setCardId('test_card_for_stats_' . uniqid());
        $card->setCardType(CardType::GENERAL_COUPON);
        $card->setStatus(CardStatus::VERIFY_OK);
        $card->setSyncing(true); // 跳过 EventListener 的 API 调用

        // 设置 CardBaseInfo
        $baseInfo = $card->getBaseInfo();
        $baseInfo->setLogoUrl('https://images.unsplash.com/photo-1556742049-0cfed4f6a45d');
        $baseInfo->setBrandName('测试商户');
        $baseInfo->setCodeType(CodeType::CODE_TYPE_QRCODE);
        $baseInfo->setTitle('测试卡券');
        $baseInfo->setColor(CardColor::COLOR_010);
        $baseInfo->setNotice('测试提醒');
        $baseInfo->setDescription('测试说明');
        $baseInfo->setQuantity(100);
        $baseInfo->setUseLimit(1);
        $baseInfo->setGetLimit(1);

        $card->setCreatedBy('system');
        $card->setUpdatedBy('system');
        $manager->persist($card);
        $manager->flush();

        // 创建今天的统计数据
        $cardStatsToday = new CardStat();
        $cardStatsToday->setCard($card);
        $cardStatsToday->setStatsDate(new \DateTimeImmutable('2024-01-01'));
        $cardStatsToday->setReceiveCount(10);
        $cardStatsToday->setUseCount(5);
        $cardStatsToday->setGiveCount(2);
        $cardStatsToday->setViewCount(20);
        $cardStatsToday->setNewFollowCount(3);
        $cardStatsToday->setUnfollowCount(1);
        $cardStatsToday->setGiveReceiveCount(1);

        $manager->persist($cardStatsToday);
        $this->addReference(self::CARD_STATS_TODAY_REFERENCE, $cardStatsToday);

        // 创建昨天的统计数据
        $cardStatsYesterday = new CardStat();
        $cardStatsYesterday->setCard($card);
        $cardStatsYesterday->setStatsDate(new \DateTimeImmutable('2023-12-31'));
        $cardStatsYesterday->setReceiveCount(8);
        $cardStatsYesterday->setUseCount(4);
        $cardStatsYesterday->setGiveCount(1);
        $cardStatsYesterday->setViewCount(15);
        $cardStatsYesterday->setNewFollowCount(2);
        $cardStatsYesterday->setUnfollowCount(0);
        $cardStatsYesterday->setGiveReceiveCount(1);

        $manager->persist($cardStatsYesterday);
        $this->addReference(self::CARD_STATS_YESTERDAY_REFERENCE, $cardStatsYesterday);

        // 创建一周前的统计数据
        $cardStatsWeekAgo = new CardStat();
        $cardStatsWeekAgo->setCard($card);
        $cardStatsWeekAgo->setStatsDate(new \DateTimeImmutable('2023-12-25'));
        $cardStatsWeekAgo->setReceiveCount(5);
        $cardStatsWeekAgo->setUseCount(2);
        $cardStatsWeekAgo->setGiveCount(1);
        $cardStatsWeekAgo->setViewCount(10);
        $cardStatsWeekAgo->setNewFollowCount(1);
        $cardStatsWeekAgo->setUnfollowCount(0);
        $cardStatsWeekAgo->setGiveReceiveCount(0);

        $manager->persist($cardStatsWeekAgo);
        $this->addReference(self::CARD_STATS_WEEK_AGO_REFERENCE, $cardStatsWeekAgo);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [CardFixtures::class];
    }
}
