<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Tour;
use AppBundle\Entity\Category;
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
     * @param Tour $tour
     * @return array
     */
    public function tourAction(Tour $tour)
    {
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')->findBy(array(
            'tour' => $tour->getId(),
        ));
        $questions  = $this->getDoctrine()->getRepository('AppBundle:Question')->findAll();

        $query = $this->getDoctrine()->getRepository('AppBundle:Question')->createQueryBuilder('q')
            ->where("q.category IN(:categories)")
            ->andWhere('q.done = 0')
            ->setParameter('categories', $categories)
            ->getQuery();

        $questionsNotDone = $query->getResult();

        $thisTourIsDone = false;
        if (null == $questionsNotDone) {
            $thisTourIsDone = true;
        }

        $nextTour = $this->getDoctrine()->getRepository('AppBundle:Tour')->findOneBy(array(
            'id' => $tour->getId() + 1,
        ));

        return array(
            'tour_is_done' => $thisTourIsDone,
            'next_tour'    => $nextTour,
            'categories'   => $categories,
            'questions'    => $questions,
            'tour'         => $tour,
        );
    }

    /**
     * @Route("/fignya/{category_slug}/{cost}", name="question")
     * @Method("GET")
     * @ParamConverter("category", options={"mapping": {"category_slug": "slug"}})
     * @Template()
     * @param Category $category
     * @param $cost
     * @return array
     */
    public function questionAction(Category $category, $cost)
    {
        $question = $this->getDoctrine()->getRepository('AppBundle:Question')->findOneBy(array(
            'category' => $category,
            'cost'     => $cost,
        ));

        return array(
            'category' => $category,
            'question' => $question,
        );
    }

    /**
     * @Route("/fignya/{category_slug}/{cost}/answer", name="answer")
     * @Method("GET")
     * @ParamConverter("category", options={"mapping": {"category_slug": "slug"}})
     * @Template()
     * @param Category $category
     * @param $cost
     * @return array
     */
    public function answerAction(Category $category, $cost)
    {
        $question = $this->getDoctrine()->getRepository('AppBundle:Question')->findOneBy(array(
            'category' => $category,
            'cost'     => $cost,
        ));

        $question->setDone(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($question);
        $em->flush();

        $tour = $category->getTour();

        return array(
            'tour'     => $tour,
            'category' => $category,
            'question' => $question,
        );
    }
}
