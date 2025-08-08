<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class RecipeController extends AbstractController {
    /**
     * Method to show the list of recipes
     * @param Request $request
     * @param RecipeRepository $repository
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('/recipe', name: 'recipe.index')]
    public function index(Request $request, recipeRepository $repository, EntityManagerInterface $em) : Response {
        //dd($em->getRepository(Recipe::class));
       $recipes =  $repository->findWithDurationLower(10);
       //dd($recipes);
        // pour modifier les recettes, grâce à l'EntityManagerInterface
        //$recipe = new Recipe();
        //$recipe
          //  ->setTitle('Corne de Gazelle')
        //    ->setSlug('corne-de-gazelle')
            //->setContent('Les cornes de gazelle sont des pâtisseries traditionnelles marocaines délicates, en forme de croissant, fourrées à la pâte d’amande. Pour les préparer, commence par mélanger les ingrédients de la pâte (farine, beurre fondu, une pincée de sel et un peu d’eau de fleur d’oranger) jusqu’à obtenir une pâte lisse et souple. Laisse-la reposer pendant que tu prépares la farce : mélange la poudre d’amande, le sucre, la cannelle et un filet d’eau de fleur d’oranger jusqu’à obtenir une pâte homogène. Étale ensuite la pâte très finement, découpe-la en bandes, place un petit boudin de farce sur chaque bande, puis referme délicatement pour former une corne. Fais cuire au four à température moyenne (environ 170°C) pendant 15 à 20 minutes, jusqu’à ce qu’elles prennent une légère couleur dorée.')
            //->setDuration(75)
            //->setIngredients('Farine, beurre fondu, sel, eau de fleur d’oranger, eau. Amandes mondées et moulues, sucre en poudre, cannelle moulue, eau de fleur d’oranger, beurre.')
            //->setCreatedAt(new \DateTimeImmutable())
            //->se tUpdatedAt(new \DateTimeImmutable());
        //$em->persist($recipe); // pour ajouter la recette à l'EntityManager
        //$em->flush();

        // pour modifier une recette existante, on peut faire comme suit :
        //$recipe->setSlug('corne de gazelle');
        //$recipe->setDuration(60);
        //$recipes[0]->setTitle('Tajine de poulet aux olives');
        //$recipes[1]->setTitle('Pastilla de poulet');
        //$em->flush(); // pour enregistrer les modifications dans la base de données
       return $this->render('recipe/index.html.twig', ['recipes' => $recipes]);
    }

    /**
     * Method to show a recipes with it's details
     * @param Request $request
     * @param String $slug
     * @param int $id
     * @param RecipeRepository $repository
     * @return Response
     */
    #[Route('/recipe/{id}-{slug}', name: 'recipe.show', requirements: ['slug' => '[a-z0-9\-]+'])]
    public function show(Request $request, string $slug, int $id, RecipeRepository $repository): Response{
        $recipe = $repository->find($id);
        //dd($recipe);
        if ($recipe->getSlug() !== $slug) {
            return $this->redirectToRoute('recipe.show', ['slug' => $recipe->getSlug(),'id'=> $recipe->getId()]);
        }
        return $this->render(( 'recipe/show.html.twig'), ['recipe' => $recipe]);
    }

    /**
     * Method to edit a recipe
     * @param Recipe $recipe
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('recipe/{id}/edit', name: 'recipe.edit', methods: ['POST', 'GET'])]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em): Response {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request); //gérer la requête HTTP et enregistrer le formulaire avec les données soumises
        if($form->isSubmitted() && $form->isValid()){
            //$recipe->setUpdatedAt(new \DateTimeImmutable());
            $em->flush(); // pour enregistrer les modifications dans la base de données
            $this->addFlash('success', 'Recette modifiée avec succès !');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/edit.html.twig', ['recipe' => $recipe, 'form' => $form]);
    }

    /**
     * Method to create a recipe
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('recipe/create', name: 'recipe.create', methods: ['POST', 'GET'])]
    public function create(Request $request, EntityManagerInterface $em): Response {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
           //recipe->setCreatedAt(new \DateTimeImmutable());
           //recipe->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Recette ajoutée avec succès !');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/create.html.twig', ['form' => $form]);
    }


    /**
     * Method to delete a recipe
     * @param Recipe $recipe
     * @param Request $request
     * @param String $slug
     * @param EntityManagerInterface $em
     * @return Response
     */
    #[Route('recipe/{id}/delete', name: 'recipe.delete', methods: ['DELETE'])]
    public function delete(Recipe $recipe, Request $request, EntityManagerInterface $em): Response {

        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success','Recette supprimée avec succès');
        return $this->redirectToRoute('recipe.index');
    }
}
