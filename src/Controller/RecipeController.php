<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\isEmpty;

class RecipeController extends AbstractController
{
    #[Route('/recettes', name: 'recipe.index')]
    public function index(RecipeRepository $repository): Response
    {
        $recipes = $repository->findAll();
//        $recipes = $repository->findWithDurationLowerThan(20);
        return $this->render('recipe/index.html.twig',[
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recettes/{slug}-{id}', name: 'recipe.show', requirements: ['id' => '\d+', 'slug' => '[a-zA-Z0-9À-ÿ-]+'])]
    public function show(string $slug, int $id, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->find($id);
        if($recipe->getSlug() !== $slug){
            return $this->redirectToRoute('recipe.show', ['slug'=> $recipe->getSlug(), 'id'=> $recipe->getId()]);
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recettes/{id}/edit', name: 'recipe.edit')]
    public function edit(Recipe $recipe, Request $request, EntityManagerInterface $em):Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            $this->addFlash('success', 'La recette a bien été modifiée');
            return $this->redirectToRoute('recipe.index');
        }
        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

    //Création de la route pour créer une nouvelle recette
    #[Route('/recettes/create', name: 'recipe.create')]
    public function create(Request $request,EntityManagerInterface $em) :Response
    {
        //Création d'une nouvelle recette vide
        $recipe = new Recipe();
        //Création du formulaire
        $form = $this->createForm(RecipeType::class, $recipe);
        //Traite la soumission du formulaire, sans cela le formulaire est vide
        $form->handleRequest($request);
        //On vérifie si le formulaire est soumis et s'il est valide
        if($form->isSubmitted() && $form->isValid()){
            //Préparation de l'envoi pour enregistrement en BDD
            $em->persist($recipe);
            //Exécution de la requête en BDD
            $em->flush();
            //Renvoie une alerte pour signaler à l'utilisateur que la recette a bien été enregistré.
            $this->addFlash('success', 'La recette a bien été créée');
            //redirection sur la page sur l'index des recettes
            return $this->redirectToRoute('recipe.index');
        }
        //Renvoi à la vue contenant le formulaire
        return $this->render('recipe/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // ?Recipe permet que Symfony ne gère pas l'exception et on gère nous même le cas
    #[Route('/recettes/{id}/delete', name: 'recipe.delete')]
    public function delete(?Recipe $recipe, EntityManagerInterface $em) :Response
    {
        //Vérification de si la recette existe
        if(!$recipe){
            // Ajoute d'un message flash d'erreur
            $this->addFlash('danger', 'La recette n\'existe pas');
            return $this->redirectToRoute('recipe.index');
        }
        // Suppression de la recette
        $em->remove($recipe);
        $em->flush();
        // Ajout d'un message flash de succès
        $this->addFlash('success', 'La recette a été supprimée');
        return $this->redirectToRoute('recipe.index');
    }

}
