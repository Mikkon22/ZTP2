<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Form;

use App\Entity\Portfolio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form type for Portfolio entity.
 */
class PortfolioType extends AbstractType
{
    /**
     * Builds the form for Portfolio entity.
     *
     * @param FormBuilderInterface $builder the form builder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'portfolio.portfolio_name',
                'attr' => [
                    'placeholder' => 'portfolio.enter_portfolio_name',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'portfolio.portfolio_type',
                'choices' => [
                    'portfolio.cash' => 'cash',
                    'portfolio.card' => 'card',
                ],
                'choice_label' => function ($choice, $key, $value) {
                    return $key;
                },
                'placeholder' => 'portfolio.choose_portfolio_type',
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
            'data_class' => Portfolio::class,
        ]);
    }
}
