<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Fighter;
use AppBundle\Entity\Skill;
use AppBundle\Form\FighterType;
use AppBundle\Repository\FighterRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FighterController extends Controller
{
    /**
     * @Route("/fighters", name="fighter_list")
     */
    public function indexAction(Request $request)
    {
        $fighters = $this->getDoctrine()
            ->getRepository(Fighter::class)
            ->findAll();

        return $this->render('game/fighter/list.html.twig', [
            'fighters' => $fighters
        ]);

    }


    /**
     * @Route("/fighter/create", name="fighter_create")
     */
    public function createAction(Request $request)
    {

        $fighter = new Fighter();

        $form = $this->createForm(FighterType::class, $fighter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $fighter->setHealthRemained($form['health']->getData());

            $em = $this->getDoctrine()->getManager();

            $em->persist($fighter);
            $em->flush();

            $this->addFlash('notice', 'Fighter Added');

            return $this->redirectToRoute('fighter_list');
        }

        return $this->render('game/fighter/create.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/fighter/view/{id}", name="fighter_details")
     */
    public function detailsAction(Request $request, $id)
    {

        $fighter = $this->getDoctrine()->getRepository(Fighter::class)
            ->listWithSkills($id);

        $fighter = $fighter[0];

        return $this->render('game/fighter/details.html.twig', [
            'fighter' => $fighter
        ]);
    }

    /**
     * @Route("/fighter/edit/{id}", name="fighter_edit")
     */
    public function editAction(Request $request, $id)
    {
        $fighter = $this->getDoctrine()->getRepository(Fighter::class)
            ->listWithSkills($id);

        $fighter = $fighter[0];

        $form = $this->createForm(FighterType::class, $fighter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();

            $fighter->setHealthRemained($form['health']->getData());

            $em->flush();

            $this->addFlash('notice', 'Fighter Updated');

            return $this->redirectToRoute('fighter_list');
        }

        return $this->render('game/fighter/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/fighter/delete/{id}", name="fighter_delete")
     */
    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $fighter = $em->getRepository(Fighter::class)->find($id);

        $em->remove($fighter);
        $em->flush();

        $this->addFlash('notice', 'Fighter Deleted');

        return $this->redirectToRoute('fighter_list');
    }
}
