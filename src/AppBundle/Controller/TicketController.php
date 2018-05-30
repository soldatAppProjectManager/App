<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

use AppBundle\Entity\Ticket;
use AppBundle\Entity\TicketStatus;
use AppBundle\Form\TicketHistoryType;
use AppBundle\Form\TicketType;
use AppBundle\Service\TicketManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Ticket controller.
 *
 * @Route("ticket")
 */
class TicketController extends Controller
{
    /**
     * Lists all ticket entities.
     *
     * @Route("/", name="ticket_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tickets = $em->getRepository('AppBundle:Ticket')->findAll();

        return $this->render('ticket/index.html.twig', array(
            'tickets' => $tickets,
        ));
    }

    /**
     * Creates a new ticket entity.
     *
     * @Route("/new", name="ticket_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, TicketManager $ticketManager)
    {
        $em = $this->getDoctrine()->getManager();
        $ticket = $ticketManager->createTicket();

        $form = $this->createForm(TicketType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($form->getData()->getFile()->getFile() !== null) {
                $ticket->getFile()->upload($this->getParameter('repertoire_ticket'));
            } else {
                $ticket->setFile(null);
            }
            $em->persist($ticket);
            $em->flush();

            return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
        }

        return $this->render('ticket/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param TicketManager $ticketManager
     * @param Ticket $ticket
     * @param TicketStatus $ticketStatus
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/{id}/history/{status_code}", name="ticket_new_history", defaults={"status_code" = null }, requirements={"id"="\d+", "status_code"="\d+"})
     * @ParamConverter("ticketStatus", options={"mapping": {"status_code": "code"}})
     * @Method({"GET", "POST"})
     */
    public function newHistoryAction(
        Request $request,
        TicketManager $ticketManager,
        Ticket $ticket,
        TicketStatus $ticketStatus = null
    )
    {
        $ticketHistory = $ticketManager->createTicketHistory($ticket, $ticketStatus);
        $ticketHistory->setAffectedTo($this->getUser());
        $form = $this->createForm(TicketHistoryType::class, $ticketHistory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ticket->addHistory($ticketHistory);
            $ticketManager->em->flush();

            return $this->redirectToRoute('ticket_show', ['id' => $ticket->getId()]);
        }

        return $this->render('ticket/new_history.html.twig', [
            'ticketHistory' => $ticketHistory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Finds and displays a ticket entity.
     *
     * @Route("/{id}", name="ticket_show")
     * @Method("GET")
     */
    public function showAction(Ticket $ticket)
    {
        $deleteForm = $this->createDeleteForm($ticket);

        return $this->render('ticket/show.html.twig', array(
            'ticket' => $ticket,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ticket entity.
     *
     * @Route("/{id}/edit", name="ticket_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Ticket $ticket)
    {
        $form = $this->createForm('AppBundle\Form\TicketType', $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->getData()->getFile()->getFile() !== null) {
                $ticket->getFile()->upload($this->getParameter('repertoire_ticket'));
            } elseif (null === $ticket->getFile()->getId()) {
                $ticket->setFile(null);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ticket_show', array('id' => $ticket->getId()));
        }

        return $this->render('ticket/edit.html.twig', array(
            'ticket' => $ticket,
            'form' => $form->createView(),
        ));
    }

    /**
     * Deletes a ticket entity.
     *
     * @Route("/{id}", name="ticket_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Ticket $ticket)
    {
        $form = $this->createDeleteForm($ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($ticket);
            $em->flush();
            $this->addFlash('notice', 'Ticket supprimé');
        }

        return $this->redirectToRoute('ticket_index');
    }

    /**
     * Creates a form to delete a ticket entity.
     *
     * @param Ticket $ticket The ticket entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Ticket $ticket)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('ticket_delete', array('id' => $ticket->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
