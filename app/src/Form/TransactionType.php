<?php

/**
 * This file is part of the ZTP2 FinanceApp project.
 *
 * Mikołaj Kondek<mikolaj.kondek@student.uj.edu.pl>
 */

namespace App\Form;

use App\Entity\Category;
use App\Entity\Portfolio;
use App\Entity\Tag;
use App\Entity\Transaction;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * Form type for Transaction entity.
 */
class TransactionType extends AbstractType
{
    /**
     * Builds the form for Transaction entity.
     *
     * @param FormBuilderInterface $builder the form builder
     * @param array                $options the options for this form
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var UserInterface $user */
        $user = $options['user'];

        $builder
            ->add('portfolio', EntityType::class, [
                'class' => Portfolio::class,
                'choice_label' => 'name',
                'choice_translation_domain' => false,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('p')
                        ->where('p.owner = :user')
                        ->setParameter('user', $user)
                        ->orderBy('p.name', 'ASC');
                },
                'label' => 'transaction.portfolio',
                'placeholder' => 'nav.choose_portfolio',
                'constraints' => [
                    new NotBlank([
                        'message' => 'common.error_select_portfolio',
                    ]),
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('transactionType', ChoiceType::class, [
                'label' => 'transaction.transaction_type',
                'mapped' => false,
                'choices' => [
                    'transaction.income_label' => 'income',
                    'transaction.expense_label' => 'expense',
                ],
                'choice_label' => function ($choice, $key, $value) {
                    return $key;
                },
                'expanded' => true,
                'data' => 'expense',
                'constraints' => [
                    new NotBlank([
                        'message' => 'common.error_select_transaction_type',
                    ]),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'transaction.title',
                'constraints' => [
                    new NotBlank([
                        'message' => 'common.error_enter_transaction_title',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'nav.enter_transaction_title',
                ],
            ])
            ->add('amount', NumberType::class, [
                'label' => 'transaction.amount',
                'scale' => 2,
                'html5' => true,
                'translation_domain' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'common.error_enter_amount',
                    ]),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'common.error_amount_greater_than_zero',
                    ]),
                ],
                'attr' => [
                    'placeholder' => '0.00',
                    'step' => '0.01',
                    'min' => '0.01',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'transaction.description',
                'required' => false,
                'attr' => [
                    'placeholder' => 'nav.enter_transaction_description',
                    'rows' => 3,
                ],
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'transaction.date',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'common.error_select_date',
                    ]),
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'choice_translation_domain' => false,
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('c')
                        ->where('c.owner = :user')
                        ->setParameter('user', $user)
                        ->orderBy('c.name', 'ASC');
                },
                'label' => 'transaction.category',
                'placeholder' => 'nav.choose_category',
                'constraints' => [
                    new NotBlank([
                        'message' => 'common.error_select_category',
                    ]),
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choices' => $user->getTags(),
                'choice_translation_domain' => false,
                'multiple' => true,
                'required' => false,
                'label' => 'transaction.tags',
                'attr' => [
                    'class' => 'form-control',
                    'data-choices' => 'true',
                ],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user): void {
            $form = $event->getForm();
            $transaction = $event->getData();
            $type = $transaction && $transaction->getAmount() ? ($transaction->getAmount() > 0 ? 'income' : 'expense') : 'expense';

            $form->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) use ($user, $type) {
                    return $er->createQueryBuilder('c')
                        ->where('c.owner = :user')
                        ->andWhere('c.type = :type')
                        ->setParameter('user', $user)
                        ->setParameter('type', $type)
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'choice_translation_domain' => false,
                'attr' => ['class' => 'form-select'],
            ]);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($user): void {
            $form = $event->getForm();
            $data = $event->getData();
            $type = $data['transactionType'] ?? 'expense';

            $form->add('category', EntityType::class, [
                'class' => Category::class,
                'query_builder' => function (EntityRepository $er) use ($user, $type) {
                    return $er->createQueryBuilder('c')
                        ->where('c.owner = :user')
                        ->andWhere('c.type = :type')
                        ->setParameter('user', $user)
                        ->setParameter('type', $type)
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'choice_translation_domain' => false,
                'attr' => ['class' => 'form-select'],
            ]);
        });
    }

    /**
     * Configures the options for this form type.
     *
     * @param OptionsResolver $resolver the resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);

        $resolver->setRequired('user');
    }
}
