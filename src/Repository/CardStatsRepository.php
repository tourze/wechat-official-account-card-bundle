<?php

namespace WechatOfficialAccountCardBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatOfficialAccountCardBundle\Entity\CardStats;

/**
 * @method CardStats|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardStats|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardStats[]    findAll()
 * @method CardStats[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardStatsRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardStats::class);
    }
}
