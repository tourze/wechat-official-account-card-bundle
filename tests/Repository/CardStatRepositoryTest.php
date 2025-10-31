<?php

namespace WechatOfficialAccountCardBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardStat;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;
use WechatOfficialAccountCardBundle\Repository\CardStatRepository;

/**
 * @internal
 */
#[CoversClass(CardStatRepository::class)]
#[RunTestsInSeparateProcesses]
final class CardStatRepositoryTest extends AbstractRepositoryTestCase
{
    private Account $testAccount;

    private Card $testCard;

    protected function onSetUp(): void
    {
        $this->testAccount = new Account();
        $this->testAccount->setAppId('test_app_id_' . uniqid());
        $this->testAccount->setAppSecret('test_secret');
        $this->testAccount->setName('Test Account');
        self::getEntityManager()->persist($this->testAccount);

        $this->testCard = new Card();
        $this->testCard->setAccount($this->testAccount);
        $this->testCard->setCardId('wx_card_test_' . uniqid());
        $this->testCard->setCardType(CardType::GROUPON);
        $this->testCard->setStatus(CardStatus::VERIFY_OK);
        $this->testCard->setSyncing(true);

        $baseInfo = new CardBaseInfo();
        $baseInfo->setLogoUrl('https://example.com/logo.png');
        $baseInfo->setBrandName('Test Brand');
        $baseInfo->setCodeType(CodeType::CODE_TYPE_TEXT);
        $baseInfo->setTitle('Test Card');
        $baseInfo->setColor(CardColor::COLOR_010);
        $baseInfo->setNotice('Test Notice');
        $baseInfo->setDescription('Test Description');
        $baseInfo->setQuantity(100);
        $baseInfo->setUseLimit(1);
        $baseInfo->setGetLimit(1);
        $this->testCard->setBaseInfo($baseInfo);

        self::getEntityManager()->persist($this->testCard);
        self::getEntityManager()->flush();

        // 创建一些基础的CardStat记录来满足DataFixtures测试的要求
        $cardStat1 = new CardStat();
        $cardStat1->setCard($this->testCard);
        $cardStat1->setStatsDate(new \DateTimeImmutable('2024-01-01'));
        $cardStat1->setReceiveCount(100);
        $cardStat1->setUseCount(50);
        $cardStat1->setGiveCount(20);
        $cardStat1->setViewCount(200);
        $cardStat1->setNewFollowCount(25);
        $cardStat1->setUnfollowCount(5);
        $cardStat1->setGiveReceiveCount(10);
        self::getEntityManager()->persist($cardStat1);

        $cardStat2 = new CardStat();
        $cardStat2->setCard($this->testCard);
        $cardStat2->setStatsDate(new \DateTimeImmutable('2024-01-02'));
        $cardStat2->setReceiveCount(120);
        $cardStat2->setUseCount(60);
        $cardStat2->setGiveCount(25);
        $cardStat2->setViewCount(240);
        $cardStat2->setNewFollowCount(30);
        $cardStat2->setUnfollowCount(8);
        $cardStat2->setGiveReceiveCount(12);
        self::getEntityManager()->persist($cardStat2);

        self::getEntityManager()->flush();
    }

    public function testFindByReceiveCountShouldReturnMatchingEntities(): void
    {
        $cardStat = $this->createCardStat(15, 7, 2);
        self::getEntityManager()->persist($cardStat);
        self::getEntityManager()->flush();

        $criteria = ['receiveCount' => 15];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(1, count($result));
        foreach ($result as $stat) {
            $this->assertEquals(15, $stat->getReceiveCount());
        }
    }

    public function testFindByCardAssociationShouldReturnMatchingEntities(): void
    {
        $cardStat = $this->createCardStat(18, 9, 4);
        self::getEntityManager()->persist($cardStat);
        self::getEntityManager()->flush();

        $criteria = ['card' => $this->testCard];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertContainsOnlyInstancesOf(CardStat::class, $result);
    }

    public function testFindByStatsDateShouldReturnMatchingEntities(): void
    {
        $statsDate = new \DateTimeImmutable('2024-01-01');
        $cardStat = $this->createCardStat(20, 12, 4);
        $cardStat->setStatsDate($statsDate);
        self::getEntityManager()->persist($cardStat);
        self::getEntityManager()->flush();

        $criteria = ['statsDate' => $statsDate];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertContainsOnlyInstancesOf(CardStat::class, $result);
    }

    public function testFindByWithLimitAndOffsetShouldRespectPagination(): void
    {
        $cardStat = $this->createCardStat(15, 7, 3);
        self::getEntityManager()->persist($cardStat);
        self::getEntityManager()->flush();

        $criteria = [];
        $orderBy = null;
        $limit = 1;
        $offset = 0;

        $result = $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
        $this->assertCount(1, $result);
    }

    public function testCountWithCriteriaShouldReturnMatchingNumber(): void
    {
        $initialCount = $this->getRepository()->count(['useCount' => 10]);

        $cardStat = $this->createCardStat(20, 10, 5);
        self::getEntityManager()->persist($cardStat);
        self::getEntityManager()->flush();

        $criteria = ['useCount' => 10];
        $result = $this->getRepository()->count($criteria);
        $this->assertEquals($initialCount + 1, $result);
    }

    public function testSaveWithoutFlushShouldOnlyPersistEntity(): void
    {
        $cardStat = $this->createCardStat(20, 10, 4);

        $initialCount = $this->getRepository()->count();

        $this->getRepository()->save($cardStat, false);

        $this->assertEquals($initialCount, $this->getRepository()->count());

        self::getEntityManager()->flush();
        $this->assertEquals($initialCount + 1, $this->getRepository()->count());
    }

    public function testRemoveWithoutFlushShouldOnlyRemoveEntity(): void
    {
        $cardStat = $this->createCardStat(10, 5, 2);
        self::getEntityManager()->persist($cardStat);
        self::getEntityManager()->flush();

        $initialCount = $this->getRepository()->count();

        $this->getRepository()->remove($cardStat, false);

        $this->assertEquals($initialCount, $this->getRepository()->count());

        self::getEntityManager()->flush();
        $this->assertEquals($initialCount - 1, $this->getRepository()->count());
    }

    private function createCardStat(int $receiveCount, int $useCount, int $giveCount): CardStat
    {
        $cardStat = new CardStat();
        $cardStat->setCard($this->testCard);
        $cardStat->setStatsDate(new \DateTimeImmutable('2024-01-01'));
        $cardStat->setReceiveCount($receiveCount);
        $cardStat->setUseCount($useCount);
        $cardStat->setGiveCount($giveCount);
        $cardStat->setViewCount($receiveCount * 2);
        $cardStat->setNewFollowCount(intval($useCount / 2));
        $cardStat->setUnfollowCount(1);
        $cardStat->setGiveReceiveCount(intval($giveCount / 2));

        return $cardStat;
    }

    protected function createNewEntity(): object
    {
        $entity = new CardStat();
        $entity->setCard($this->testCard);
        $entity->setStatsDate(new \DateTimeImmutable('2024-01-01'));
        $entity->setReceiveCount(10);
        $entity->setUseCount(5);
        $entity->setGiveCount(2);
        $entity->setViewCount(20);
        $entity->setNewFollowCount(2);
        $entity->setUnfollowCount(1);
        $entity->setGiveReceiveCount(1);

        return $entity;
    }

    protected function getRepository(): CardStatRepository
    {
        return self::getService(CardStatRepository::class);
    }
}
