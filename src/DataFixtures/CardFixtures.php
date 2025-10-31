<?php

namespace WechatOfficialAccountCardBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use WechatOfficialAccountBundle\DataFixtures\AccountFixtures;
use WechatOfficialAccountBundle\Entity\Account;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;

#[When(env: 'test')]
#[When(env: 'dev')]
class CardFixtures extends Fixture implements DependentFixtureInterface
{
    public const CARD_BASIC_REFERENCE = 'card-basic';
    public const CARD_GROUPON_REFERENCE = 'card-groupon';
    public const CARD_CASH_REFERENCE = 'card-cash';
    public const CARD_DISCOUNT_REFERENCE = 'card-discount';
    public const CARD_MEMBER_REFERENCE = 'card-member';

    public function load(ObjectManager $manager): void
    {
        /** @var Account $account */
        $account = $this->getReference(AccountFixtures::ACCOUNT_REFERENCE, Account::class);

        // 创建基础卡券
        $cardBasic = new Card();
        $cardBasic->setAccount($account);
        $cardBasic->setCardId('test_card_basic_' . uniqid());
        $cardBasic->setCardType(CardType::GROUPON);
        $cardBasic->setStatus(CardStatus::VERIFY_OK);
        // 设置为syncing=true以跳过CardListener的API调用
        $cardBasic->setSyncing(true);

        // 设置baseInfo必需字段
        $baseInfo = $cardBasic->getBaseInfo();
        $baseInfo->setLogoUrl('https://images.unsplash.com/photo-1592417817098-8fd3d9eb14a5');
        $baseInfo->setBrandName('测试商户');
        $baseInfo->setTitle('测试卡券');
        $baseInfo->setCodeType(CodeType::CODE_TYPE_TEXT);
        $baseInfo->setColor(CardColor::COLOR_010);
        $baseInfo->setNotice('测试使用提醒');
        $baseInfo->setDescription('测试使用说明');
        $baseInfo->setQuantity(1000);
        $baseInfo->setUseLimit(1);
        $baseInfo->setGetLimit(1);

        $cardBasic->setCreatedBy('system');
        $cardBasic->setUpdatedBy('system');

        $manager->persist($cardBasic);
        $this->addReference(self::CARD_BASIC_REFERENCE, $cardBasic);

        // 创建团购券
        $cardGroupon = new Card();
        $cardGroupon->setAccount($account);
        $cardGroupon->setCardId('test_card_groupon_' . uniqid());
        $cardGroupon->setCardType(CardType::GROUPON);
        $cardGroupon->setStatus(CardStatus::VERIFY_OK);
        // 设置为syncing=true以跳过CardListener的API调用
        $cardGroupon->setSyncing(true);

        // 设置baseInfo必需字段
        $baseInfoGroupon = $cardGroupon->getBaseInfo();
        $baseInfoGroupon->setLogoUrl('https://images.unsplash.com/photo-1592417817098-8fd3d9eb14a5');
        $baseInfoGroupon->setBrandName('测试商户');
        $baseInfoGroupon->setTitle('测试团购券');
        $baseInfoGroupon->setCodeType(CodeType::CODE_TYPE_TEXT);
        $baseInfoGroupon->setColor(CardColor::COLOR_010);
        $baseInfoGroupon->setNotice('测试使用提醒');
        $baseInfoGroupon->setDescription('测试使用说明');
        $baseInfoGroupon->setQuantity(1000);
        $baseInfoGroupon->setUseLimit(1);
        $baseInfoGroupon->setGetLimit(1);

        $cardGroupon->setCreatedBy('system');
        $cardGroupon->setUpdatedBy('system');

        $manager->persist($cardGroupon);
        $this->addReference(self::CARD_GROUPON_REFERENCE, $cardGroupon);

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [AccountFixtures::class];
    }
}
