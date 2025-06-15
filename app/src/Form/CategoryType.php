<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Category Type',
                'choices' => [
                    'Income' => 'income',
                    'Expense' => 'expense',
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
                'label' => 'Category Name',
                'attr' => [
                    'placeholder' => 'Enter category name',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Enter category description (optional)',
                    'rows' => 3,
                ],
            ])
            ->add('color', ColorType::class, [
                'label' => 'Color',
                'required' => false,
                'attr' => [
                    'class' => 'form-control form-control-color',
                    'title' => 'Choose category color',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
} 