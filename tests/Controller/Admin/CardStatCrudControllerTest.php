<?php

declare(strict_types=1);

namespace WechatOfficialAccountCardBundle\Tests\Controller\Admin;

use Doctrine\DBAL\Exception\TableNotFoundException;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\Security\Core\User\UserInterface;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Controller\Admin\CardStatCrudController;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardStat;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;

/**
 * CardStatCrudController 单元测试
 * 测试重点：EasyAdmin配置、字段配置、卡券统计数据管理功能验证
 * @internal
 */
#[CoversClass(CardStatCrudController::class)]
#[RunTestsInSeparateProcesses]
class CardStatCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    private ?CardStat $testCardStat = null;


    /**
     * 创建已认证的客户端并初始化测试数据
     */
    private function createTestClient(): KernelBrowser
    {
        // 创建认证客户端（会清理数据库）
        $client = $this->createAuthenticatedClient();

        // 尝试确保测试数据存在
        try {
            $this->ensureTestDataExists();
        } catch (\Throwable $e) {
            // 如果无法创建测试数据（如数据库表不存在），静默忽略
            // 大多数配置类测试不依赖实际数据
        }

        return $client;
    }


    /**
     * 在管理员登录后立即补充测试数据，确保列表页渲染出表头
     *
     * 这个方法会被基类的 testIndexPageShowsConfiguredColumns 调用，
     * 通过在认证后立即创建数据来解决 "No table headers found" 错误
     */
    protected function loginAsAdmin(
        KernelBrowser $client,
        string $username = 'admin@test.com',
        string $password = 'password123'
    ): UserInterface {
        $user = parent::loginAsAdmin($client, $username, $password);

        // 认证完成后，立即确保测试数据存在
        // 这样基类的 testIndexPageShowsConfiguredColumns 测试就能找到表格头
        try {
            $this->ensureTestDataExists();
        } catch (\Throwable $e) {
            // 如果无法创建数据，跳过依赖数据的测试
            // 但不影响配置类测试的执行
            error_log('Warning: CardStat 测试数据创建失败，某些测试可能会失败: ' . $e->getMessage());
        }

        return $user;
    }

    protected function getControllerService(): CardStatCrudController
    {
        // 尝试确保测试数据存在
        try {
            $this->ensureTestDataExists();
        } catch (\Throwable $e) {
            // 如果无法创建测试数据（如数据库表不存在），静默忽略
            // 配置类测试不依赖实际数据
        }

        return self::getService(CardStatCrudController::class);
    }

    /**
     * 重写基类的认证客户端创建方法，确保在测试INDEX页面前有测试数据
     *
     * 注意：这个方法专门为了解决基类 testIndexPageShowsConfiguredColumns 测试失败问题
     * 该测试期望页面有表格头，但没有数据时 EasyAdmin 不显示表格头
     */
    protected function createTestClientWithData(): KernelBrowser
    {
        // 先创建认证客户端（会清理数据库）
        $client = $this->createAuthenticatedClient();

        // 立即创建测试数据，确保 INDEX 页面有内容显示
        try {
            $this->ensureTestDataExists();
        } catch (\Throwable $e) {
            // 如果无法创建数据，跳过相关测试
            self::markTestSkipped('CardStat 测试数据无法初始化，跳过 INDEX 页面测试：' . $e->getMessage());
        }

        return $client;
    }

    private function createTestData(): CardStat
    {
        // 避免重复创建
        if (null !== $this->testCardStat && 0 !== $this->testCardStat->getId()) {
            return $this->testCardStat;
        }

        $entityManager = self::getEntityManager();

        // 创建Account
        $accountRepository = $entityManager->getRepository(Account::class);
        $account = $accountRepository->findOneBy([]);

        if (null === $account) {
            $account = new Account();
            $account->setAppId('test_app_id');
            $account->setAppSecret('test_app_secret');
            $account->setName('Test Account');
            $entityManager->persist($account);
            $entityManager->flush();
        }

        // 创建Card
        $cardRepository = $entityManager->getRepository(Card::class);
        $card = $cardRepository->findOneBy(['cardId' => 'test_card_123']);

        if (null === $card) {
            $card = new Card();
            $card->setAccount($account);
            $card->setCardId('test_card_123');
            $card->setCardType(CardType::GENERAL_COUPON);
            $card->setStatus(CardStatus::VERIFY_OK);
            // 设置syncing为true以跳过EventListener的API调用
            $card->setSyncing(true);

            // 设置CardBaseInfo
            $baseInfo = $card->getBaseInfo();
            $baseInfo->setLogoUrl('https://example.com/logo.png');
            $baseInfo->setBrandName('测试商户');
            $baseInfo->setCodeType(CodeType::CODE_TYPE_QRCODE);
            $baseInfo->setTitle('测试卡券');
            $baseInfo->setColor(CardColor::COLOR_010);
            $baseInfo->setNotice('测试提醒');
            $baseInfo->setDescription('测试说明');
            $baseInfo->setQuantity(100);
            $baseInfo->setUseLimit(1);
            $baseInfo->setGetLimit(1);

            $entityManager->persist($card);
            $entityManager->flush();
        }

        // 创建CardStat
        $stat = new CardStat();
        $stat->setCard($card);
        $stat->setStatsDate(new \DateTimeImmutable('2024-01-01'));
        $stat->setReceiveCount(10);
        $stat->setUseCount(5);
        $stat->setGiveCount(2);
        $stat->setViewCount(20);
        $stat->setNewFollowCount(3);
        $stat->setUnfollowCount(1);
        $stat->setGiveReceiveCount(1);

        $entityManager->persist($stat);
        $entityManager->flush();

        // 验证实体已正确保存并有ID
        $this->assertNotEquals(0, $stat->getId(), 'CardStat should have an ID after flush');

        $this->testCardStat = $stat;

        return $stat;
    }

    public function testGetEntityFqcnReturnsCorrectClass(): void
    {
        $this->assertSame(CardStat::class, CardStatCrudController::getEntityFqcn());
    }

    public function testConfigureFieldsReturnsExpectedFields(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_INDEX));

        $this->assertNotEmpty($fields, 'Index page should have fields configured');
        $this->assertGreaterThanOrEqual(3, count($fields), 'Index page should have at least 3 fields');

        // 验证字段配置对象类型正确
        foreach ($fields as $field) {
            $this->assertInstanceOf(FieldInterface::class, $field, 'Each field should implement FieldInterface');
        }
    }

    public function testFieldsIncludeRequiredCardStatFields(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_DETAIL));

        $fieldClasses = array_map(function ($field): string {
            return is_object($field) ? get_class($field) : '';
        }, $fields);

        $fieldClasses = array_filter($fieldClasses, static fn ($class): bool => '' !== $class);

        // 验证包含卡券统计数据管理的关键字段类型（DETAIL页面显示所有字段）
        $this->assertContains(IdField::class, $fieldClasses, 'Should include ID field');
        $this->assertContains(AssociationField::class, $fieldClasses, 'Should include Association field for card');
        $this->assertContains(DateField::class, $fieldClasses, 'Should include Date field for stats date');
        $this->assertContains(IntegerField::class, $fieldClasses, 'Should include Integer fields for statistics counts');
        $this->assertContains(DateTimeField::class, $fieldClasses, 'Should include DateTime fields');
    }

    public function testStatisticsFieldsConfiguration(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_DETAIL));

        $integerFields = array_filter($fields, function ($field) {
            return $field instanceof IntegerField;
        });

        // 验证包含统计数据的整数字段（只在DETAIL页面显示）
        $this->assertNotEmpty($integerFields, 'Should have integer fields for statistics');
        $this->assertGreaterThanOrEqual(7, count($integerFields), 'Should have at least 7 integer fields for various statistics');
    }

    public function testStatisticsFieldsPresent(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_DETAIL));

        $fieldNames = [];
        foreach ($fields as $field) {
            if (is_object($field)) {
                $dto = $field->getAsDto();
                $fieldNames[] = $dto->getProperty();
            }
        }

        // 验证统计数据字段存在
        $statisticsFields = [
            'receiveCount',      // 领取次数
            'useCount',          // 使用次数
            'giveCount',         // 转赠次数
            'viewCount',         // 浏览次数
            'newFollowCount',    // 新增关注数
            'unfollowCount',     // 取消关注数
            'giveReceiveCount',   // 转赠领取数
        ];

        foreach ($statisticsFields as $statisticsField) {
            $this->assertContains($statisticsField, $fieldNames, "Statistics field '{$statisticsField}' should be present in card stat configuration");
        }
    }

    public function testDateFieldConfiguration(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_INDEX));

        $dateFields = array_filter($fields, function ($field) {
            return $field instanceof DateField;
        });

        // 验证包含日期字段（统计日期）
        $this->assertNotEmpty($dateFields, 'Should have date field for statistics date');
        $this->assertSameSize([1], $dateFields, 'Should have exactly 1 date field for stats date');
    }

    public function testFieldConfigurationForDifferentPages(): void
    {
        $controller = $this->getControllerService();
        $pages = [Crud::PAGE_INDEX, Crud::PAGE_NEW, Crud::PAGE_EDIT, Crud::PAGE_DETAIL];

        foreach ($pages as $page) {
            $fields = iterator_to_array($controller->configureFields($page));
            $this->assertNotEmpty($fields, "Fields should not be empty for page: {$page}");

            // 验证每个字段都是有效的EasyAdmin字段对象
            foreach ($fields as $field) {
                $this->assertInstanceOf(FieldInterface::class, $field);
            }
        }
    }

    public function testFormPagesHaveRequiredFields(): void
    {
        $controller = $this->getControllerService();
        $newFields = iterator_to_array($controller->configureFields(Crud::PAGE_NEW));
        $editFields = iterator_to_array($controller->configureFields(Crud::PAGE_EDIT));

        // 验证新建和编辑页面都有字段配置
        $this->assertNotEmpty($newFields, 'New page should have fields');
        $this->assertNotEmpty($editFields, 'Edit page should have fields');

        // 验证表单页面的字段数量相同（配置应该一致）
        $this->assertSameSize($newFields, $editFields, 'New and Edit pages should have same number of fields');
    }

    public function testDetailPageShowsAllInformation(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_DETAIL));

        $this->assertNotEmpty($fields, 'Detail page should have fields configured');

        $fieldClasses = array_map(function ($field): string {
            return is_object($field) ? get_class($field) : '';
        }, $fields);

        $fieldClasses = array_filter($fieldClasses, static fn ($class): bool => '' !== $class);

        // 详情页应该显示日期时间字段（创建时间、更新时间）
        $dateTimeFieldCount = count(array_filter($fieldClasses, fn ($class) => DateTimeField::class === $class));
        $this->assertGreaterThanOrEqual(2, $dateTimeFieldCount, 'Detail page should show datetime fields');
    }

    public function testControllerImplementsCorrectInterface(): void
    {
        $controller = $this->getControllerService();
        $this->assertInstanceOf(
            AbstractCrudController::class,
            $controller
        );
    }

    public function testCardStatSpecificFieldsArePresent(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_DETAIL));

        $fieldNames = [];
        foreach ($fields as $field) {
            if (is_object($field)) {
                $dto = $field->getAsDto();
                $fieldNames[] = $dto->getProperty();
            }
        }

        // 验证卡券统计特有的字段存在
        $expectedFields = [
            'id',
            'card',              // 关联卡券
            'statsDate',         // 统计日期
            'receiveCount',      // 领取次数
            'useCount',          // 使用次数
            'giveCount',         // 转赠次数
            'viewCount',         // 浏览次数
            'newFollowCount',    // 新增关注数
            'unfollowCount',     // 取消关注数
            'giveReceiveCount',  // 转赠领取数
            'createTime',         // 创建时间
            'updateTime',          // 更新时间
        ];

        foreach ($expectedFields as $expectedField) {
            $this->assertContains($expectedField, $fieldNames, "Field '{$expectedField}' should be present in card stat configuration");
        }
    }

    public function testIntegerFieldsHaveValidConstraints(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_DETAIL));

        $integerFields = array_filter($fields, function ($field) {
            return $field instanceof IntegerField;
        });

        // 验证包含统计数量相关的整数字段（只在DETAIL页面显示）
        $this->assertNotEmpty($integerFields, 'Should have integer fields for statistical counts');

        // 验证至少有7个整数字段用于各种统计数据
        $this->assertGreaterThanOrEqual(7, count($integerFields), 'Should have at least 7 integer fields for comprehensive statistics');
    }

    public function testAssociationFieldConfiguration(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_INDEX));

        $associationFields = array_filter($fields, function ($field) {
            return $field instanceof AssociationField;
        });

        // 验证包含关联字段（关联卡券）
        $this->assertNotEmpty($associationFields, 'Should have association field for card');
        $this->assertSameSize([1], $associationFields, 'Should have exactly 1 association field for card');
    }

    public function testStatisticsDateFieldPresent(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_INDEX));

        $fieldNames = [];
        foreach ($fields as $field) {
            if (is_object($field)) {
                $dto = $field->getAsDto();
                $fieldNames[] = $dto->getProperty();
            }
        }

        // 验证统计日期字段存在
        $this->assertContains('statsDate', $fieldNames, 'Statistics date field should be present for date-based analytics');
    }

    public function testComprehensiveStatisticsTracking(): void
    {
        $controller = $this->getControllerService();
        $fields = iterator_to_array($controller->configureFields(Crud::PAGE_DETAIL));

        $fieldNames = [];
        foreach ($fields as $field) {
            if (is_object($field)) {
                $dto = $field->getAsDto();
                $fieldNames[] = $dto->getProperty();
            }
        }

        // 验证涵盖卡券全生命周期的统计字段
        $lifecycleFields = [
            'receiveCount',      // 领取统计
            'useCount',          // 使用统计
            'giveCount',         // 转赠统计
            'viewCount',         // 浏览统计
            'newFollowCount',    // 获客效果
            'unfollowCount',     // 流失统计
            'giveReceiveCount',   // 转赠传播效果
        ];

        foreach ($lifecycleFields as $lifecycleField) {
            $this->assertContains($lifecycleField, $fieldNames, "Lifecycle tracking field '{$lifecycleField}' should be present for comprehensive analytics");
        }
    }

    /**
     * 确保测试数据存在 - 幂等操作，可以多次调用
     */
    private function ensureTestDataExists(): void
    {
        try {
            if (self::hasDoctrineSupport()) {
                $em = self::getEntityManager();
                // 检查数据库是否已经有表（已初始化）
                try {
                    $count = $em->getRepository(CardStat::class)->count([]);
                    if ($count > 0) {
                        return; // 数据已存在，直接返回
                    }

                    // 创建测试数据
                    $this->testCardStat = null;
                    $this->createTestData();

                    // 验证数据被创建
                    $newCount = $em->getRepository(CardStat::class)->count([]);
                    if (0 === $newCount) {
                        throw new \RuntimeException('Failed to create test data');
                    }
                } catch (TableNotFoundException $e) {
                    // 数据库还未初始化，跳过数据创建
                    // 这些测试不需要实际数据
                    // 抛出异常让调用者知道无法创建数据
                    throw new \Exception('Database tables not initialized: ' . $e->getMessage(), 0, $e);
                }
            }
        } catch (\Throwable $e) {
            // 如果创建失败，抛出异常让调用者处理
            throw $e;
        }
    }

    /**
     * 测试INDEX页面显示正确的列
     */
    public function testIndexPageShowsCorrectColumns(): void
    {
        $client = $this->createTestClient();

        $crawler = $client->request('GET', $this->generateAdminUrl(Action::INDEX));
        $this->assertResponseIsSuccessful();

        // 验证页面包含期望的列标题
        $expectedHeaders = ['ID', '关联卡券', '统计日期', '创建时间', '更新时间'];

        $pageContent = $crawler->html();
        foreach ($expectedHeaders as $header) {
            $this->assertStringContainsString($header, $pageContent, "页面应该包含列标题: {$header}");
        }
    }

    /**
     * @return \Generator<string, array{string}>
     *
     * 注意：基类的 testIndexPageShowsConfiguredColumns 测试会失败，因为它在调用
     * createAuthenticatedClient() 后清理了数据库，而我们无法在测试运行期间重新创建数据。
     *
     * 我们改用自己的 testIndexPageShowsCorrectColumns 方法，它使用 createTestClient()
     * 在数据库清理后立即创建测试数据。
     *
     * 这里提供的数据集主要是为了让基类能够验证字段配置的一致性。
     */
    public static function provideIndexPageHeaders(): \Generator
    {
        // 注意：基类的 testIndexPageShowsConfiguredColumns 测试在 CardStat 场景下会失败，
        // 因为：
        // 1. CardStatCrudController 禁用了 NEW 操作（统计数据是系统生成的）
        // 2. 基类测试会清理数据库，导致没有数据显示
        // 3. EasyAdmin 在没有数据时不显示表格头，导致测试失败
        //
        // 解决方案：我们提供了自定义的 testIndexPageShowsCorrectColumns 方法，
        // 它使用 createTestClient() 确保有测试数据。
        //
        // 为了避免基类测试干扰，这里返回空的数据集跳过基类测试。
        // 自定义测试已经覆盖了相同的测试场景。

        // 提供正常的数据，测试会失败，但我们有自定义的测试覆盖相同功能
        yield 'ID' => ['ID'];
        yield '关联卡券' => ['关联卡券'];
        yield '统计日期' => ['统计日期'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }


    // 统计数据只读，不支持新建和编辑功能
    // 新建和编辑页面已被禁用

    /** @return \Generator<string, array{string}> */
    public static function provideNewPageFields(): \Generator
    {
        // 统计数据不支持新建，但需要提供非空数据集以避免PHPUnit错误
        // 基类会检查 isActionEnabled 来决定是否跳过测试
        yield 'skipped' => ['__SKIPPED__'];
    }

    /** @return \Generator<string, array{string}> */
    public static function provideEditPageFields(): \Generator
    {
        // 统计数据不支持编辑，但需要提供非空数据集以避免PHPUnit错误
        // 基类会检查 isActionEnabled 来决定是否跳过测试
        yield 'skipped' => ['__SKIPPED__'];
    }

    // 统计数据只读，不需要验证错误测试
    // 验证错误测试已被禁用，因为新建功能已被禁用
}
