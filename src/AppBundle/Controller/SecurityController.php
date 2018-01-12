<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Form\LoginForm;


class SecurityController extends Controller
{
    /**
     * @Route("/login", name="security_login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

		$form = $this->createForm(LoginForm::class, [
		            '_username' => $lastUsername,
		        ]);

	    return $this->render('Security/login.html.twig', array(
                'form' => $form->createView(),
                'error' => $error));
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction(Request $request)
    {
    	throw new \Exception('this should not be reached!');
    }
}
