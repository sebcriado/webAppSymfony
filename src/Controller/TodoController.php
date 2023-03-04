<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'todo')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        // Afficher notre tableau de todo
        // sinon je l'initialise puis je l'affiche
        if (!$session->has('todos')) {
            $todos = [
                'achat' => 'acheter clé usb',
                'cours' => 'Finaliser mon cours',
                'correction' => 'corriger les copies'
            ];
            $session->set('todos', $todos);
            $this->addFlash('info', "La liste des todos viens d'être initialisée");
        }
        // si j'ai mon tableau de todo dans ma session je ne fait que l'afficher
        return $this->render('todo/index.html.twig');
    }

    #[Route('/add/{name}/{content}', name: 'todo.add')]

    public function addTodo(Request $request, $name, $content): RedirectResponse
    {

        $session = $request->getSession();
        // Vérifier si j'ai mon tableau de todo dans la session
        if ($session->has('todos')) { // si oui

            // Vérifier si on a déjà un todo avec le même name
            $todos = $session->get('todos');
            if (isset($todos[$name])) {
                // si oui, afficher erreur
                $this->addFlash('error', "Le todo d'id $name existe déjà");
            } else {
                // si non, on l'ajoute et on affiche un message de succès
                $todos[$name] = $content;
                $this->addFlash('success', "Le todo d'id $name à été ajouté avec succès");
                $session->set('todos', $todos);
            }
        } else { // si non

            // afficher une erreur et on va rediriger vers le controlleur index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/update/{name}/{content}', name: 'todo.update')]

    public function updateTodo(Request $request, $name, $content): RedirectResponse
    {

        $session = $request->getSession();
        // Vérifier si j'ai mon tableau de todo dans la session
        if ($session->has('todos')) { // si oui

            // Vérifier si on a déjà un todo avec le même name
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                // si non, afficher erreur
                $this->addFlash('error', "Le todo d'id $name n'existe pas");
            } else {
                // si oui, on le modifie et on affiche un message de succès
                $todos[$name] = $content;
                $this->addFlash('success', "Le todo d'id $name à été modifié avec succès");
                $session->set('todos', $todos);
            }
        } else { // si non

            // afficher une erreur et on va rediriger vers le controlleur index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/delete/{name}', name: 'todo.delete')]

    public function deleteTodo(Request $request, $name): RedirectResponse
    {

        $session = $request->getSession();
        // Vérifier si j'ai mon tableau de todo dans la session
        if ($session->has('todos')) { // si oui

            // Vérifier si on a déjà un todo avec le même name
            $todos = $session->get('todos');
            if (!isset($todos[$name])) {
                // si non, afficher erreur
                $this->addFlash('error', "Le todo d'id $name n'existe pas");
            } else {
                // si oui, on le supprime et on affiche un message de succès
                unset($todos[$name]);
                $this->addFlash('success', "Le todo d'id $name à été supprimé avec succès");
                $session->set('todos', $todos);
            }
        } else { // si non

            // afficher une erreur et on va rediriger vers le controlleur index
            $this->addFlash('error', "La liste des todos n'est pas encore initialisée");
        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/reset', name: 'todo.reset')]

    public function resetTodo(Request $request): RedirectResponse
    {
        $session = $request->getSession();
        $session->remove('todos');
        return $this->redirectToRoute('todo');
    }
}
