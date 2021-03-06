<?php

namespace AppBundle\Repository;

/**
 * FighterRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FighterRepository extends \Doctrine\ORM\EntityRepository
{
    public function listWithSkillsOld($id)
    {
        return $this->getEntityManager()->createQuery(
            'SELECT f , fs, s
            FROM AppBundle:Fighter f
            JOIN f.fighter_skill fs
            JOIN fs.skill s
            WHERE f.id = :id
            ORDER BY f.health ASC'
        )
            ->setParameter('id', $id)
            ->getResult();
    }

    public function listWithSkills($id)
    {
        return $this
            ->createQueryBuilder('f')
            ->leftJoin('f.skills','s')
            ->addSelect('s')
            ->where('f.id = :id')
            ->setParameter('id',$id)
            ->getQuery()
            ->getResult();
    }

    public function findFightersNotInGameId($gameId)
    {
        $notIn = $this
            ->createQueryBuilder('f')
            ->join('f.gameFighters','gf')
            ->where('gf.gameId = :id')
            ->getQuery();

        return $this
            ->createQueryBuilder('ff')
            ->where( $this->createQueryBuilder('ff')->expr()->notIn('ff.id',$notIn->getDQL()) )
            ->setParameter('id',$gameId)
            ->getQuery()
            ->getResult();

    }

    public function findFightersInGameId($gameId)
    {
        return $this
            ->createQueryBuilder('f')
            ->join('f.gameFighters','gf')
            ->where('gf.gameId = :id')
            ->setParameter('id',$gameId)
            ->getQuery()
            ->getResult();
    }
}
