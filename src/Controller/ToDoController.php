<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ToDoController extends AbstractController
{
    /**
     * @Route("/toDo", name= "toDo");
     */

    public function indexAction(SessionInterface $session): Response
    {
        if (!$session->has('todos')) {
            $todos = array(
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger mes examens'
            );
            $session->set('todos', $todos);
            $this->addFlash('info', "Bienvenue dans votre plateforme de gestions des todos");
        }
        return $this->render('toDo/listeToDo.html.twig');
    }

    /**
     * @Route("toDo/add/{name}/{content}", name="addtodo");
     */
    public function addToDo($name, $content, SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $message = "Message flash : La liste n'est pas encore initialisée";
            $this->addFlash('error', $message);
        } else {
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                $this->addFlash('error', "le todo $name existe déjà");
            } else {
                $todos[$name] = $content;
                $session->set('todos', $todos);
                $this->addFlash('success', "le todo $name est crée");
            }
        }
        return $this->redirectToRoute('toDo');
    }

    /**
     * @Route("toDo/delete/{indice}", name="deletetodo");
     */
    public function deleteToDo($indice, SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $this->addFlash('error', "le todo n'existe pas");
        } else {
            $todos = $session->get('todos');
            if (isset($todos[$indice])) {
                unset($todos[$indice]);
                $session->set('todos', $todos);
                $this->addFlash('success', "le todo $indice est supprimé");
            } else {
                $this->addFlash('error', "le toDo $indice à supprimer n’existe pas");
            }
        }
        return $this->redirectToRoute('toDo');
    }
    /**
     * @Route("toDo/reset" , name="resetToDo");
     */
    /*
     * Créer une action resetToDo qui permet de vider la session et de la remettre à son état initial.
     *Prenez en considération le cas où la liste n’est pas encore initialisée
     */
    public function resetToDo(SessionInterface $session)
    {
        if (!$session->has('todos')) {
            $this->addFlash('error', "la liste n’est pas encore initialisée");
        } else {
            $session->remove('todos');
            $this->addFlash('success', "votre liste est dans son état initial");
        }
        return $this->redirectToRoute('toDo');
    }
}
