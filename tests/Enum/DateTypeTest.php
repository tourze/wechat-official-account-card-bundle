<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatOfficialAccountCardBundle\Enum\DateType;

/**
 * @internal
 */
#[CoversClass(DateType::class)]
final class DateTypeTest extends AbstractEnumTestCase
{
    #[TestWith([DateType::DATE_TYPE_FIX_TIME_RANGE, 'DATE_TYPE_FIX_TIME_RANGE', '固定日期区间'])]
    #[TestWith([DateType::DATE_TYPE_FIX_TERM, 'DATE_TYPE_FIX_TERM', '固定时长（自领取后按天算）'])]
    #[TestWith([DateType::DATE_TYPE_PERMANENT, 'DATE_TYPE_PERMANENT', '永久有效'])]
    public function testValueAndLabel(DateType $enum, string $expectedValue, string $expectedLabel): void
    {
        $this->assertEquals($expectedValue, $enum->value);
        $this->assertEquals($expectedLabel, $enum->getLabel());
    }

    #[TestWith(['DATE_TYPE_FIX_TIME_RANGE', DateType::DATE_TYPE_FIX_TIME_RANGE])]
    #[TestWith(['DATE_TYPE_FIX_TERM', DateType::DATE_TYPE_FIX_TERM])]
    #[TestWith(['DATE_TYPE_PERMANENT', DateType::DATE_TYPE_PERMANENT])]
    public function testFromValidValue(string $value, DateType $expected): void
    {
        $this->assertEquals($expected, DateType::from($value));
    }

    #[TestWith(['INVALID_DATE_TYPE'])]
    #[TestWith(['DATE_TYPE_INVALID'])]
    #[TestWith(['NOT_VALID'])]
    public function testFromInvalidValueThrowsException(string $invalidValue): void
    {
        $this->expectException(\ValueError::class);
        DateType::from($invalidValue);
    }

    #[TestWith(['DATE_TYPE_FIX_TIME_RANGE', DateType::DATE_TYPE_FIX_TIME_RANGE])]
    #[TestWith(['DATE_TYPE_FIX_TERM', DateType::DATE_TYPE_FIX_TERM])]
    #[TestWith(['DATE_TYPE_PERMANENT', DateType::DATE_TYPE_PERMANENT])]
    public function testTryFromValidValue(string $value, DateType $expected): void
    {
        $this->assertEquals($expected, DateType::tryFrom($value));
    }

    #[TestWith(['INVALID_DATE_TYPE'])]
    #[TestWith(['DATE_TYPE_INVALID'])]
    #[TestWith(['NOT_VALID'])]
    public function testTryFromInvalidValueReturnsNull(string $invalidValue): void
    {
        $this->assertNull(DateType::tryFrom($invalidValue));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (DateType $case) => $case->value, DateType::cases());
        $this->assertCount(count(array_unique($values)), $values, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (DateType $case) => $case->getLabel(), DateType::cases());
        $uniqueLabels = array_unique($labels);
        $this->assertCount(count($uniqueLabels), $labels, 'All enum labels must be unique');
    }

    public function testCasesCount(): void
    {
        $this->assertCount(3, DateType::cases());
    }

    public function testToArray(): void
    {
        $result = DateType::DATE_TYPE_FIX_TIME_RANGE->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertEquals('DATE_TYPE_FIX_TIME_RANGE', $result['value']);
        $this->assertEquals('固定日期区间', $result['label']);
    }
}
