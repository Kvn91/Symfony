<?php

namespace Kevin\PlatformBundle\Repository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Kevin\PlatformBundle\Entity\Advert;

/**
 * AdvertRepository'
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AdvertRepository extends \Doctrine\ORM\EntityRepository
{
    public function getAdverts($page)
    {
        $query = $this->createQueryBuilder('a')
            ->leftJoin('a.image', 'i')
            ->addSelect('i')
            ->leftJoin('a.categories', 'c')
            ->addSelect('c')
            ->leftJoin('a.advertSkills', 'ads')
            ->addSelect('ads')
            ->orderBy('a.date', 'DESC')
            ->getQuery()
        ;

        $query->setFirstResult(($page-1) * advert::NB_ADVERTS_PER_PAGE)
            ->setMaxResults(advert::NB_ADVERTS_PER_PAGE);

        return new Paginator($query, true);
    }
}
