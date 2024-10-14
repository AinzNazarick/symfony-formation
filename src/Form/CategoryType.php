<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Formulaire pour créer ou éditer une catégorie
 */
class CategoryType extends AbstractType
{
    /**
     * Construit le formulaire avec les champs nécessaires
     *
     * @param FormBuilderInterface $builder Le constructeur de formulaire
     * @param array $options Les options du formulaire
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('save', SubmitType::class,[
                'label' => 'Enregistrer',
            ])
        ;
    }

    /**
     * Configure les options par défaut pour ce type de formulaire
     *
     * @param OptionsResolver $resolver Le résolveur d'options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class, // Lie le formulaire à l'entité
        ]);
    }
}
