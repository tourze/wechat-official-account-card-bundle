<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatOfficialAccountCardBundle\Enum\EventTypeEnum;

/**
 * @internal
 */
#[CoversClass(EventTypeEnum::class)]
final class EventTypeEnumTest extends AbstractEnumTestCase
{
    #[TestWith([EventTypeEnum::USER_GET_CARD, 'user_get_card', '用户领取卡券'])]
    #[TestWith([EventTypeEnum::USER_DELETE_CARD, 'user_del_card', '用户删除卡券'])]
    #[TestWith([EventTypeEnum::USER_CONSUME_CARD, 'user_consume_card', '用户核销卡券'])]
    public function testValueAndLabel(EventTypeEnum $enum, string $expectedValue, string $expectedLabel): void
    {
        $this->assertEquals($expectedValue, $enum->value);
        $this->assertEquals($expectedLabel, $enum->getLabel());
    }

    #[TestWith(['user_get_card', EventTypeEnum::USER_GET_CARD])]
    #[TestWith(['user_del_card', EventTypeEnum::USER_DELETE_CARD])]
    #[TestWith(['user_consume_card', EventTypeEnum::USER_CONSUME_CARD])]
    public function testFromValidValue(string $value, EventTypeEnum $expected): void
    {
        $this->assertEquals($expected, EventTypeEnum::from($value));
    }

    #[TestWith(['invalid_event'])]
    #[TestWith(['user_invalid_card'])]
    #[TestWith(['not_valid'])]
    public function testFromInvalidValueThrowsException(string $invalidValue): void
    {
        $this->expectException(\ValueError::class);
        EventTypeEnum::from($invalidValue);
    }

    #[TestWith(['user_get_card', EventTypeEnum::USER_GET_CARD])]
    #[TestWith(['user_del_card', EventTypeEnum::USER_DELETE_CARD])]
    #[TestWith(['user_consume_card', EventTypeEnum::USER_CONSUME_CARD])]
    public function testTryFromValidValue(string $value, EventTypeEnum $expected): void
    {
        $this->assertEquals($expected, EventTypeEnum::tryFrom($value));
    }

    #[TestWith(['invalid_event'])]
    #[TestWith(['user_invalid_card'])]
    #[TestWith(['not_valid'])]
    public function testTryFromInvalidValueReturnsNull(string $invalidValue): void
    {
        $this->assertNull(EventTypeEnum::tryFrom($invalidValue));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (EventTypeEnum $case) => $case->value, EventTypeEnum::cases());
        $this->assertCount(count(array_unique($values)), $values, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (EventTypeEnum $case) => $case->getLabel(), EventTypeEnum::cases());
        $uniqueLabels = array_unique($labels);
        $this->assertCount(count($uniqueLabels), $labels, 'All enum labels must be unique');
    }

    public function testCasesCount(): void
    {
        $this->assertCount(3, EventTypeEnum::cases());
    }

    public function testToArray(): void
    {
        $result = EventTypeEnum::USER_GET_CARD->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);

        $this->assertEquals('user_get_card', $result['value']);
        $this->assertEquals('用户领取卡券', $result['label']);
    }
}
