<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatOfficialAccountCardBundle\Enum\CardColor;

/**
 * @internal
 */
#[CoversClass(CardColor::class)]
final class CardColorTest extends AbstractEnumTestCase
{
    #[TestWith([CardColor::COLOR_010, '#63b359', '淡绿色'])]
    #[TestWith([CardColor::COLOR_020, '#2c9f67', '深绿色'])]
    #[TestWith([CardColor::COLOR_030, '#509fc9', '浅蓝色'])]
    #[TestWith([CardColor::COLOR_040, '#5885cf', '蓝色'])]
    #[TestWith([CardColor::COLOR_050, '#9062c0', '紫色'])]
    #[TestWith([CardColor::COLOR_060, '#d09a45', '棕色'])]
    #[TestWith([CardColor::COLOR_070, '#e4b138', '黄色'])]
    #[TestWith([CardColor::COLOR_080, '#ee903c', '橙色'])]
    #[TestWith([CardColor::COLOR_081, '#f08500', '橙色'])]
    #[TestWith([CardColor::COLOR_082, '#a9d92d', '绿色'])]
    #[TestWith([CardColor::COLOR_090, '#dd6549', '红色'])]
    #[TestWith([CardColor::COLOR_100, '#cc463d', '深红色'])]
    #[TestWith([CardColor::COLOR_101, '#cf3e36', '深红色'])]
    #[TestWith([CardColor::COLOR_102, '#5E6671', '灰色'])]
    public function testValueAndLabel(CardColor $enum, string $expectedValue, string $expectedLabel): void
    {
        $this->assertEquals($expectedValue, $enum->value);
        $this->assertEquals($expectedLabel, $enum->getLabel());
    }

    public function testFromValidValue(): void
    {
        $this->assertEquals(CardColor::COLOR_010, CardColor::from('#63b359'));
        $this->assertEquals(CardColor::COLOR_050, CardColor::from('#9062c0'));
        $this->assertEquals(CardColor::COLOR_102, CardColor::from('#5E6671'));
    }

    public function testFromInvalidValueThrowsException(): void
    {
        $this->expectException(\ValueError::class);
        CardColor::from('#000000');
    }

    public function testTryFromValidValue(): void
    {
        $this->assertEquals(CardColor::COLOR_010, CardColor::tryFrom('#63b359'));
        $this->assertEquals(CardColor::COLOR_050, CardColor::tryFrom('#9062c0'));
        $this->assertEquals(CardColor::COLOR_102, CardColor::tryFrom('#5E6671'));
    }

    public function testTryFromInvalidValueReturnsNull(): void
    {
        $this->assertNull(CardColor::tryFrom('#000000'));
        $this->assertNull(CardColor::tryFrom('invalid'));
        $this->assertNull(CardColor::tryFrom('#ff'));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (CardColor $case) => $case->value, CardColor::cases());
        $this->assertCount(count(array_unique($values)), $values, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (CardColor $case) => $case->getLabel(), CardColor::cases());
        // CardColor has some duplicate labels (橙色, 深红色), this is expected
        $this->assertCount(14, $labels, 'Should have labels for all enum cases');
        $uniqueLabels = array_unique($labels);
        $this->assertCount(12, $uniqueLabels, 'Should have 12 unique labels with 2 duplicates');
    }

    public function testCasesCount(): void
    {
        $this->assertCount(14, CardColor::cases());
    }

    public function testColorValidation(): void
    {
        foreach (CardColor::cases() as $color) {
            $this->assertMatchesRegularExpression('/#[0-9a-fA-F]{6}/', $color->value);
        }
    }

    public function testToArray(): void
    {
        $result = CardColor::COLOR_010->toArray();

        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertEquals('#63b359', $result['value']);
        $this->assertEquals('淡绿色', $result['label']);
    }
}
