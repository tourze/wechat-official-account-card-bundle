<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatOfficialAccountCardBundle\Enum\CardType;

/**
 * @internal
 */
#[CoversClass(CardType::class)]
final class CardTypeTest extends AbstractEnumTestCase
{
    #[TestWith([CardType::GROUPON, 'GROUPON', '团购券'])]
    #[TestWith([CardType::CASH, 'CASH', '代金券'])]
    #[TestWith([CardType::DISCOUNT, 'DISCOUNT', '折扣券'])]
    #[TestWith([CardType::GIFT, 'GIFT', '兑换券'])]
    #[TestWith([CardType::GENERAL_COUPON, 'GENERAL_COUPON', '优惠券'])]
    #[TestWith([CardType::MEMBER_CARD, 'MEMBER_CARD', '会员卡'])]
    #[TestWith([CardType::SCENIC_TICKET, 'SCENIC_TICKET', '景点门票'])]
    #[TestWith([CardType::MOVIE_TICKET, 'MOVIE_TICKET', '电影票'])]
    #[TestWith([CardType::BOARDING_PASS, 'BOARDING_PASS', '飞机票'])]
    #[TestWith([CardType::MEETING_TICKET, 'MEETING_TICKET', '会议门票'])]
    #[TestWith([CardType::BUS_TICKET, 'BUS_TICKET', '汽车票'])]
    public function testValueAndLabel(CardType $enum, string $expectedValue, string $expectedLabel): void
    {
        $this->assertEquals($expectedValue, $enum->value);
        $this->assertEquals($expectedLabel, $enum->getLabel());
    }

    #[TestWith(['GROUPON', CardType::GROUPON])]
    #[TestWith(['CASH', CardType::CASH])]
    #[TestWith(['DISCOUNT', CardType::DISCOUNT])]
    #[TestWith(['GIFT', CardType::GIFT])]
    #[TestWith(['GENERAL_COUPON', CardType::GENERAL_COUPON])]
    #[TestWith(['MEMBER_CARD', CardType::MEMBER_CARD])]
    #[TestWith(['SCENIC_TICKET', CardType::SCENIC_TICKET])]
    #[TestWith(['MOVIE_TICKET', CardType::MOVIE_TICKET])]
    #[TestWith(['BOARDING_PASS', CardType::BOARDING_PASS])]
    #[TestWith(['MEETING_TICKET', CardType::MEETING_TICKET])]
    #[TestWith(['BUS_TICKET', CardType::BUS_TICKET])]
    public function testFromValidValue(string $value, CardType $expected): void
    {
        $this->assertEquals($expected, CardType::from($value));
    }

    #[TestWith(['INVALID_TYPE'])]
    #[TestWith(['UNKNOWN_CARD'])]
    #[TestWith(['NOT_VALID'])]
    public function testFromInvalidValueThrowsException(string $invalidValue): void
    {
        $this->expectException(\ValueError::class);
        CardType::from($invalidValue);
    }

    #[TestWith(['CASH', CardType::CASH])]
    #[TestWith(['DISCOUNT', CardType::DISCOUNT])]
    #[TestWith(['GIFT', CardType::GIFT])]
    public function testTryFromValidValue(string $value, CardType $expected): void
    {
        $this->assertEquals($expected, CardType::tryFrom($value));
    }

    #[TestWith(['INVALID_TYPE'])]
    #[TestWith(['UNKNOWN_CARD'])]
    #[TestWith(['NOT_VALID'])]
    public function testTryFromInvalidValueReturnsNull(string $invalidValue): void
    {
        $this->assertNull(CardType::tryFrom($invalidValue));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (CardType $case) => $case->value, CardType::cases());
        $this->assertCount(count(array_unique($values)), $values, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (CardType $case) => $case->getLabel(), CardType::cases());
        $uniqueLabels = array_unique($labels);
        $this->assertCount(count($uniqueLabels), $labels, 'All enum labels must be unique');
    }

    public function testCasesCount(): void
    {
        $this->assertCount(11, CardType::cases());
    }

    public function testToArray(): void
    {
        $result = CardType::CASH->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertEquals('CASH', $result['value']);
        $this->assertEquals('代金券', $result['label']);
    }
}
