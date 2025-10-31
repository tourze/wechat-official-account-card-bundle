<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatOfficialAccountCardBundle\Enum\CardStateEnum;

/**
 * @internal
 */
#[CoversClass(CardStateEnum::class)]
final class CardStateEnumTest extends AbstractEnumTestCase
{
    #[TestWith([CardStateEnum::NORMAL, 0, '待领取'])]
    #[TestWith([CardStateEnum::GATHER, 1, '已领取'])]
    #[TestWith([CardStateEnum::DELETE, -1, '已删除'])]
    #[TestWith([CardStateEnum::VERIFIED, 2, '已核销'])]
    public function testValueAndLabel(CardStateEnum $enum, int $expectedValue, string $expectedLabel): void
    {
        $this->assertEquals($expectedValue, $enum->value);
        $this->assertEquals($expectedLabel, $enum->getLabel());
    }

    #[TestWith([0, CardStateEnum::NORMAL])]
    #[TestWith([1, CardStateEnum::GATHER])]
    #[TestWith([-1, CardStateEnum::DELETE])]
    #[TestWith([2, CardStateEnum::VERIFIED])]
    public function testFromValidValue(int $value, CardStateEnum $expected): void
    {
        $this->assertEquals($expected, CardStateEnum::from($value));
    }

    #[TestWith([999])]
    #[TestWith([-999])]
    #[TestWith([3])]
    public function testFromInvalidValueThrowsException(int $invalidValue): void
    {
        $this->expectException(\ValueError::class);
        CardStateEnum::from($invalidValue);
    }

    #[TestWith([0, CardStateEnum::NORMAL])]
    #[TestWith([1, CardStateEnum::GATHER])]
    #[TestWith([-1, CardStateEnum::DELETE])]
    #[TestWith([2, CardStateEnum::VERIFIED])]
    public function testTryFromValidValue(int $value, CardStateEnum $expected): void
    {
        $this->assertEquals($expected, CardStateEnum::tryFrom($value));
    }

    #[TestWith([999])]
    #[TestWith([-999])]
    #[TestWith([3])]
    public function testTryFromInvalidValueReturnsNull(int $invalidValue): void
    {
        $this->assertNull(CardStateEnum::tryFrom($invalidValue));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (CardStateEnum $case) => $case->value, CardStateEnum::cases());
        $this->assertCount(count(array_unique($values)), $values, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (CardStateEnum $case) => $case->getLabel(), CardStateEnum::cases());
        $uniqueLabels = array_unique($labels);
        $this->assertCount(count($uniqueLabels), $labels, 'All enum labels must be unique');
    }

    public function testCasesCount(): void
    {
        $this->assertCount(4, CardStateEnum::cases());
    }

    public function testGetList(): void
    {
        $list = CardStateEnum::getList();

        $this->assertCount(4, $list);
        $this->assertArrayHasKey(0, $list);
        $this->assertArrayHasKey(1, $list);
        $this->assertArrayHasKey(-1, $list);
        $this->assertArrayHasKey(2, $list);
        $this->assertEquals('待领取', $list[0]);
        $this->assertEquals('已领取', $list[1]);
        $this->assertEquals('已删除', $list[-1]);
        $this->assertEquals('已核销', $list[2]);
    }

    public function testToArray(): void
    {
        $result = CardStateEnum::NORMAL->toArray();

        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertEquals(0, $result['value']);
        $this->assertEquals('待领取', $result['label']);
    }
}
