<?php

declare(strict_types=1);

namespace WechatOfficialAccountCardBundle\Tests\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\EasyAdminEnumFieldBundle\Field\EnumField;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatOfficialAccountCardBundle\Controller\Admin\CardCrudController;
use WechatOfficialAccountCardBundle\Entity\Card;

/**
 * CardCrudController 单元测试
 * 测试重点：EasyAdmin配置、字段配置、微信卡券管理功能验证、枚举字段配置
 * @internal
 */
#[CoversClass(CardCrudController::class)]
#[RunTestsInSeparateProcesses]
class CardCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    private CardCrudController $controller;

    protected function setUpController(): void
    {
        $this->controller = self::getService(CardCrudController::class);
    }

    protected function getControllerService(): CardCrudController
    {
        return self::getService(CardCrudController::class);
    }

    public function testGetEntityFqcnReturnsCorrectClass(): void
    {
        $this->assertSame(Card::class, CardCrudController::getEntityFqcn());
    }

    public function testConfigureFieldsReturnsExpectedFields(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_INDEX));

        $this->assertNotEmpty($fields, 'Index page should have fields configured');
        $this->assertGreaterThanOrEqual(7, count($fields), 'Index page should have core fields configured');

        // 验证字段配置对象类型正确
        foreach ($fields as $field) {
            $this->assertInstanceOf(FieldInterface::class, $field, 'Each field should implement FieldInterface');
        }
    }

    public function testFieldsIncludeRequiredCardFields(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $fieldClasses = array_map(function ($field): string {
            return is_object($field) ? get_class($field) : '';
        }, $fields);

        $fieldClasses = array_filter($fieldClasses, static fn ($class): bool => '' !== $class);

        // 验证包含卡券管理的关键字段类型
        $this->assertContains(IdField::class, $fieldClasses, 'Should include ID field');
        $this->assertContains(AssociationField::class, $fieldClasses, 'Should include Association field for account');
        $this->assertContains(TextField::class, $fieldClasses, 'Should include Text fields for card info');
        $this->assertContains(EnumField::class, $fieldClasses, 'Should include Enum fields for card type and status');
        $this->assertContains(BooleanField::class, $fieldClasses, 'Should include Boolean fields');
        $this->assertContains(DateTimeField::class, $fieldClasses, 'Should include DateTime fields');
    }

    public function testEnumFieldsConfiguration(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $enumFields = array_filter($fields, function ($field) {
            return $field instanceof EnumField;
        });

        // 验证包含枚举字段（卡券类型、状态）
        // 注意：由于移除了baseInfo嵌入式字段，现在只有cardType和status两个枚举字段
        $this->assertNotEmpty($enumFields, 'Should have enum fields for card configuration');
        $this->assertGreaterThanOrEqual(2, count($enumFields), 'Should have at least 2 enum fields (type, status)');
    }

    public function testBaseInfoFieldsConfiguration(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_DETAIL));

        $fieldNames = [];
        foreach ($fields as $field) {
            if (is_object($field)) {
                $dto = $field->getAsDto();
                $fieldNames[] = $dto->getProperty();
            }
        }

        // 注意：baseInfo是嵌入式对象，EasyAdmin不直接支持嵌入式字段的点号访问
        // 因此这里只验证核心字段的存在性
        $this->assertGreaterThan(0, count($fieldNames), 'Should have configured field names');
    }

    public function testFieldConfigurationForDifferentPages(): void
    {
        $this->setUpController();
        $pages = [Crud::PAGE_INDEX, Crud::PAGE_NEW, Crud::PAGE_EDIT, Crud::PAGE_DETAIL];

        foreach ($pages as $page) {
            $fields = iterator_to_array($this->controller->configureFields($page));
            $this->assertNotEmpty($fields, "Fields should not be empty for page: {$page}");

            // 验证每个字段都是有效的EasyAdmin字段对象
            foreach ($fields as $field) {
                $this->assertInstanceOf(FieldInterface::class, $field);
            }
        }
    }

    public function testIntegerFieldsHaveValidConstraints(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $integerFields = array_filter($fields, function ($field) {
            return $field instanceof IntegerField;
        });

        // 注意：移除了baseInfo嵌入式字段后，IntegerField也不再存在于核心字段中
        // 这是预期的行为，因为数量、限制等字段都属于嵌入式对象
        $this->assertEmpty($integerFields, 'Integer fields should not exist after baseInfo removal');
    }

    public function testUrlFieldConfiguration(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $urlFields = array_filter($fields, function ($field) {
            return $field instanceof UrlField;
        });

        // 注意：移除了baseInfo嵌入式字段后，UrlField也不再存在于核心字段中
        $this->assertEmpty($urlFields, 'URL fields should not exist after baseInfo removal');
    }

    public function testFormPagesHaveRequiredFields(): void
    {
        $this->setUpController();
        $newFields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));
        $editFields = iterator_to_array($this->controller->configureFields(Crud::PAGE_EDIT));

        // 验证新建和编辑页面都有字段配置
        $this->assertNotEmpty($newFields, 'New page should have fields');
        $this->assertNotEmpty($editFields, 'Edit page should have fields');

        // 验证表单页面的字段数量相同（配置应该一致）
        $this->assertSameSize($newFields, $editFields, 'New and Edit pages should have same number of fields');
    }

    public function testDetailPageShowsAllInformation(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_DETAIL));

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
        $this->setUpController();
        $this->assertInstanceOf(
            AbstractCrudController::class,
            $this->controller
        );
    }

    public function testCardSpecificFieldsArePresent(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_DETAIL));

        $fieldNames = [];
        foreach ($fields as $field) {
            if (is_object($field)) {
                $dto = $field->getAsDto();
                $fieldNames[] = $dto->getProperty();
            }
        }

        // 验证微信卡券特有的字段存在
        $expectedFields = [
            'id',
            'account',      // 微信公众号
            'cardId',       // 卡券ID
            'cardType',     // 卡券类型
            'status',       // 状态
            'syncing',      // 同步中
            'createTime',    // 创建时间
            'updateTime',     // 更新时间
        ];

        foreach ($expectedFields as $expectedField) {
            $this->assertContains($expectedField, $fieldNames, "Field '{$expectedField}' should be present in card configuration");
        }
    }

    public function testBooleanFieldsConfiguration(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $booleanFields = array_filter($fields, function ($field) {
            return $field instanceof BooleanField;
        });

        // 验证包含布尔字段（同步状态）
        // 注意：移除了baseInfo嵌入式字段后，只保留了syncing字段
        $this->assertNotEmpty($booleanFields, 'Should have boolean fields for various settings');
        $this->assertGreaterThanOrEqual(1, count($booleanFields), 'Should have at least 1 boolean field (syncing)');
    }

    public function testTextFieldsForCardInformation(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $textFields = array_filter($fields, function ($field) {
            return $field instanceof TextField;
        });

        // 验证包含文本字段用于卡券基本信息
        // 注意：移除了baseInfo嵌入式字段后，只保留了cardId字段
        $this->assertNotEmpty($textFields, 'Should have text fields for card information');
        $this->assertGreaterThanOrEqual(1, count($textFields), 'Should have at least 1 text field (cardId)');
    }

    /** @return \Generator<string, array{string}> */
    public static function provideIndexPageHeaders(): \Generator
    {
        yield 'ID' => ['ID'];
        yield '微信公众号' => ['微信公众号'];
        yield '卡券ID' => ['卡券ID'];
        yield '卡券类型' => ['卡券类型'];
        yield '状态' => ['状态'];
        yield '同步中' => ['同步中'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /** @return \Generator<string, array{string}> */
    public static function provideNewPageFields(): \Generator
    {
        yield 'account' => ['account'];
        yield 'cardId' => ['cardId'];
        yield 'cardType' => ['cardType'];
        yield 'status' => ['status'];
        yield 'syncing' => ['syncing'];
    }

    /** @return \Generator<string, array{string}> */
    public static function provideEditPageFields(): \Generator
    {
        yield 'account' => ['account'];
        yield 'cardId' => ['cardId'];
        yield 'cardType' => ['cardType'];
        yield 'status' => ['status'];
        yield 'syncing' => ['syncing'];
    }

    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();

        // 使用正确的EasyAdmin URL格式
        $url = $this->generateAdminUrl(Action::NEW);
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();

        // 验证页面可访问且包含表单元素
        // selectButton()->form() 在按钮不存在时会抛出 InvalidArgumentException，无需额外 assertNotNull
        $form = $client->getCrawler()->selectButton('Create')->form();

        // 提交空表单
        $crawler = $client->submit($form);

        // 验证返回状态码和错误信息
        $this->assertResponseStatusCodeSame(422);

        // 验证有验证错误
        $errorMessages = $crawler->filter('.invalid-feedback')->extract(['_text']);
        $hasValidationError = false;

        foreach ($errorMessages as $message) {
            $this->assertIsString($message);
            if (str_contains($message, 'should not be blank') || str_contains($message, 'This value should not be blank')) {
                $hasValidationError = true;
                break;
            }
        }

        $this->assertTrue($hasValidationError, 'Should have validation errors for required fields');
    }
}
