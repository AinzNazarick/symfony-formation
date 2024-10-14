<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CategoryController extends AbstractController
{
    /**
     * Affiche la liste de toutes les catégories
     *
     * @param CategoryRepository $categoryRepository Le repo pour accéder aux catégories
     * @return Response La page avec la liste des catégories
     */
    #[Route('/category', name: 'category.index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        // Récupération de toutes les catégories
        $categories = $categoryRepository->findAll();
        // On envoie ça dans la vue
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * Modifie une catégorie existante
     *
     * @param Category $category La catégorie à modifier
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités de Doctrine
     * @param Request $request La requête HTTP en cours
     * @return Response Redirection ou formulaire d'édition
     */
    #[Route('/category/edit/{id}', name: 'category.edit')]
    public function edit(Category $category, EntityManagerInterface $entityManager, Request $request): Response{
        // On crée le formulaire à partir de CategoryType avec les infos de notre catégorie
        $form = $this->createForm(CategoryType::class, $category);
        // Traite la soumission du formulaire, sans cela le formulaire est vide
        $form->handleRequest($request);
        // Vérification si mon formulaire a été soumis et s'il est valide
        if($form->isSubmitted() && $form->isValid()){
            // On envoie des modifications à la bdd
            $entityManager->flush();
            //Renvoie une alerte pour signaler à l'utilisateur que la catégorie a bien été modifiée.
            $this->addFlash('success', 'Modification enregistré');
            // On redirige vers la liste des catégories
            return $this->redirectToRoute('category.index');
        }
        // Si le form n'est pas soumis ou pas valide, on affiche la page d'édition
        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Création d'une nouvelle catégorie
     *
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités de Doctrine
     * @param Request $request La requête HTTP en cours
     * @return Response Redirection ou formulaire de création
     */
    #[Route('/category/create', name: 'category.create')]
    public function create(EntityManagerInterface $entityManager, Request $request): Response{
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Catégorie créée avec success!');
            return $this->redirectToRoute('category.index');
        }

        return $this->render('category/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affiche les détails d'une catégorie
     *
     * @param Category $category La catégorie à afficher
     * @param CategoryRepository $categoryRepository Le repo pour accéder aux catégories
     * @return Response La page de détails de la catégorie
     */
    #[Route('/category/{id}', name: 'category.show')]
    public function show(Category $category, CategoryRepository $categoryRepository): Response{
        $category = $categoryRepository->find($category);
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * Supprime une catégorie
     *
     * @param Category $category La catégorie à supprimer
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités de Doctrine
     * @return Response Redirection vers la liste des catégories
     */
    #[Route('/category/delete/{id}', name: 'category.delete')]
    public function delete(Category $category, EntityManagerInterface $entityManager):Response{
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('success', 'La catégorie à été modifié');
        return $this->redirectToRoute('category.index');
    }
}
