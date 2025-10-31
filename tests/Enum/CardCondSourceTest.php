<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatOfficialAccountCardBundle\Enum\CardCondSource;

/**
 * @internal
 */
#[CoversClass(CardCondSource::class)]
final class CardCondSourceTest extends AbstractEnumTestCase
{
    #[TestWith([CardCondSource::PLATFORM, 0, '公众平台创建'])]
    #[TestWith([CardCondSource::API, 1, 'API创建'])]
    public function testValueAndLabel(CardCondSource $enum, int $expectedValue, string $expectedLabel): void
    {
        $this->assertEquals($expectedValue, $enum->value);
        $this->assertEquals($expectedLabel, $enum->getLabel());
    }

    #[TestWith([0, CardCondSource::PLATFORM])]
    #[TestWith([1, CardCondSource::API])]
    public function testFromValidValue(int $value, CardCondSource $expected): void
    {
        $this->assertEquals($expected, CardCondSource::from($value));
    }

    #[TestWith([999])]
    #[TestWith([-1])]
    #[TestWith([2])]
    public function testFromInvalidValueThrowsException(int $invalidValue): void
    {
        $this->expectException(\ValueError::class);
        CardCondSource::from($invalidValue);
    }

    #[TestWith([0, CardCondSource::PLATFORM])]
    #[TestWith([1, CardCondSource::API])]
    public function testTryFromValidValue(int $value, CardCondSource $expected): void
    {
        $this->assertEquals($expected, CardCondSource::tryFrom($value));
    }

    #[TestWith([999])]
    #[TestWith([-1])]
    #[TestWith([2])]
    public function testTryFromInvalidValueReturnsNull(int $invalidValue): void
    {
        $this->assertNull(CardCondSource::tryFrom($invalidValue));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (CardCondSource $case) => $case->value, CardCondSource::cases());
        $this->assertCount(count(array_unique($values)), $values, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (CardCondSource $case) => $case->getLabel(), CardCondSource::cases());
        $uniqueLabels = array_unique($labels);
        $this->assertCount(count($uniqueLabels), $labels, 'All enum labels must be unique');
    }

    public function testCasesCount(): void
    {
        $this->assertCount(2, CardCondSource::cases());
    }

    public function testToArray(): void
    {
        $result = CardCondSource::PLATFORM->toArray();

        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertEquals(0, $result['value']);
        $this->assertEquals('公众平台创建', $result['label']);
    }
}
