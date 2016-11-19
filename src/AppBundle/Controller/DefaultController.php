<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Hint;
use AppBundle\Entity\Question;
use AppBundle\Entity\Team;
use AppBundle\Entity\Tour;
use AppBundle\Entity\Category;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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
     * @Route("/fignya/hint/team_{team_number}/{hint_slug}/{question_id}", name="hint")
     * @ParamConverter("team", options={"mapping": {"team_number": "number"}})
     * @ParamConverter("hint", options={"mapping": {"hint_slug": "slug"}})
     * @ParamConverter("question", options={"mapping": {"question_id": "id"}})
     * @Template()
     * @param Team $team
     * @param Hint $hint
     * @param Question $question
     * @return array
     */
    public function hintAction(Team $team, Hint $hint, Question $question)
    {
            switch ($team->getNumber()) {
                case 'One':
                    $hint->setTeamOneUsed(true);
                    break;
                case 'Two':
                    $hint->setTeamTwoUsed(true);
                    break;
                case 'Three':
                    $hint->setTeamThreeUsed(true);
                    break;
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($hint);
            $em->flush();

        return array(
            'question' => $question,
            'hint'     => $hint,
        );
    }

    /**
     * @Route("/fignya/tour/{tour_slug}", name="tour")
     * @Method("GET")
     * @ParamConverter("tour", options={"mapping": {"tour_slug": "slug"}})
     * @Template()
     * @param Tour $tour
     * @return array
     */
    public function tourAction(Tour $tour)
    {
        $teams = $this->getDoctrine()->getRepository('AppBundle:Team')->findAll();

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
            'teams'        => $teams,
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
        $hints = $this->getDoctrine()->getRepository('AppBundle:Hint')->findAll();
        $teams = $this->getDoctrine()->getRepository('AppBundle:Team')->findAll();

        $question = $this->getDoctrine()->getRepository('AppBundle:Question')->findOneBy(array(
            'category' => $category,
            'cost'     => $cost,
        ));

        return array(
            'category' => $category,
            'question' => $question,
            'teams'    => $teams,
            'hints'    => $hints,
        );
    }

    /**
     * @Route("/fignya/{category_slug}/{cost}/answer", name="answer")
     * @ParamConverter("category", options={"mapping": {"category_slug": "slug"}})
     * @Template()
     * @param Request $request
     * @param Category $category
     * @param $cost
     * @return array
     */
    public function answerAction(Request $request, Category $category, $cost)
    {
        $teams = $this->getDoctrine()->getRepository('AppBundle:Team')->findAll();
        $teamsArray = array();
        foreach($teams as $team) {
            $teamsArray[$team->getId()] = $team->getName();
        }

        $question = $this->getDoctrine()->getRepository('AppBundle:Question')->findOneBy(array(
            'category' => $category,
            'cost'     => $cost,
        ));
        $tour = $category->getTour();

        $form = $this->createFormBuilder()
            ->add('team', ChoiceType::class, array(
                'label'    => 'Чья же команда ответила верно??',
                'expanded' => true,
                'choices'  => $teamsArray,
            ))
            ->add('Защитано!', SubmitType::class, array(
                'attr' => array(
                    'class' => 'scored',
                ),
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            $team = $this->getDoctrine()->getRepository('AppBundle:Team')->findOneBy(array(
                'id' => $data,
            ));

            $teamNewScores = $team->getScores() + $cost;
            $team->setScores($teamNewScores);

            $em->persist($team);



            $question->setDone(true);

            $em = $this->getDoctrine()->getManager();
            $em->persist($question);

            $em->flush();

            return $this->redirectToRoute('tour', array(
                'tour_slug' => $tour->getSlug(),
            ));
        }

        return array(
            'tour'     => $tour,
            'category' => $category,
            'question' => $question,
            'form'     => $form->createView(),
        );
    }
}
