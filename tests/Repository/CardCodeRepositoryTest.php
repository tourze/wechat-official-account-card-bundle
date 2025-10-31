<?php

namespace WechatOfficialAccountCardBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardCode;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;
use WechatOfficialAccountCardBundle\Repository\CardCodeRepository;

/**
 * @internal
 */
#[CoversClass(CardCodeRepository::class)]
#[RunTestsInSeparateProcesses]
final class CardCodeRepositoryTest extends AbstractRepositoryTestCase
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

        // 创建一些基础的CardCode记录来满足DataFixtures测试的要求
        // 使用Z开头的代码确保不会干扰其他测试的排序
        $cardCode1 = new CardCode();
        $cardCode1->setCard($this->testCard);
        $cardCode1->setCode('ZFIXTURE_CODE_001');
        $cardCode1->setIsUsed(false);
        $cardCode1->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode1);

        $cardCode2 = new CardCode();
        $cardCode2->setCard($this->testCard);
        $cardCode2->setCode('ZFIXTURE_CODE_002');
        $cardCode2->setIsUsed(true);
        $cardCode2->setUsedAt(new \DateTimeImmutable());
        $cardCode2->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode2);

        $cardCode3 = new CardCode();
        $cardCode3->setCard($this->testCard);
        $cardCode3->setCode('ZFIXTURE_CODE_003');
        $cardCode3->setIsUsed(false);
        $cardCode3->setIsUnavailable(true);
        $cardCode3->setUnavailableAt(new \DateTimeImmutable());
        self::getEntityManager()->persist($cardCode3);

        self::getEntityManager()->flush();
    }

    public function testRepositoryExtendingServiceEntityRepository(): void
    {
        $repository = $this->getRepository();
        $this->assertInstanceOf(ServiceEntityRepository::class, $repository);
    }

    public function testFindOneByWithMatchingCriteriaAndOrderByShouldReturnEntity(): void
    {
        $cardCode1 = new CardCode();
        $cardCode1->setCard($this->testCard);
        $cardCode1->setCode('AAA_TEST_002');
        $cardCode1->setIsUsed(false);
        $cardCode1->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode1);

        $cardCode2 = new CardCode();
        $cardCode2->setCard($this->testCard);
        $cardCode2->setCode('AAA_TEST_001');
        $cardCode2->setIsUsed(false);
        $cardCode2->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode2);

        self::getEntityManager()->flush();

        $criteria = ['isUsed' => false];
        $orderBy = ['code' => 'ASC'];

        $result = $this->getRepository()->findOneBy($criteria, $orderBy);
        $this->assertInstanceOf(CardCode::class, $result);
        $this->assertEquals('AAA_TEST_001', $result->getCode());
    }

    public function testFindByWithCardAssociationCriteriaShouldReturnArrayOfEntities(): void
    {
        $cardCode = new CardCode();
        $cardCode->setCard($this->testCard);
        $cardCode->setCode('CODE001');
        $cardCode->setIsUsed(false);
        $cardCode->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode);
        self::getEntityManager()->flush();

        $criteria = ['card' => $this->testCard];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertContainsOnlyInstancesOf(CardCode::class, $result);
    }

    public function testFindByWithNullUsedAtCriteriaShouldReturnArrayOfEntities(): void
    {
        $cardCode = new CardCode();
        $cardCode->setCard($this->testCard);
        $cardCode->setCode('CODE001');
        $cardCode->setIsUsed(false);
        $cardCode->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode);
        self::getEntityManager()->flush();

        $criteria = ['usedAt' => null];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertContainsOnlyInstancesOf(CardCode::class, $result);
    }

    public function testCountWithCardAssociationCriteriaShouldReturnCorrectNumber(): void
    {
        $initialCount = $this->getRepository()->count(['card' => $this->testCard]);

        $cardCode = new CardCode();
        $cardCode->setCard($this->testCard);
        $cardCode->setCode('COUNT_TEST_' . uniqid());
        $cardCode->setIsUsed(false);
        $cardCode->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode);
        self::getEntityManager()->flush();

        $criteria = ['card' => $this->testCard];
        $result = $this->getRepository()->count($criteria);
        $this->assertEquals($initialCount + 1, $result);
    }

    public function testCountWithNullUsedAtCriteriaShouldReturnCorrectNumber(): void
    {
        $initialCount = $this->getRepository()->count(['usedAt' => null]);

        $cardCode = new CardCode();
        $cardCode->setCard($this->testCard);
        $cardCode->setCode('NULL_TEST_' . uniqid());
        $cardCode->setIsUsed(false);
        $cardCode->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode);
        self::getEntityManager()->flush();

        $criteria = ['usedAt' => null];
        $result = $this->getRepository()->count($criteria);
        $this->assertEquals($initialCount + 1, $result);
    }

    public function testSaveWithoutFlushShouldOnlyPersistEntity(): void
    {
        $cardCode = new CardCode();
        $cardCode->setCard($this->testCard);
        $cardCode->setCode('NEW_CODE_' . uniqid());
        $cardCode->setIsUsed(false);
        $cardCode->setIsUnavailable(false);

        $initialCount = $this->getRepository()->count();

        $this->getRepository()->save($cardCode, false);

        $this->assertEquals($initialCount, $this->getRepository()->count());

        self::getEntityManager()->flush();
        $this->assertEquals($initialCount + 1, $this->getRepository()->count());
    }

    public function testRemoveWithoutFlushShouldOnlyRemoveEntity(): void
    {
        $cardCode = new CardCode();
        $cardCode->setCard($this->testCard);
        $cardCode->setCode('TO_DELETE_NO_FLUSH_' . uniqid());
        $cardCode->setIsUsed(false);
        $cardCode->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode);
        self::getEntityManager()->flush();

        $initialCount = $this->getRepository()->count();

        $this->getRepository()->remove($cardCode, false);

        $this->assertEquals($initialCount, $this->getRepository()->count());

        self::getEntityManager()->flush();
        $this->assertEquals($initialCount - 1, $this->getRepository()->count());
    }

    public function testFindOneByCardAssociationShouldReturnMatchingEntity(): void
    {
        $cardCode = new CardCode();
        $cardCode->setCard($this->testCard);
        $cardCode->setCode('CARD_ENTITY_' . uniqid());
        $cardCode->setIsUsed(false);
        $cardCode->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode);
        self::getEntityManager()->flush();

        $criteria = ['card' => $this->testCard];

        $result = $this->getRepository()->findOneBy($criteria);
        $this->assertInstanceOf(CardCode::class, $result);
        $this->assertEquals($this->testCard->getId(), $result->getCard()->getId());
    }

    public function testCountByCardAssociationShouldReturnCorrectNumber(): void
    {
        $initialCount = $this->getRepository()->count(['card' => $this->testCard]);

        $cardCode1 = new CardCode();
        $cardCode1->setCard($this->testCard);
        $cardCode1->setCode('COUNT_CARD_1_' . uniqid());
        $cardCode1->setIsUsed(false);
        $cardCode1->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode1);

        $cardCode2 = new CardCode();
        $cardCode2->setCard($this->testCard);
        $cardCode2->setCode('COUNT_CARD_2_' . uniqid());
        $cardCode2->setIsUsed(false);
        $cardCode2->setIsUnavailable(false);
        self::getEntityManager()->persist($cardCode2);

        self::getEntityManager()->flush();

        $criteria = ['card' => $this->testCard];
        $result = $this->getRepository()->count($criteria);
        $this->assertEquals($initialCount + 2, $result);
    }

    protected function createNewEntity(): object
    {
        $entity = new CardCode();
        $entity->setCard($this->testCard);
        $entity->setCode('TEST_CODE_' . uniqid());
        $entity->setIsUsed(false);
        $entity->setIsUnavailable(false);

        return $entity;
    }

    protected function getRepository(): CardCodeRepository
    {
        return self::getService(CardCodeRepository::class);
    }
}
