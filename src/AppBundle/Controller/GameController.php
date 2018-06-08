<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Personnage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Service\GameService;

/**
 * Class GameController
 *
 * @package AppBundle\Controller
 */
class GameController extends Controller
{
    /**
     * @Route("/game/prepare", name="game_prepare")
     */
    public function indexAction(Request $request)
    {
        $character = $this->getDoctrine()->getRepository(Personnage::class)->findBy(['user' => $this->getUser()]);/* recherche persos de l'user */
        if($character == null) return $this->redirect($this->generateUrl('personnage_add')); /* si aucun perso, go creation */

        $opponents = $this->getDoctrine()->getRepository(Personnage::class)->findBy(['user' => null]); /* recherche des bots soit id user -> null*/

        return $this->render('game/game_prepare.html.twig', array('characters' => $character, 'opponents' => $opponents));
    }

    /**
     * @Route("/game/play", name="game_play")
     */
    public function battleAction(Request $request)
    {
        $idCharacter = $request->get('character'); /* get id perso de l'user */
        $idOpponent = $request->get('opponent');  /* get id bot */

        if($idCharacter == null || $idOpponent == null)
            return $this->redirect($this->generateUrl('game_prepare')); /* si les ids sont null -> redirection choix perso */


        $character = $this->getDoctrine()->getRepository(Personnage::class)->findById($idCharacter);
        $opponent = $this->getDoctrine()->getRepository(Personnage::class)->findById($idOpponent);


        return $this->render('game/game_play.html.twig', array(
            'character' => $character,
            'opponent' => $opponent
        ));
    }

}
