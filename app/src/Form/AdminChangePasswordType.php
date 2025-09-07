<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Form type for admin password change.
 */
class AdminChangePasswordType extends AbstractType
{
    /**
     * Builds the form for admin password change.
     *
     * @param FormBuilderInterface $builder the form builder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('newPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'mapped' => false,
                'first_options' => [
                    'label' => 'admin.new_password',
                    'attr' => ['class' => 'form-control'],
                ],
                'second_options' => [
                    'label' => 'admin.repeat_new_password',
                    'attr' => ['class' => 'form-control'],
                ],
                'invalid_message' => 'common.error_password_fields_match',
                'constraints' => [
                    new NotBlank([
                        'message' => 'common.error_enter_new_password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'common.error_password_min_length',
                        'max' => 4096,
                    ]),
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
        $resolver->setDefaults([]);
    }
}
