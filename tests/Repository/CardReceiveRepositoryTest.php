<?php

namespace WechatOfficialAccountCardBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardCode;
use WechatOfficialAccountCardBundle\Entity\CardReceive;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;
use WechatOfficialAccountCardBundle\Repository\CardReceiveRepository;

/**
 * @internal
 */
#[CoversClass(CardReceiveRepository::class)]
#[RunTestsInSeparateProcesses]
final class CardReceiveRepositoryTest extends AbstractRepositoryTestCase
{
    private Account $testAccount;

    private Card $testCard;

    private CardCode $testCardCode;

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

        $this->testCardCode = new CardCode();
        $this->testCardCode->setCard($this->testCard);
        $this->testCardCode->setCode('TEST_CODE_' . uniqid());
        $this->testCardCode->setIsUsed(false);
        $this->testCardCode->setIsUnavailable(false);
        self::getEntityManager()->persist($this->testCardCode);

        // 创建一个基础的 CardReceive 实例用于父类的 count 测试
        $baseCardReceive = new CardReceive();
        $baseCardReceive->setCard($this->testCard);
        $baseCardReceive->setCardCode($this->testCardCode);
        $baseCardReceive->setOpenId('base_openid_' . uniqid());
        $baseCardReceive->setIsUsed(true);
        $baseCardReceive->setIsUnavailable(false);
        $baseCardReceive->setIsGiven(false);
        self::getEntityManager()->persist($baseCardReceive);

        self::getEntityManager()->flush();
    }

    public function testRepositoryExtendingServiceEntityRepository(): void
    {
        $repository = $this->getRepository();
        $this->assertInstanceOf(ServiceEntityRepository::class, $repository);
    }

    public function testFindOneByWithMatchingCriteriaAndOrderByShouldReturnEntity(): void
    {
        $cardReceive1 = new CardReceive();
        $cardReceive1->setCard($this->testCard);
        $cardReceive1->setCardCode($this->testCardCode);
        $cardReceive1->setOpenId('test002');
        $cardReceive1->setIsUsed(false);
        $cardReceive1->setIsUnavailable(false);
        $cardReceive1->setIsGiven(false);
        self::getEntityManager()->persist($cardReceive1);

        $cardReceive2 = new CardReceive();
        $cardReceive2->setCard($this->testCard);
        $cardReceive2->setCardCode($this->testCardCode);
        $cardReceive2->setOpenId('test001');
        $cardReceive2->setIsUsed(false);
        $cardReceive2->setIsUnavailable(false);
        $cardReceive2->setIsGiven(false);
        self::getEntityManager()->persist($cardReceive2);

        self::getEntityManager()->flush();

        $criteria = ['isUsed' => false];
        $orderBy = ['openId' => 'ASC'];

        $result = $this->getRepository()->findOneBy($criteria, $orderBy);
        $this->assertInstanceOf(CardReceive::class, $result);
        $this->assertEquals('test001', $result->getOpenId());
    }

    public function testFindByWithCardAssociationFieldCriteriaShouldReturnArrayOfEntities(): void
    {
        $cardReceive = new CardReceive();
        $cardReceive->setCard($this->testCard);
        $cardReceive->setCardCode($this->testCardCode);
        $cardReceive->setOpenId('openid001');
        $cardReceive->setIsUsed(false);
        $cardReceive->setIsUnavailable(false);
        $cardReceive->setIsGiven(false);
        self::getEntityManager()->persist($cardReceive);
        self::getEntityManager()->flush();

        $criteria = ['card' => $this->testCard];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertContainsOnlyInstancesOf(CardReceive::class, $result);
    }

    public function testFindByWithNullValueCriteriaShouldReturnArrayOfEntities(): void
    {
        $cardReceive = new CardReceive();
        $cardReceive->setCard($this->testCard);
        $cardReceive->setCardCode($this->testCardCode);
        $cardReceive->setOpenId('openid001');
        $cardReceive->setIsUsed(false);
        $cardReceive->setIsUnavailable(false);
        $cardReceive->setIsGiven(false);
        self::getEntityManager()->persist($cardReceive);
        self::getEntityManager()->flush();

        $criteria = ['usedAt' => null];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertContainsOnlyInstancesOf(CardReceive::class, $result);
    }

    public function testCountWithCardAssociationFieldCriteriaShouldReturnCorrectNumber(): void
    {
        $initialCount = $this->getRepository()->count(['card' => $this->testCard]);

        $cardReceive = new CardReceive();
        $cardReceive->setCard($this->testCard);
        $cardReceive->setCardCode($this->testCardCode);
        $cardReceive->setOpenId('count_test_' . uniqid());
        $cardReceive->setIsUsed(false);
        $cardReceive->setIsUnavailable(false);
        $cardReceive->setIsGiven(false);
        self::getEntityManager()->persist($cardReceive);
        self::getEntityManager()->flush();

        $criteria = ['card' => $this->testCard];
        $result = $this->getRepository()->count($criteria);
        $this->assertEquals($initialCount + 1, $result);
    }

    public function testSaveWithoutFlushShouldOnlyPersistEntity(): void
    {
        $cardReceive = new CardReceive();
        $cardReceive->setCard($this->testCard);
        $cardReceive->setCardCode($this->testCardCode);
        $cardReceive->setOpenId('NEW_OPENID_' . uniqid());
        $cardReceive->setIsUsed(false);
        $cardReceive->setIsUnavailable(false);
        $cardReceive->setIsGiven(false);

        $initialCount = $this->getRepository()->count();

        $this->getRepository()->save($cardReceive, false);

        $this->assertEquals($initialCount, $this->getRepository()->count());

        self::getEntityManager()->flush();
        $this->assertEquals($initialCount + 1, $this->getRepository()->count());
    }

    public function testRemoveWithoutFlushShouldOnlyRemoveEntity(): void
    {
        $cardReceive = new CardReceive();
        $cardReceive->setCard($this->testCard);
        $cardReceive->setCardCode($this->testCardCode);
        $cardReceive->setOpenId('TO_DELETE_NO_FLUSH_' . uniqid());
        $cardReceive->setIsUsed(false);
        $cardReceive->setIsUnavailable(false);
        $cardReceive->setIsGiven(false);
        self::getEntityManager()->persist($cardReceive);
        self::getEntityManager()->flush();

        $initialCount = $this->getRepository()->count();

        $this->getRepository()->remove($cardReceive, false);

        $this->assertEquals($initialCount, $this->getRepository()->count());

        self::getEntityManager()->flush();
        $this->assertEquals($initialCount - 1, $this->getRepository()->count());
    }

    protected function createNewEntity(): object
    {
        $entity = new CardReceive();
        $entity->setCard($this->testCard);
        $entity->setCardCode($this->testCardCode);
        $entity->setOpenId('TEST_OPENID_' . uniqid());
        $entity->setIsUsed(false);
        $entity->setIsUnavailable(false);
        $entity->setIsGiven(false);

        return $entity;
    }

    protected function getRepository(): CardReceiveRepository
    {
        return self::getService(CardReceiveRepository::class);
    }
}
