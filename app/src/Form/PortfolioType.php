<?php

namespace App\Form;

use App\Entity\Portfolio;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PortfolioType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Portfolio Name',
                'attr' => [
                    'placeholder' => 'Enter portfolio name',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Portfolio Type',
                'choices' => [
                    'Cash' => 'cash',
                    'Card' => 'card',
                ],
                'placeholder' => 'Choose portfolio type',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}
