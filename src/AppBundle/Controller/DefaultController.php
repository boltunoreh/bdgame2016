<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tour;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
    public function fignyaAction()
    {
        $tours = $this->getDoctrine()->getRepository('AppBundle:Tour')->findAll();

        return array(
            'tours' => $tours,
        );
    }

    /**
     * @Route("/fignya/{tour_slug}", name="tour")
     * @Method("GET")
     * @ParamConverter("tour", options={"mapping": {"tour_slug": "slug"}})
     * @Template()
     */
    public function tourAction(Tour $tour)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findBy(array(
            'tour' => $tour->getId(),
        ));
        $questions  = $this->getDoctrine()->getRepository('AppBundle:Question')->findAll();

        return array(
            'categories' => $categories,
            'questions'  => $questions,
            'tour'       => $tour,
        );
    }
}
