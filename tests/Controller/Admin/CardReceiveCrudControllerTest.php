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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\PHPUnitSymfonyWebTest\AbstractEasyAdminControllerTestCase;
use WechatOfficialAccountCardBundle\Controller\Admin\CardReceiveCrudController;
use WechatOfficialAccountCardBundle\Entity\CardReceive;

/**
 * CardReceiveCrudController 单元测试
 * 测试重点：EasyAdmin配置、字段配置、卡券领取记录管理功能验证
 * @internal
 */
#[CoversClass(CardReceiveCrudController::class)]
#[RunTestsInSeparateProcesses]
class CardReceiveCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    private CardReceiveCrudController $controller;

    protected function setUpController(): void
    {
        $this->controller = self::getService(CardReceiveCrudController::class);
    }

    protected function getControllerService(): CardReceiveCrudController
    {
        return self::getService(CardReceiveCrudController::class);
    }

    public function testGetEntityFqcnReturnsCorrectClass(): void
    {
        $this->assertSame(CardReceive::class, CardReceiveCrudController::getEntityFqcn());
    }

    public function testConfigureFieldsReturnsExpectedFields(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_INDEX));

        $this->assertNotEmpty($fields, 'Index page should have fields configured');
        $this->assertGreaterThanOrEqual(12, count($fields), 'Index page should have at least 12 fields');

        // 验证字段配置对象类型正确
        foreach ($fields as $field) {
            $this->assertInstanceOf(FieldInterface::class, $field, 'Each field should implement FieldInterface');
        }
    }

    public function testFieldsIncludeRequiredCardReceiveFields(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $fieldClasses = array_map(function ($field): string {
            return is_object($field) ? get_class($field) : '';
        }, $fields);

        $fieldClasses = array_filter($fieldClasses, static fn ($class): bool => '' !== $class);

        // 验证包含卡券领取记录管理的关键字段类型
        $this->assertContains(IdField::class, $fieldClasses, 'Should include ID field');
        $this->assertContains(AssociationField::class, $fieldClasses, 'Should include Association fields for card and cardCode');
        $this->assertContains(TextField::class, $fieldClasses, 'Should include Text fields for user information');
        $this->assertContains(BooleanField::class, $fieldClasses, 'Should include Boolean fields for status tracking');
        $this->assertContains(DateTimeField::class, $fieldClasses, 'Should include DateTime fields');
    }

    public function testAssociationFieldsConfiguration(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $associationFields = array_filter($fields, function ($field) {
            return $field instanceof AssociationField;
        });

        // 验证包含关联字段（关联卡券和卡券码）
        $this->assertNotEmpty($associationFields, 'Should have association fields');
        $this->assertGreaterThanOrEqual(2, count($associationFields), 'Should have at least 2 association fields (card and cardCode)');
    }

    public function testUserInformationFields(): void
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

        // 验证用户信息相关字段存在
        $userInfoFields = [
            'openId',           // 用户OpenID
            'givenToOpenId',     // 转赠目标用户
        ];

        foreach ($userInfoFields as $userInfoField) {
            $this->assertContains($userInfoField, $fieldNames, "User info field '{$userInfoField}' should be present in card receive configuration");
        }
    }

    public function testStatusTrackingFields(): void
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

        // 验证状态跟踪字段存在
        $statusFields = [
            'isUsed',        // 已使用
            'usedAt',        // 使用时间
            'isUnavailable', // 已失效
            'unavailableAt', // 失效时间
            'isGiven',       // 已转赠
            'givenAt',        // 转赠时间
        ];

        foreach ($statusFields as $statusField) {
            $this->assertContains($statusField, $fieldNames, "Status field '{$statusField}' should be present in card receive configuration");
        }
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

        // 详情页应该显示多个日期时间字段（创建时间、更新时间、使用时间、失效时间、转赠时间）
        $dateTimeFieldCount = count(array_filter($fieldClasses, fn ($class) => DateTimeField::class === $class));
        $this->assertGreaterThanOrEqual(5, $dateTimeFieldCount, 'Detail page should show multiple datetime fields for tracking');
    }

    public function testControllerImplementsCorrectInterface(): void
    {
        $this->setUpController();
        $this->assertInstanceOf(
            AbstractCrudController::class,
            $this->controller
        );
    }

    public function testCardReceiveSpecificFieldsArePresent(): void
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

        // 验证卡券领取记录特有的字段存在
        $expectedFields = [
            'id',
            'card',          // 关联卡券
            'cardCode',      // 卡券码
            'openId',        // 用户OpenID
            'isUsed',        // 已使用
            'usedAt',        // 使用时间
            'isUnavailable', // 已失效
            'unavailableAt', // 失效时间
            'isGiven',       // 已转赠
            'givenAt',       // 转赠时间
            'givenToOpenId', // 转赠目标用户
            'createTime',     // 创建时间
            'updateTime',      // 更新时间
        ];

        foreach ($expectedFields as $expectedField) {
            $this->assertContains($expectedField, $fieldNames, "Field '{$expectedField}' should be present in card receive configuration");
        }
    }

    public function testBooleanFieldsConfiguration(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $booleanFields = array_filter($fields, function ($field) {
            return $field instanceof BooleanField;
        });

        // 验证包含状态相关的布尔字段
        $this->assertNotEmpty($booleanFields, 'Should have boolean fields for status tracking');

        // 验证至少有3个布尔字段（isUsed, isUnavailable, isGiven）
        $this->assertGreaterThanOrEqual(3, count($booleanFields), 'Should have at least 3 boolean fields for comprehensive status tracking');
    }

    public function testTextFieldsForUserIdentification(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $textFields = array_filter($fields, function ($field) {
            return $field instanceof TextField;
        });

        // 验证包含文本字段用于用户识别
        $this->assertNotEmpty($textFields, 'Should have text fields for user identification');

        // 验证有足够的文本字段（openId, givenToOpenId）
        $this->assertGreaterThanOrEqual(2, count($textFields), 'Should have at least 2 text fields for user identification');
    }

    public function testDateTimeFieldsForStatusTracking(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_DETAIL));

        $dateTimeFields = array_filter($fields, function ($field) {
            return $field instanceof DateTimeField;
        });

        // 验证包含日期时间字段用于状态跟踪
        $this->assertNotEmpty($dateTimeFields, 'Should have datetime fields for status tracking');

        // 验证有足够的日期时间字段（usedAt, unavailableAt, givenAt, createdAt, updatedAt）
        $this->assertGreaterThanOrEqual(5, count($dateTimeFields), 'Should have at least 5 datetime fields for comprehensive tracking');
    }

    /** @return \Generator<string, array{string}> */
    public static function provideIndexPageHeaders(): \Generator
    {
        yield 'ID' => ['ID'];
        yield '关联卡券' => ['关联卡券'];
        yield '卡券码' => ['卡券码'];
        yield '用户OpenID' => ['用户OpenID'];
        yield '已使用' => ['已使用'];
        yield '使用时间' => ['使用时间'];
        yield '已失效' => ['已失效'];
        yield '失效时间' => ['失效时间'];
        yield '已转赠' => ['已转赠'];
        yield '转赠时间' => ['转赠时间'];
        yield '转赠目标用户' => ['转赠目标用户'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /** @return \Generator<string, array{string}> */
    public static function provideNewPageFields(): \Generator
    {
        yield 'card' => ['card'];
        yield 'cardCode' => ['cardCode'];
        yield 'openId' => ['openId'];
        yield 'isUsed' => ['isUsed'];
        yield 'isUnavailable' => ['isUnavailable'];
        yield 'isGiven' => ['isGiven'];
        yield 'givenToOpenId' => ['givenToOpenId'];
    }

    /** @return \Generator<string, array{string}> */
    public static function provideEditPageFields(): \Generator
    {
        yield 'card' => ['card'];
        yield 'cardCode' => ['cardCode'];
        yield 'openId' => ['openId'];
        yield 'isUsed' => ['isUsed'];
        yield 'isUnavailable' => ['isUnavailable'];
        yield 'isGiven' => ['isGiven'];
        yield 'givenToOpenId' => ['givenToOpenId'];
    }

    public function testValidationErrors(): void
    {
        $client = $this->createAuthenticatedClient();

        // 使用正确的EasyAdmin URL格式
        $url = $this->generateAdminUrl(Action::NEW);
        $client->request('GET', $url);

        $this->assertResponseIsSuccessful();

        $form = $client->getCrawler()->selectButton('Create')->form();

        // 提交空表单
        $crawler = $client->submit($form);

        // 验证返回状态码和错误信息
        $this->assertResponseStatusCodeSame(422);

        // 验证必填字段的错误信息
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
