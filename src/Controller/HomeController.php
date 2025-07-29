<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController {
    // Atribut  permet de définir la route pour cette classe
    #[Route("/", name: 'home')]
    // Controller va contenir des méthodes, et chaque méthode corresponds à une page web
    // 1. Méthode index pour la page d'accueil
    function index(Request $request) : Response {
        // dd($request); //pour afficher le contenu de la requête et arrêter l'exécution du script
        // les méthodes doivent toujours retourner une réponse => un objet Response
        //return new Response('Salam, bienvenue sur mon site '.$request->query->get('name', 'Toto') . ' !');
        return $this->render('home/index.html.twig');
    }
}
