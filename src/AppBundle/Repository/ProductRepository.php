<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;

/**
 * Class ProductRepository
 */
class ProductRepository extends EntityRepository
{
    /**
     * Retrieve the products from their ID.
     *
     * @param array $idList
     *
     * @return Product[]
     */
    public function retrieveByIdList(array $idList)
    {
        $qb = $this->createQueryBuilder('p');

        $qb
            ->andWhere($qb->expr()->in('p.id', ':id_list'))
            ->setParameter('id_list', $idList)
        ;

        return $qb->getQuery()->getResult();
    }
}
