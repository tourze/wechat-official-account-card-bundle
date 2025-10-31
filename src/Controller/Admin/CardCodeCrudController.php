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
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use WechatOfficialAccountCardBundle\Entity\CardCode;

/**
 * 微信卡券码管理控制器
 *
 * @extends AbstractCrudController<CardCode>
 */
#[AdminCrud(routePath: '/wechat-card/code', routeName: 'wechat_card_code')]
final class CardCodeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CardCode::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            AssociationField::new('card', '关联卡券')
                ->setRequired(true)
                ->setHelp('选择关联的卡券'),

            TextField::new('code', '卡券码')
                ->setRequired(true)
                ->setHelp('微信卡券的唯一识别码')
                ->setMaxLength(50),

            BooleanField::new('isUsed', '已使用')
                ->setHelp('此卡券码是否已被使用'),

            DateTimeField::new('usedAt', '使用时间')
                ->hideOnForm()
                ->setHelp('卡券码的使用时间')
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            BooleanField::new('isUnavailable', '已失效')
                ->setHelp('此卡券码是否已失效'),

            DateTimeField::new('unavailableAt', '失效时间')
                ->hideOnForm()
                ->setHelp('卡券码的失效时间')
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

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
            ->add('card')
            ->add('code')
            ->add('isUsed')
            ->add('isUnavailable')
            ->add('createTime')
            ->add('updateTime')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('卡券码')
            ->setEntityLabelInPlural('卡券码')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setPaginatorPageSize(20)
        ;
    }
}
