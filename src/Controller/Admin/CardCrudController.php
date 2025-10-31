<?php

declare(strict_types=1);

namespace WechatOfficialAccountCardBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use Tourze\EasyAdminEnumFieldBundle\Field\EnumField;
use WechatOfficialAccountCardBundle\Entity\Card;
use WechatOfficialAccountCardBundle\Enum\CardColor;
use WechatOfficialAccountCardBundle\Enum\CardStatus;
use WechatOfficialAccountCardBundle\Enum\CardType;
use WechatOfficialAccountCardBundle\Enum\CodeType;

/**
 * 微信卡券管理控制器
 *
 * @extends AbstractCrudController<Card>
 */
#[AdminCrud(routePath: '/wechat-card/card', routeName: 'wechat_card_card')]
final class CardCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Card::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $cardTypeField = EnumField::new('cardType', '卡券类型')
            ->setRequired(true)
            ->setHelp('选择卡券的类型')
        ;
        $cardTypeField->setEnumCases(CardType::cases());

        $statusField = EnumField::new('status', '状态')
            ->setRequired(true)
            ->setHelp('卡券的当前状态')
        ;
        $statusField->setEnumCases(CardStatus::cases());

        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            AssociationField::new('account', '微信公众号')
                ->setRequired(true)
                ->setHelp('选择关联的微信公众号账户'),

            TextField::new('cardId', '卡券ID')
                ->setRequired(true)
                ->setHelp('微信返回的唯一卡券标识符')
                ->setMaxLength(50),

            $cardTypeField,

            $statusField,

            BooleanField::new('syncing', '同步中')
                ->setHelp('是否正在与微信服务器同步'),

            DateTimeField::new('createTime', '创建时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            DateTimeField::new('updateTime', '更新时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),
        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('account')
            ->add('cardId')
            ->add('cardType')
            ->add('status')
            ->add('syncing')
            ->add('createTime')
            ->add('updateTime')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('卡券')
            ->setEntityLabelInPlural('卡券')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setPaginatorPageSize(20)
        ;
    }
}
