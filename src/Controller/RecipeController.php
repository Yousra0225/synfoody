<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipeController extends AbstractController {
    #[Route('/recipe', name: 'recipe.index')]
    public function index(Request $request) : Response {
        //return new Response('Salam, bienvenue sur la page des recettes ');
        return $this->render('recipe/index.html.twig');
    }

    #[Route('/recipe/{slug}-{id}', name: 'recipe.show')]
    public function show(Request $request, String $slug, int $id): Response{
        return $this->render(( 'recipe/show.html.twig'), ['slug' => $slug, 'id' => $id]);
    }


}
