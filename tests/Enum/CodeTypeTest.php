<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatOfficialAccountCardBundle\Enum\CodeType;

/**
 * @internal
 */
#[CoversClass(CodeType::class)]
final class CodeTypeTest extends AbstractEnumTestCase
{
    #[TestWith([CodeType::CODE_TYPE_TEXT, 'CODE_TYPE_TEXT', '文本'])]
    #[TestWith([CodeType::CODE_TYPE_BARCODE, 'CODE_TYPE_BARCODE', '一维码'])]
    #[TestWith([CodeType::CODE_TYPE_QRCODE, 'CODE_TYPE_QRCODE', '二维码'])]
    #[TestWith([CodeType::CODE_TYPE_ONLY_QRCODE, 'CODE_TYPE_ONLY_QRCODE', '仅显示二维码'])]
    #[TestWith([CodeType::CODE_TYPE_ONLY_BARCODE, 'CODE_TYPE_ONLY_BARCODE', '仅显示一维码'])]
    #[TestWith([CodeType::CODE_TYPE_NONE, 'CODE_TYPE_NONE', '不显示任何码型'])]
    public function testValueAndLabel(CodeType $enum, string $expectedValue, string $expectedLabel): void
    {
        $this->assertEquals($expectedValue, $enum->value);
        $this->assertEquals($expectedLabel, $enum->getLabel());
    }

    #[TestWith(['CODE_TYPE_TEXT', CodeType::CODE_TYPE_TEXT])]
    #[TestWith(['CODE_TYPE_BARCODE', CodeType::CODE_TYPE_BARCODE])]
    #[TestWith(['CODE_TYPE_QRCODE', CodeType::CODE_TYPE_QRCODE])]
    #[TestWith(['CODE_TYPE_ONLY_QRCODE', CodeType::CODE_TYPE_ONLY_QRCODE])]
    #[TestWith(['CODE_TYPE_ONLY_BARCODE', CodeType::CODE_TYPE_ONLY_BARCODE])]
    #[TestWith(['CODE_TYPE_NONE', CodeType::CODE_TYPE_NONE])]
    public function testFromValidValue(string $value, CodeType $expected): void
    {
        $this->assertEquals($expected, CodeType::from($value));
    }

    #[TestWith(['INVALID_CODE_TYPE'])]
    #[TestWith(['CODE_TYPE_INVALID'])]
    #[TestWith(['NOT_VALID'])]
    public function testFromInvalidValueThrowsException(string $invalidValue): void
    {
        $this->expectException(\ValueError::class);
        CodeType::from($invalidValue);
    }

    #[TestWith(['CODE_TYPE_TEXT', CodeType::CODE_TYPE_TEXT])]
    #[TestWith(['CODE_TYPE_QRCODE', CodeType::CODE_TYPE_QRCODE])]
    #[TestWith(['CODE_TYPE_NONE', CodeType::CODE_TYPE_NONE])]
    public function testTryFromValidValue(string $value, CodeType $expected): void
    {
        $this->assertEquals($expected, CodeType::tryFrom($value));
    }

    #[TestWith(['INVALID_CODE_TYPE'])]
    #[TestWith(['CODE_TYPE_INVALID'])]
    #[TestWith(['NOT_VALID'])]
    public function testTryFromInvalidValueReturnsNull(string $invalidValue): void
    {
        $this->assertNull(CodeType::tryFrom($invalidValue));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (CodeType $case) => $case->value, CodeType::cases());
        $this->assertCount(count(array_unique($values)), $values, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (CodeType $case) => $case->getLabel(), CodeType::cases());
        $uniqueLabels = array_unique($labels);
        $this->assertCount(count($uniqueLabels), $labels, 'All enum labels must be unique');
    }

    public function testCasesCount(): void
    {
        $this->assertCount(6, CodeType::cases());
    }

    public function testToArray(): void
    {
        $result = CodeType::CODE_TYPE_TEXT->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertEquals('CODE_TYPE_TEXT', $result['value']);
        $this->assertEquals('文本', $result['label']);
    }
}
