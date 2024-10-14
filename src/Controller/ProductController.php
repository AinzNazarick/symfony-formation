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
    /**
     * Affiche la liste de tous les produits
     *
     * @param ProductRepository $productRepository Le repo pour accéder aux produits
     * @return Response La page avec la liste des produits
     */
    #[Route('/product', name: 'product.index')]
    public function index(ProductRepository $productRepository): Response
    {
        // On récupère tous les produits
        $products = $productRepository->findAll();
        // On les envoie à la vue
        return $this->render('product/index.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * Création d'un nouveau produit
     *
     * @param Request $request La requête HTTP en cours
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités de Doctrine
     * @return Response Redirection ou formulaire de création
     */
    #[Route('/product/create', name: 'product.create')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response{
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            // Ajout d'une notification
            $this->addFlash('success', 'Produit créer avec succès !');
            return $this->redirectToRoute('product.index');
        }
        // Si le form n'est pas bon, on affiche la page de création
        return $this->render('product/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Affiche les détails d'un produit
     *
     * @param Product $product Le produit à afficher
     * @return Response La page de détails du produit
     */
    #[Route('/product/{id}', name: 'product.show')]
    public function show(Product $product): Response{
        return $this->render('product/show.html.twig', [
            'product' => $product,
        ]);
    }

    /**
     * Modifie le produit existant
     *
     * @param Request $request La requête HTTP en cours
     * @param Product $product Le produit à modifier
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités de Doctrine
     * @return Response Redirection ou formulaire d'édition
     */
    #[Route('/product/edit/{id}', name: 'product.edit')]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response{
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // On envoie les modifications à la bdd
            $entityManager->flush();
            // On ajoute une notification
            $this->addFlash('success', 'Modification enregistré');
            return $this->redirectToRoute('product.index');
        }

        // Si le form n'est pas bon, on réaffiche la page d'édition
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Supprime un produit
     *
     * @param Product $product Le produit à supprimer
     * @param EntityManagerInterface $entityManager Le gestionnaire d'entités de Doctrine
     * @return Response Redirection vers la liste des produits
     */
    #[Route('/product/delete/{id}', name: 'product.delete')]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response{
        $entityManager->remove($product);
        $entityManager->flush();
        // Confirmation de suppression
        $this->addFlash('success', 'Le produit à été supprimé');
        return $this->redirectToRoute('product.index');
    }
}
