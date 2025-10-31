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
use WechatOfficialAccountCardBundle\Controller\Admin\CardCodeCrudController;
use WechatOfficialAccountCardBundle\Entity\CardCode;

/**
 * CardCodeCrudController 单元测试
 * 测试重点：EasyAdmin配置、字段配置、卡券码管理功能验证
 * @internal
 */
#[CoversClass(CardCodeCrudController::class)]
#[RunTestsInSeparateProcesses]
class CardCodeCrudControllerTest extends AbstractEasyAdminControllerTestCase
{
    private CardCodeCrudController $controller;

    protected function setUpController(): void
    {
        $this->controller = self::getService(CardCodeCrudController::class);
    }

    protected function getControllerService(): CardCodeCrudController
    {
        return self::getService(CardCodeCrudController::class);
    }

    public function testGetEntityFqcnReturnsCorrectClass(): void
    {
        $this->assertSame(CardCode::class, CardCodeCrudController::getEntityFqcn());
    }

    public function testConfigureFieldsReturnsExpectedFields(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_INDEX));

        $this->assertNotEmpty($fields, 'Index page should have fields configured');
        $this->assertGreaterThanOrEqual(9, count($fields), 'Index page should have at least 9 fields');

        // 验证字段配置对象类型正确
        foreach ($fields as $field) {
            $this->assertInstanceOf(FieldInterface::class, $field, 'Each field should implement FieldInterface');
        }
    }

    public function testFieldsIncludeRequiredCardCodeFields(): void
    {
        $this->setUpController();
        $fields = iterator_to_array($this->controller->configureFields(Crud::PAGE_NEW));

        $fieldClasses = array_map(function ($field): string {
            return is_object($field) ? get_class($field) : '';
        }, $fields);

        $fieldClasses = array_filter($fieldClasses, static fn ($class): bool => '' !== $class);

        // 验证包含卡券码管理的关键字段类型
        $this->assertContains(IdField::class, $fieldClasses, 'Should include ID field');
        $this->assertContains(AssociationField::class, $fieldClasses, 'Should include Association field for card');
        $this->assertContains(TextField::class, $fieldClasses, 'Should include Text field for code');
        $this->assertContains(BooleanField::class, $fieldClasses, 'Should include Boolean fields for status');
        $this->assertContains(DateTimeField::class, $fieldClasses, 'Should include DateTime fields');
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

        // 详情页应该显示更多日期时间字段（创建时间、更新时间、使用时间、失效时间）
        $dateTimeFieldCount = count(array_filter($fieldClasses, fn ($class) => DateTimeField::class === $class));
        $this->assertGreaterThanOrEqual(4, $dateTimeFieldCount, 'Detail page should show multiple datetime fields');
    }

    public function testControllerImplementsCorrectInterface(): void
    {
        $this->setUpController();
        $this->assertInstanceOf(
            AbstractCrudController::class,
            $this->controller
        );
    }

    public function testCardCodeSpecificFieldsArePresent(): void
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

        // 验证卡券码特有的字段存在
        $expectedFields = [
            'id',
            'card',        // 关联卡券
            'code',        // 卡券码
            'isUsed',      // 已使用
            'usedAt',      // 使用时间
            'isUnavailable', // 已失效
            'unavailableAt', // 失效时间
            'createTime',   // 创建时间
            'updateTime',    // 更新时间
        ];

        foreach ($expectedFields as $expectedField) {
            $this->assertContains($expectedField, $fieldNames, "Field '{$expectedField}' should be present in card code configuration");
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

        // 验证至少有2个布尔字段（isUsed 和 isUnavailable）
        $this->assertGreaterThanOrEqual(2, count($booleanFields), 'Should have at least 2 boolean fields for status management');
    }

    /** @return \Generator<string, array{string}> */
    public static function provideIndexPageHeaders(): \Generator
    {
        yield 'ID' => ['ID'];
        yield '关联卡券' => ['关联卡券'];
        yield '卡券码' => ['卡券码'];
        yield '已使用' => ['已使用'];
        yield '使用时间' => ['使用时间'];
        yield '已失效' => ['已失效'];
        yield '失效时间' => ['失效时间'];
        yield '创建时间' => ['创建时间'];
        yield '更新时间' => ['更新时间'];
    }

    /** @return \Generator<string, array{string}> */
    public static function provideNewPageFields(): \Generator
    {
        yield 'card' => ['card'];
        yield 'code' => ['code'];
        yield 'isUsed' => ['isUsed'];
        yield 'isUnavailable' => ['isUnavailable'];
    }

    /** @return \Generator<string, array{string}> */
    public static function provideEditPageFields(): \Generator
    {
        yield 'card' => ['card'];
        yield 'code' => ['code'];
        yield 'isUsed' => ['isUsed'];
        yield 'isUnavailable' => ['isUnavailable'];
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
