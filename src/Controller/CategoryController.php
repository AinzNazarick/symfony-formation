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
    #[Route('/category', name: 'category.index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();
        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category/edit/{id}', name: 'category.edit')]
    public function edit(Category $category, EntityManagerInterface $entityManager, Request $request): Response{
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            $this->addFlash('success', 'Modification enregistré');
            return $this->redirectToRoute('category.index');
        }
        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

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

    #[Route('/category/{id}', name: 'category.show')]
    public function show(Category $category, CategoryRepository $categoryRepository): Response{
        $category = $categoryRepository->find($category);
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/category/delete/{id}', name: 'category.delete')]
    public function delete(Category $category, EntityManagerInterface $entityManager):Response{
        $entityManager->remove($category);
        $entityManager->flush();
        $this->addFlash('success', 'La catégorie à été modifié');
        return $this->redirectToRoute('category.index');
    }
}
