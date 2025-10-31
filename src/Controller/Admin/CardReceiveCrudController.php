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
use WechatOfficialAccountCardBundle\Entity\CardReceive;

/**
 * 微信卡券领取记录管理控制器
 *
 * @extends AbstractCrudController<CardReceive>
 */
#[AdminCrud(routePath: '/wechat-card/receive', routeName: 'wechat_card_receive')]
final class CardReceiveCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CardReceive::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            AssociationField::new('card', '关联卡券')
                ->setRequired(true)
                ->setHelp('选择关联的卡券'),

            AssociationField::new('cardCode', '卡券码')
                ->setRequired(true)
                ->setHelp('选择关联的卡券码'),

            TextField::new('openId', '用户OpenID')
                ->setRequired(true)
                ->setHelp('微信用户的唯一标识符')
                ->setMaxLength(50),

            BooleanField::new('isUsed', '已使用')
                ->setHelp('此卡券是否已被用户使用'),

            DateTimeField::new('usedAt', '使用时间')
                ->hideOnForm()
                ->setHelp('用户使用卡券的时间')
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            BooleanField::new('isUnavailable', '已失效')
                ->setHelp('此卡券是否已失效'),

            DateTimeField::new('unavailableAt', '失效时间')
                ->hideOnForm()
                ->setHelp('卡券失效的时间')
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            BooleanField::new('isGiven', '已转赠')
                ->setHelp('此卡券是否已被转赠给其他用户'),

            DateTimeField::new('givenAt', '转赠时间')
                ->hideOnForm()
                ->setHelp('卡券转赠的时间')
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            TextField::new('givenToOpenId', '转赠目标用户')
                ->setRequired(false)
                ->setHelp('接收转赠卡券的用户OpenID')
                ->setMaxLength(50),

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
            ->add('cardCode')
            ->add('openId')
            ->add('isUsed')
            ->add('isUnavailable')
            ->add('isGiven')
            ->add('givenToOpenId')
            ->add('createTime')
            ->add('updateTime')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('领取记录')
            ->setEntityLabelInPlural('领取记录')
            ->setDefaultSort(['createTime' => 'DESC'])
            ->setPaginatorPageSize(20)
        ;
    }
}
