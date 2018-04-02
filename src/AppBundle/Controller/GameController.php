<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Fighter;
use AppBundle\Entity\GameFighter;
use AppBundle\Entity\Skill;
use AppBundle\Entity\Game;
use AppBundle\Form\GameType;
use AppBundle\Form\GameFighterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

//@TODO Cand se asociaza Game cu fighter cu team trebuie facut cu form builder
class GameController extends Controller
{
    /**
     * @Route("/games", name="game_list")
     */
    public function indexAction(Request $request)
    {
        $games = $this->getDoctrine()
            ->getRepository(Game::class)
            ->findAll();

        return $this->render('game/game/list.html.twig',[
            'games' => $games
        ]);

    }

    /**
     * @Route("/game/create", name="game_create")
     */
    public function createAction(Request $request)
    {

        $game = new Game();

        $form = $this->createForm(GameType::class, $game);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getManager();

            $em->persist($game);
            $em->flush();

            $this->addFlash('notice','Game Added');

            return $this->redirectToRoute('game_list');
        }

        return $this->render('game/game/create.html.twig',['form'=>$form->createView()]);
    }


    /**
     * @Route("/game/view/{id}", name="game_details")
     */
    public function detailsAction(Request $request,$id)
    {
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->findComplete($id);

        $game = $game[0];

        return $this->render('game/game/details.html.twig',[
            'game' => $game
        ]);
    }

    /**
     * @Route("/game/edit/{id}", name="game_edit")
     */
    public function editAction(Request $request,$id)
    {
        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->findComplete($id);

        $game = $game[0];

        $form = $this->createForm(GameType::class,$game);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){

            $em = $this->getDoctrine()->getManager();

            $em->flush();

            $this->addFlash('notice','Game Updated');

            return $this->redirectToRoute('game_list');
        }

        return $this->render('game/game/create.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/game/delete/{id}", name="game_delete")
     */
    public function deleteAction(Request $request,$id)
    {
        $em = $this->getDoctrine()->getManager();
        $game = $em->getRepository(Game::class)->find($id);

        $em->remove($game);
        $em->flush();

        $this->addFlash('notice','Game Deleted');

        return $this->redirectToRoute('game_list');
    }

    /**
     * @Route("/game/list_fighters/{id}", name="game_list_fighters")
     */
    public function listFightersAction(Request $request,$id)
    {
        $fighters = $this->getDoctrine()
            ->getRepository(Fighter::class)
            ->findFightersNotInGameId($id);

        return $this->render('game/game/list_fighters.html.twig',['gameId' => $id,'fighters'=>$fighters]);
    }

    /**
     * @Route("/game/add_fighter/{gameId}/{fighterId}/{team}", name="game_add_fighter")
     */
    public function addFighterAction($gameId,$fighterId,$team){
        $gameFighter = new GameFighter();

        $fighter = $this->getDoctrine()
            ->getRepository(Fighter::class)
            ->find($fighterId);

        $game = $this->getDoctrine()
            ->getRepository(Game::class)
            ->find($gameId);

        $gameFighter->setGame($game);
        $gameFighter->setFighter($fighter);
        $gameFighter->setTeam($team);

        $em = $this->getDoctrine()->getManager();

        $em->persist($gameFighter);
        $em->flush();

        $this->addFlash('notice','Fighter Added To Game');

        return $this->redirectToRoute('game_list');
    }
}
