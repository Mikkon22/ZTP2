<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Portfolio;
use App\Entity\Tag;
use App\Entity\Transaction;
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
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var UserInterface $user */
        $user = $options['user'];

        $builder
            ->add('portfolio', EntityType::class, [
                'class' => Portfolio::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('p')
                        ->where('p.owner = :user')
                        ->setParameter('user', $user)
                        ->orderBy('p.name', 'ASC');
                },
                'label' => 'Portfolio',
                'placeholder' => 'Choose a portfolio',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a portfolio',
                    ]),
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('transactionType', ChoiceType::class, [
                'label' => 'Transaction Type',
                'mapped' => false,
                'choices' => [
                    'Income' => 'income',
                    'Expense' => 'expense',
                ],
                'expanded' => true,
                'data' => 'expense',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a transaction type',
                    ]),
                ],
                'attr' => [
                    'class' => 'btn-group',
                    'data-toggle' => 'buttons',
                ],
                'label_attr' => [
                    'class' => 'btn btn-outline-primary',
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Title',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a transaction title',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'Enter transaction title',
                ],
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Amount',
                'scale' => 2,
                'html5' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter an amount',
                    ]),
                    new GreaterThan([
                        'value' => 0,
                        'message' => 'Amount must be greater than 0',
                    ]),
                ],
                'attr' => [
                    'placeholder' => '0.00',
                    'step' => '0.01',
                    'min' => '0.01',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Enter transaction description (optional)',
                    'rows' => 3,
                ],
            ])
            ->add('date', DateTimeType::class, [
                'label' => 'Date',
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a date',
                    ]),
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er) use ($user) {
                    return $er->createQueryBuilder('c')
                        ->where('c.owner = :user')
                        ->orderBy('c.name', 'ASC');
                },
                'label' => 'Category',
                'placeholder' => 'Choose a category',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please select a category',
                    ]),
                ],
                'attr' => ['class' => 'form-select'],
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'choices' => $user->getTags(),
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'data-choices' => 'true',
                ],
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($user) {
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
                'attr' => ['class' => 'form-select'],
            ]);
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($user) {
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
                'attr' => ['class' => 'form-select'],
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);

        $resolver->setRequired('user');
    }
} 