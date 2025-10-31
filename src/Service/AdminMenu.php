<?php

declare(strict_types=1);

namespace WechatOfficialAccountCardBundle\Service;

use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Tourze\EasyAdminMenuBundle\Service\LinkGeneratorInterface;
use Tourze\EasyAdminMenuBundle\Service\MenuProviderInterface;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Entity\CardCode;
use WechatOfficialAccountCardBundle\Entity\CardReceive;
use WechatOfficialAccountCardBundle\Entity\CardStat;

/**
 * 微信公众号卡券管理后台菜单提供者
 */
#[Autoconfigure(public: true)]
readonly class AdminMenu implements MenuProviderInterface
{
    public function __construct(
        private LinkGeneratorInterface $linkGenerator,
    ) {
    }

    public function __invoke(ItemInterface $item): void
    {
        if (null === $item->getChild('微信管理')) {
            $item->addChild('微信管理');
        }

        $wechatMenu = $item->getChild('微信管理');
        if (null === $wechatMenu) {
            return;
        }

        // 添加卡券管理子菜单
        if (null === $wechatMenu->getChild('卡券管理')) {
            $wechatMenu->addChild('卡券管理')
                ->setAttribute('icon', 'fas fa-ticket-alt')
            ;
        }

        $cardMenu = $wechatMenu->getChild('卡券管理');
        if (null === $cardMenu) {
            return;
        }

        $cardMenu->addChild('卡券列表')
            ->setUri($this->linkGenerator->getCurdListPage(Card::class))
            ->setAttribute('icon', 'fas fa-credit-card')
        ;

        $cardMenu->addChild('卡券码管理')
            ->setUri($this->linkGenerator->getCurdListPage(CardCode::class))
            ->setAttribute('icon', 'fas fa-qrcode')
        ;

        $cardMenu->addChild('领取记录')
            ->setUri($this->linkGenerator->getCurdListPage(CardReceive::class))
            ->setAttribute('icon', 'fas fa-hand-holding')
        ;

        $cardMenu->addChild('统计数据')
            ->setUri($this->linkGenerator->getCurdListPage(CardStat::class))
            ->setAttribute('icon', 'fas fa-chart-bar')
        ;
    }
}
