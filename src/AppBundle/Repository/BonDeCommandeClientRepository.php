<?php

namespace AppBundle\Repository;
use AppBundle\Entity\Client;
use AppBundle\Entity\ProduitBC;
use AppBundle\Entity\statutProduit;
use AppBundle\Search\PeriodCriteria;
use Doctrine\ORM\Query\Expr\Join;

/**
 * BonDeCommandeClientRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BonDeCommandeClientRepository extends \Doctrine\ORM\EntityRepository
{
    public function getRapportHebdomadaire($commercial)
    {

        $date = new \DateTime('last monday');

        return $this->createQueryBuilder('bc')
            ->select('bc', 'commercial', 'client')
            ->innerJoin('bc.client', 'client')
            ->innerJoin('bc.commercial', 'commercial')
            ->Where('commercial = :commercial')
            ->andWhere('bc.datedereception >= :min')
            ->andWhere('bc.datedereception <= :max')
            ->setParameters(array('commercial' => $commercial, 'min' => $date, 'max' => new \DateTime("now")))
            ->getQuery()->getResult();
    }


    public function findBCResteALivrer($commercial)
    {

        $in = $this->getEntityManager()->getRepository('AppBundle:ProduitBC')
            ->createQueryBuilder('produit')
            ->select('po.id')
            ->innerJoin('produit.statut', 'statut')
            ->innerJoin('produit.BonDeCommandeClient', 'po')
            ->where('statut.id != 6');

        $qb = $this->createQueryBuilder('bc');

        $qb->select('bc', 'client')
            ->innerJoin('bc.client', 'client')
            ->innerJoin('bc.commercial', 'commercial')
            ->Where('commercial = :commercial')
            ->andWhere($qb->expr()->in('bc.id', $in->getDQL()))
            ->setParameters(array('commercial' => $commercial));

        $query = $qb->getQuery()->getResult();

        return $query;
    }


    public function findBCRisqueRetard($commercial, $proximité)
    {


        $in = $this->getEntityManager()->getRepository('AppBundle:ProduitBC')
            ->createQueryBuilder('produit')
            ->select('po.id')
            ->innerJoin('produit.statut', 'statut')
            ->innerJoin('produit.BonDeCommandeClient', 'po')
            ->where('statut.id != 6');

        $qb = $this->createQueryBuilder('bc');

        $qb->select('bc', 'client')
            ->innerJoin('bc.client', 'client')
            ->innerJoin('bc.commercial', 'commercial')
            ->Where('commercial = :commercial')
            ->andWhere($qb->expr()->in('bc.id', $in->getDQL()))
            ->andWhere('DATE_SUB(DATE_ADD(bc.datedereception,bc.echeance, \'day\'),:proximite,\'day\') <= CURRENT_TIMESTAMP()')
            ->andWhere('DATE_ADD(bc.datedereception,bc.echeance, \'day\') >= CURRENT_TIMESTAMP()')
            ->setParameters(array('commercial' => $commercial, 'proximite' => $proximité));

        $query = $qb->getQuery()->getResult();

        // dump($qb->getQuery());

        return $query;
    }


    public function findBCRetard($commercial)
    {


        $in = $this->getEntityManager()->getRepository('AppBundle:ProduitBC')
            ->createQueryBuilder('produit')
            ->select('po.id')
            ->innerJoin('produit.statut', 'statut')
            ->innerJoin('produit.BonDeCommandeClient', 'po')
            ->where('statut.id != 6');

        $qb = $this->createQueryBuilder('bc');

        $qb->select('bc', 'client')
            ->innerJoin('bc.client', 'client')
            ->innerJoin('bc.commercial', 'commercial')
            ->Where('commercial = :commercial')
            ->andWhere($qb->expr()->in('bc.id', $in->getDQL()))
            ->andWhere('DATE_ADD(bc.datedereception,bc.echeance, \'day\') < CURRENT_TIMESTAMP()')
            ->setParameters(array('commercial' => $commercial));

        $query = $qb->getQuery()->getResult();

        dump($qb->getQuery());

        return $query;
    }

    public function findCmdNotDelivered()
    {
        return $this->createQueryBuilder('cmd')
            ->join(ProduitBC::class,'p', 'WITH', 'p.documentClient = cmd.id')
            ->innerJoin('p.statut', 'st')
            ->where('st.id in (:ids)')
            ->setParameter('ids',statutProduit::STATUT_A_LIVRER)
            ->getQuery()
            ->getResult();
    }

    public function findBcByPeriod(PeriodCriteria $criteria)
    {
        $qb = $this->createQueryBuilder('bdcc')
            ->innerJoin('bdcc.client', 'client')
            ->where('client.id != :clientId')->setParameter('clientId', Client::SOLDATA_ID);

        if (!empty($criteria->getStartDate())) {
            $qb->andWhere('bdcc.datecreation >= :startDate')
                ->setParameter('startDate', $criteria->getStartDate());
        }

        if (!empty($criteria->getStartDate())) {
            $qb->andWhere('bdcc.datecreation <= :endDate')
                ->setParameter('endDate', $criteria->getEndDate());
        }

        return $qb->getQuery()->getResult();
    }



// SELECT `bon_de_commande_client`.`datedereception`,`bon_de_commande_client`.`echeance`, NOW(),
// DATE_SUB(DATE_ADD(`bon_de_commande_client`.`datedereception`, INTERVAL `bon_de_commande_client`.`echeance` DAY),INTERVAL ROUND(`bon_de_commande_client`.`echeance` / 2) DAY) AS "MIN", 
// DATE_ADD(`bon_de_commande_client`.`datedereception`, INTERVAL `bon_de_commande_client`.`echeance` DAY) AS "MAX" 
// FROM `bon_de_commande_client`
// WHERE NOW() >= DATE_SUB(DATE_ADD(`bon_de_commande_client`.`datedereception`, INTERVAL `bon_de_commande_client`.`echeance` DAY),INTERVAL ROUND(`bon_de_commande_client`.`echeance` / 2) DAY) AND
// NOW() <= DATE_ADD(`bon_de_commande_client`.`datedereception`, INTERVAL `bon_de_commande_client`.`echeance` DAY)

}
