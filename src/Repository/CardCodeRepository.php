<?php

namespace WechatOfficialAccountCardBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DoctrineEnhanceBundle\Repository\CommonRepositoryAware;
use WechatOfficialAccountCardBundle\Entity\CardCode;

/**
 * @method CardCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardCode[]    findAll()
 * @method CardCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardCodeRepository extends ServiceEntityRepository
{
    use CommonRepositoryAware;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardCode::class);
    }
}
