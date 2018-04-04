<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Skill;
use AppBundle\Form\SkillType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SkillController extends Controller
{
    /**
     * @Route("/skills", name="skill_list")
     */
    public function indexAction(Request $request)
    {
        $skills = $this->getDoctrine()
            ->getRepository('AppBundle:Skill')
            ->findAll();

        return $this->render('game/skill/list.html.twig',[
            'skills' => $skills
        ]);

    }


    /**
     * @Route("/skill/create", name="skill_create")
     */
    public function createAction(Request $request)
    {
        $skill = new Skill();

        $form = $this->createForm(SkillType::class, $skill);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getManager();

            $em->persist($skill);
            $em->flush();

            $this->addFlash('notice','Skill Added');

            return $this->redirectToRoute('skill_list');
        }

        return $this->render('game/skill/create.html.twig',['form'=>$form->createView()]);
    }


    /**
     * @Route("/skill/view/{id}", name="skill_details")
     */
    public function detailsAction(Request $request,$id)
    {
        $skill= $this->getDoctrine()
            ->getRepository('AppBundle:Skill')
            ->find($id);

        return $this->render('game/skill/details.html.twig',[
            'skill' => $skill
        ]);
    }

    /**
     * @Route("/skill/edit/{id}", name="skill_edit")
     */
    public function editAction(Request $request,$id)
    {
        $skill = $this->getDoctrine()
            ->getRepository('AppBundle:Skill')
            ->find($id);

        $form = $this->createForm(SkillType::class,$skill);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getManager();

            $em->flush();

            $this->addFlash('notice','Skill Updated');

            return $this->redirectToRoute('skill_list');
        }

        return $this->render('game/skill/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/skill/delete/{id}", name="skill_delete")
     */
    public function deleteAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $skill = $em->getRepository('AppBundle:Skill')->find($id);

        $em->remove($skill);
        $em->flush();

        $this->addFlash('notice','Skill Deleted');

        return $this->redirectToRoute('skill_list');
    }
}
