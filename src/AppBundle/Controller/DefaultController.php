<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController.
 *
 * @author Artur Vesker
 */
class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return;
    }

    /**
     * @Route("/fignya", name="fignya")
     * @Method("GET")
     * @Template()
     */
    public function fignyaAction(Request $request)
    {
        $tours = $this->getDoctrine()->getRepository('AppBundle:Tour');

        return array(
            'tours' => $tours,
        );
    }
}
