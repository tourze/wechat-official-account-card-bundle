<?php

namespace WechatOfficialAccountCardBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\Embed\CardBaseInfo;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;
use WechatOfficialAccountCardBundle\Repository\CardRepository;

/**
 * @internal
 */
#[CoversClass(CardRepository::class)]
#[RunTestsInSeparateProcesses]
final class CardRepositoryTest extends AbstractRepositoryTestCase
{
    private Account $testAccount;

    protected function onSetUp(): void
    {
        $this->testAccount = new Account();
        $this->testAccount->setAppId('test_app_id_' . uniqid());
        $this->testAccount->setAppSecret('test_secret');
        $this->testAccount->setName('Test Account');
        self::getEntityManager()->persist($this->testAccount);

        // 创建基础测试数据用于父类的 count 测试
        $baseCard = $this->createCard('base_card_' . uniqid(), CardType::GROUPON, CardStatus::VERIFY_OK);
        self::getEntityManager()->persist($baseCard);

        self::getEntityManager()->flush();
    }

    public function testFindByCardIdShouldReturnMatchingCards(): void
    {
        $cardId = 'wx_card_test_' . uniqid();
        $card = $this->createCard($cardId, CardType::DISCOUNT, CardStatus::VERIFY_OK);
        self::getEntityManager()->persist($card);
        self::getEntityManager()->flush();

        $criteria = ['cardId' => $cardId];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(1, count($result));
        $this->assertEquals($cardId, $result[0]->getCardId());
    }

    public function testFindByCardTypeShouldReturnMatchingCards(): void
    {
        $card1 = $this->createCard('card_groupon_1_' . uniqid(), CardType::GROUPON, CardStatus::VERIFY_OK);
        $card2 = $this->createCard('card_groupon_2_' . uniqid(), CardType::GROUPON, CardStatus::VERIFY_OK);
        self::getEntityManager()->persist($card1);
        self::getEntityManager()->persist($card2);
        self::getEntityManager()->flush();

        $criteria = ['cardType' => CardType::GROUPON];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(2, count($result));
        foreach ($result as $resultCard) {
            $this->assertEquals(CardType::GROUPON, $resultCard->getCardType());
        }
    }

    public function testFindByStatusShouldReturnMatchingCards(): void
    {
        $card = $this->createCard('card_verify_ok_' . uniqid(), CardType::CASH, CardStatus::VERIFY_OK);
        self::getEntityManager()->persist($card);
        self::getEntityManager()->flush();

        $criteria = ['status' => CardStatus::VERIFY_OK];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(1, count($result));
        foreach ($result as $resultCard) {
            $this->assertEquals(CardStatus::VERIFY_OK, $resultCard->getStatus());
        }
    }

    public function testFindByAccountShouldReturnMatchingCards(): void
    {
        $card = $this->createCard('card_account_test_' . uniqid(), CardType::GIFT, CardStatus::VERIFY_OK, $this->testAccount);
        self::getEntityManager()->persist($card);
        self::getEntityManager()->flush();

        $criteria = ['account' => $this->testAccount];

        $result = $this->getRepository()->findBy($criteria);
        $this->assertGreaterThanOrEqual(1, count($result));
        foreach ($result as $resultCard) {
            $this->assertEquals($this->testAccount->getId(), $resultCard->getAccount()->getId());
        }
    }

    public function testCountWithCriteriaShouldReturnMatchingNumber(): void
    {
        $initialCount = $this->getRepository()->count(['status' => CardStatus::VERIFY_OK]);

        $card = $this->createCard('count_test_' . uniqid(), CardType::CASH, CardStatus::VERIFY_OK);
        self::getEntityManager()->persist($card);
        self::getEntityManager()->flush();

        $criteria = ['status' => CardStatus::VERIFY_OK];
        $result = $this->getRepository()->count($criteria);
        $this->assertEquals($initialCount + 1, $result);
    }

    public function testSaveWithoutFlushShouldOnlyPersistEntity(): void
    {
        $card = $this->createCard('card_to_persist_' . uniqid(), CardType::BOARDING_PASS, CardStatus::VERIFY_OK);

        $initialCount = $this->getRepository()->count();

        $this->getRepository()->save($card, false);

        $this->assertEquals($initialCount, $this->getRepository()->count());

        self::getEntityManager()->flush();
        $this->assertEquals($initialCount + 1, $this->getRepository()->count());
    }

    public function testRemoveWithoutFlushShouldOnlyRemoveEntity(): void
    {
        $card = $this->createCard('card_to_delete_' . uniqid(), CardType::BUS_TICKET, CardStatus::DELETE);
        self::getEntityManager()->persist($card);
        self::getEntityManager()->flush();

        $initialCount = $this->getRepository()->count();

        $this->getRepository()->remove($card, false);

        $this->assertEquals($initialCount, $this->getRepository()->count());

        self::getEntityManager()->flush();
        $this->assertEquals($initialCount - 1, $this->getRepository()->count());
    }

    public function testFindByWithLimitAndOffsetShouldRespectPagination(): void
    {
        $card = $this->createCard('paginated_card_' . uniqid(), CardType::GENERAL_COUPON, CardStatus::VERIFY_OK);
        self::getEntityManager()->persist($card);
        self::getEntityManager()->flush();

        $criteria = [];
        $orderBy = null;
        $limit = 1;
        $offset = 0;

        $result = $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
        $this->assertCount(1, $result);
    }

    private function createCard(
        string $cardId,
        CardType $cardType,
        CardStatus $status,
        ?Account $account = null,
    ): Card {
        $card = new Card();
        $card->setCardId($cardId);
        $card->setCardType($cardType);
        $card->setStatus($status);
        $card->setAccount($account ?? $this->testAccount);
        $card->setSyncing(true);

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
        $card->setBaseInfo($baseInfo);

        return $card;
    }

    protected function createNewEntity(): object
    {
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

        $entity = new Card();
        $entity->setAccount($this->testAccount);
        $entity->setCardId('TEST_CARD_ID_' . uniqid());
        $entity->setCardType(CardType::DISCOUNT);
        $entity->setStatus(CardStatus::VERIFY_OK);
        $entity->setSyncing(true);
        $entity->setBaseInfo($baseInfo);

        return $entity;
    }

    protected function getRepository(): CardRepository
    {
        return self::getService(CardRepository::class);
    }
}
