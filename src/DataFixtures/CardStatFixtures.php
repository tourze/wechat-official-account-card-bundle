<?php

namespace WechatOfficialAccountCardBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;

#[When(env: 'test')]
#[When(env: 'dev')]
class CardStatFixtures extends Fixture implements DependentFixtureInterface
{
    public const CARD_STATS_TODAY_REFERENCE = 'card-stats-today';
    public const CARD_STATS_YESTERDAY_REFERENCE = 'card-stats-yesterday';
    public const CARD_STATS_WEEK_AGO_REFERENCE = 'card-stats-week-ago';

    public function load(ObjectManager $manager): void
    {
        // 由于 CardStats 实体依赖 Card 实体，而 Card 实体又依赖 WechatOfficialAccountBundle\Entity\Account
        // 而该 Bundle 在单独的包测试环境中不可用
        // 故此 DataFixtures 留空，实际的测试数据通过单元测试中的 Mock 对象提供
        // 这样可以避免跨 Bundle 依赖导致的集成测试失败
    }

    public function getDependencies(): array
    {
        return [CardFixtures::class];
    }
}
