<?php

namespace AppBundle\Event;

use Doctrine\Common\EventSubscriber;
use AppBundle\Entity\Devis;
use AppBundle\Entity\LigneDevis;
use Doctrine\ORM\Event\LifecycleEventArgs;


class DevisListener implements EventSubscriber
{
    // private $passwordEncoder;

    // public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    // {
    //     $this->passwordEncoder = $passwordEncoder;
    // }

    public function getSubscribedEvents()
    {
    	return ['postLoad'];
    }

    public function postLoad(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof Devis) {
            return;
        }

        $ligne = new LigneDevis;
        $lignes = [];

        $ligne->setNumero(999);
        $ligne->setQuantite("69");
        $ligne->setDesignation("Bidon (gros)");
        $ligne->setDescription("Completement bidon, pas beau l'avion");
        $ligne->setPrixunitaire("99");
        $ligne->setSoustotalht("6999");
        $ligne->setGaranties("3 ans");
        $ligne->setTermes("Sur stock");

        $lignes[]=$ligne;

        $entity->setLignes($lignes);

        // dump($ligne);dump($entity); die();

        // necessary to force the update to see the change
        // $em = $args->getEntityManager();
        // $meta = $em->getClassMetadata(get_class($entity));
        // $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }
}