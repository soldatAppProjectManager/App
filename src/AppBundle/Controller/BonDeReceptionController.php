<?php

namespace AppBundle\Controller;

use AppBundle\Entity\BonDeReception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Bondereception controller.
 *
 * @Route("reception")
 */
class BonDeReceptionController extends Controller
{
    /**
     * Lists all bonDeReception entities.
     *
     * @Route("/", name="reception_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $bonDeReceptions = $em->getRepository('AppBundle:BonDeReception')->findAll();

        return $this->render('bondereception/index.html.twig', array(
            'bonDeReceptions' => $bonDeReceptions,
        ));
    }

    /**
     * Finds and displays a bonDeReception entity.
     *
     * @Route("/{id}", name="reception_show")
     * @Method("GET")
     */
    public function showAction(BonDeReception $bonDeReception)
    {

        return $this->render('bondereception/show.html.twig', array(
            'bonDeReception' => $bonDeReception,
        ));
    }
}
