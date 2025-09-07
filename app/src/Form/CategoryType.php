<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for Category entity.
 */
class CategoryType extends AbstractType
{
    /**
     * Builds the form for Category entity.
     *
     * @param FormBuilderInterface $builder the form builder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'category.category_type',
                'choices' => [
                    'category.income' => 'income',
                    'category.expense' => 'expense',
                ],
                'expanded' => true,
                'attr' => [
                    'class' => 'btn-group',
                    'data-toggle' => 'buttons',
                ],
                'label_attr' => [
                    'class' => 'btn btn-outline-primary',
                ],
            ])
            ->add('name', TextType::class, [
                'label' => 'category.category_name',
                'attr' => [
                    'placeholder' => 'category.enter_category_name',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'transaction.description',
                'required' => false,
                'attr' => [
                    'placeholder' => 'category.enter_category_description',
                    'rows' => 3,
                ],
            ])
            ->add('color', ColorType::class, [
                'label' => 'category.color',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-color',
                    'title' => 'category.choose_category_color',
                ],
            ])
        ;
    }

    /**
     * Configures the options for this form type.
     *
     * @param OptionsResolver $resolver the resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
