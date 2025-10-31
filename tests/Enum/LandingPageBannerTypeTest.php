<?php

namespace WechatOfficialAccountCardBundle\Tests\Enum;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestWith;
use Tourze\PHPUnitEnum\AbstractEnumTestCase;
use WechatOfficialAccountCardBundle\Enum\LandingPageBannerType;

/**
 * @internal
 */
#[CoversClass(LandingPageBannerType::class)]
final class LandingPageBannerTypeTest extends AbstractEnumTestCase
{
    #[TestWith([LandingPageBannerType::URL, 0, '图文消息场景'])]
    #[TestWith([LandingPageBannerType::BANNER, 1, '朋友圈场景'])]
    #[TestWith([LandingPageBannerType::CELL, 2, '单张卡券页面场景'])]
    public function testValueAndLabel(LandingPageBannerType $enum, int $expectedValue, string $expectedLabel): void
    {
        $this->assertEquals($expectedValue, $enum->value);
        $this->assertEquals($expectedLabel, $enum->getLabel());
    }

    #[TestWith([0, LandingPageBannerType::URL])]
    #[TestWith([1, LandingPageBannerType::BANNER])]
    #[TestWith([2, LandingPageBannerType::CELL])]
    public function testFromValidValue(int $value, LandingPageBannerType $expected): void
    {
        $this->assertEquals($expected, LandingPageBannerType::from($value));
    }

    #[TestWith([999])]
    #[TestWith([-1])]
    #[TestWith([3])]
    public function testFromInvalidValueThrowsException(int $invalidValue): void
    {
        $this->expectException(\ValueError::class);
        LandingPageBannerType::from($invalidValue);
    }

    #[TestWith([0, LandingPageBannerType::URL])]
    #[TestWith([1, LandingPageBannerType::BANNER])]
    #[TestWith([2, LandingPageBannerType::CELL])]
    public function testTryFromValidValue(int $value, LandingPageBannerType $expected): void
    {
        $this->assertEquals($expected, LandingPageBannerType::tryFrom($value));
    }

    #[TestWith([999])]
    #[TestWith([-1])]
    #[TestWith([3])]
    public function testTryFromInvalidValueReturnsNull(int $invalidValue): void
    {
        $this->assertNull(LandingPageBannerType::tryFrom($invalidValue));
    }

    public function testValueUniqueness(): void
    {
        $values = array_map(fn (LandingPageBannerType $case) => $case->value, LandingPageBannerType::cases());
        $this->assertCount(count(array_unique($values)), $values, 'All enum values must be unique');
    }

    public function testLabelUniqueness(): void
    {
        $labels = array_map(fn (LandingPageBannerType $case) => $case->getLabel(), LandingPageBannerType::cases());
        $uniqueLabels = array_unique($labels);
        $this->assertCount(count($uniqueLabels), $labels, 'All enum labels must be unique');
    }

    public function testCasesCount(): void
    {
        $this->assertCount(3, LandingPageBannerType::cases());
    }

    public function testToArray(): void
    {
        $result = LandingPageBannerType::URL->toArray();

        $this->assertIsArray($result);
        $this->assertArrayHasKey('value', $result);
        $this->assertArrayHasKey('label', $result);
        $this->assertEquals(0, $result['value']);
        $this->assertEquals('图文消息场景', $result['label']);
    }
}
