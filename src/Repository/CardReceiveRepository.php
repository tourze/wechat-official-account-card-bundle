<?php

namespace WechatOfficialAccountCardBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use WechatOfficialAccountCardBundle\Entity\CardReceive;

/**
 * @method CardReceive|null find($id, $lockMode = null, $lockVersion = null)
 * @method CardReceive|null findOneBy(array $criteria, array $orderBy = null)
 * @method CardReceive[]    findAll()
 * @method CardReceive[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CardReceiveRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CardReceive::class);
    }
}
