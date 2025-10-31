<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatOfficialAccountCardBundle\Enum\CardStatus;

/**
 * @internal
 */
#[CoversClass(CardStatus::class)]
final class CardStatusTest extends AbstractEnumTestCase
{
    #[TestWith([CardStatus::NOT_VERIFY, 'CARD_STATUS_NOT_VERIFY', '待审核'])]
    #[TestWith([CardStatus::VERIFY_FAIL, 'CARD_STATUS_VERIFY_FAIL', '审核失败'])]
    #[TestWith([CardStatus::VERIFY_OK, 'CARD_STATUS_VERIFY_OK', '通过审核'])]
    #[TestWith([CardStatus::DELETE, 'CARD_STATUS_DELETE', '已删除'])]
    #[TestWith([CardStatus::DISPATCH, 'CARD_STATUS_DISPATCH', '已投放'])]
    public function testValueAndLabel(CardStatus $enum, string $expectedValue, string $expectedLabel): void
    {
        $this->assertEquals($expectedValue, $enum->value);
        $this->assertEquals($expectedLabel, $enum->getLabel());
    }

    #[TestWith(['CARD_STATUS_NOT_VERIFY', CardStatus::NOT_VERIFY])]
    #[TestWith(['CARD_STATUS_VERIFY_FAIL', CardStatus::VERIFY_FAIL])]
    #[TestWith(['CARD_STATUS_VERIFY_OK', CardStatus::VERIFY_OK])]
    #[TestWith(['CARD_STATUS_DELETE', CardStatus::DELETE])]
    #[TestWith(['CARD_STATUS_DISPATCH', CardStatus::DISPATCH])]
    public function testFromValidValue(string $value, CardStatus $expected): void
    {
        $this->assertEquals($expected, CardStatus::from($value));
    }

    #[TestWith(['INVALID_STATUS'])]
    #[TestWith(['CARD_STATUS_INVALID'])]
    #[TestWith(['NOT_VALID'])]
    public function testFromInvalidValueThrowsException(string $invalidValue): void
    {
        $this->expectException(\ValueError::class);
        CardStatus::from($invalidValue);
    }

    #[TestWith(['CARD_STATUS_NOT_VERIFY', CardStatus::NOT_VERIFY])]
    #[TestWith(['CARD_STATUS_VERIFY_OK', CardStatus::VERIFY_OK])]
    #[TestWith(['CARD_STATUS_DISPATCH', CardStatus::DISPATCH])]
    public function testTryFromValidValue(string $value, CardStatus $expected): void
    {
        $this->assertEquals($expected, CardStatus::tryFrom($value));
    }

    #[TestWith(['INVALID_STATUS'])]
    #[TestWith(['CARD_STATUS_INVALID'])]
    #[TestWith(['NOT_VALID'])]
    public function testTryFromInvalidValueReturnsNull(string $invalidValue): void
    {
        $this->assertNull(CardStatus::tryFrom($invalidValue));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (CardStatus $case) => $case->value, CardStatus::cases());
        $this->assertCount(count(array_unique($values)), $values, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (CardStatus $case) => $case->getLabel(), CardStatus::cases());
        $uniqueLabels = array_unique($labels);
        $this->assertCount(count($uniqueLabels), $labels, 'All enum labels must be unique');
    }

    public function testCasesCount(): void
    {
        $this->assertCount(5, CardStatus::cases());
    }

    public function testToArray(): void
    {
        $result = CardStatus::NOT_VERIFY->toArray();

        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertEquals('CARD_STATUS_NOT_VERIFY', $result['value']);
        $this->assertEquals('待审核', $result['label']);
    }
}
