<?php

declare(strict_types=1);

namespace WechatOfficialAccountCardBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use WechatOfficialAccountCardBundle\Entity\CardStat;

/**
 * 微信卡券统计数据管理控制器
 *
 * @extends AbstractCrudController<CardStat>
 */
#[AdminCrud(routePath: '/wechat-card/stat', routeName: 'wechat_card_stat')]
final class CardStatCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return CardStat::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $fields = [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            AssociationField::new('card', '关联卡券')
                ->setHelp('选择关联的卡券'),

            DateField::new('statsDate', '统计日期')
                ->setHelp('此统计数据对应的日期')
                ->setFormat('yyyy-MM-dd'),

            DateTimeField::new('createTime', '创建时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            DateTimeField::new('updateTime', '更新时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),
        ];

        // 只在详情页面显示所有字段，列表页面只显示基本信息
        if (Crud::PAGE_DETAIL === $pageName) {
            $fields[] = IntegerField::new('receiveCount', '领取次数')
                ->setHelp('当日卡券被领取的次数')
                ->setFormTypeOptions(['attr' => ['min' => 0]])
            ;

            $fields[] = IntegerField::new('useCount', '使用次数')
                ->setHelp('当日卡券被使用的次数')
                ->setFormTypeOptions(['attr' => ['min' => 0]])
            ;

            $fields[] = IntegerField::new('giveCount', '转赠次数')
                ->setHelp('当日卡券被转赠的次数')
                ->setFormTypeOptions(['attr' => ['min' => 0]])
            ;

            $fields[] = IntegerField::new('viewCount', '浏览次数')
                ->setHelp('当日卡券页面被浏览的次数')
                ->setFormTypeOptions(['attr' => ['min' => 0]])
            ;

            $fields[] = IntegerField::new('newFollowCount', '新增关注数')
                ->setHelp('通过此卡券新增关注的用户数')
                ->setFormTypeOptions(['attr' => ['min' => 0]])
            ;

            $fields[] = IntegerField::new('unfollowCount', '取消关注数')
                ->setHelp('当日取消关注的用户数')
                ->setFormTypeOptions(['attr' => ['min' => 0]])
            ;

            $fields[] = IntegerField::new('giveReceiveCount', '转赠领取数')
                ->setHelp('当日通过转赠方式领取卡券的次数')
                ->setFormTypeOptions(['attr' => ['min' => 0]])
            ;
        }

        return $fields;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('card')
            ->add('statsDate')
            ->add('receiveCount')
            ->add('useCount')
            ->add('giveCount')
            ->add('viewCount')
            ->add('newFollowCount')
            ->add('unfollowCount')
            ->add('giveReceiveCount')
            ->add('createTime')
            ->add('updateTime')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable('edit', 'new', 'delete')
        ;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('统计数据')
            ->setEntityLabelInPlural('统计数据')
            ->setDefaultSort(['statsDate' => 'DESC', 'createTime' => 'DESC'])
            ->setPaginatorPageSize(20)
            ->showEntityActionsInlined()
            ->setSearchFields(['id', 'card.cardId', 'statsDate'])
        ;
    }
}
