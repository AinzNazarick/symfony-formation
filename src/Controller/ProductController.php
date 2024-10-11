<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'product.index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/product/create', name: 'product.create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response{
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Produit créer avec succès !');
            return $this->redirectToRoute('product.index');
        }
        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/{id}', name: 'product.show')]
    public function show(Product $product): Response{
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    #[Route('/product/edit/{id}', name: 'product.edit')]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response{
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Modification enregistré');
            return $this->redirectToRoute('product.index');
        }
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/delete/{id}', name: 'product.delete')]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response{
        $entityManager->remove($product);
        $entityManager->flush();
        $this->addFlash('success', 'Le produit à été supprimé');
        return $this->redirectToRoute('product.index');
    }
}
