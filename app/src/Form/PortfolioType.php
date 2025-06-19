<?php

/**
 * This file is part of the ZTP2-2 project.
 *
 * (c) Your Name <your@email.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
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
     */
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

    /**
     * Configures the options for this form type.
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Portfolio::class,
        ]);
    }
}
