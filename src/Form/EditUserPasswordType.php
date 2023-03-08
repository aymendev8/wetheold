<?php
    namespace App\Form;


    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\PasswordType;
    use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\Validator\Constraints as Assert;

    class EditUserPasswordType extends AbstractType
    {
        public function buildForm(FormBuilderInterface $builder, array $options): void
        {
            $builder
                ->add('plainPassword', PasswordType::class, [
                    'attr' => [
                        'class' => 'w-11/12 focus:outline-none focus:text-gray-600 p-2'
                    ],
                    'label' =>  'Mot de passe actuel',
                    'label_attr' => [
                        'class' => 'form-label mt-2'
                    ],
                    'constraints' => [
                        new Assert\NotBlank([
                            'message' => 'Veuillez saisir un mot de passe',
                        ]),
                    ]
                ])
                ->add('newPassword', RepeatedType::class, [
                    'type' => PasswordType::class,
                    'first_options' => [
                        'attr' => [
                            'class' => 'w-11/12 focus:outline-none focus:text-gray-600 p-2'
                        ],
                        'label' => 'Nouveau mot de passe',
                        'label_attr' => [
                            'class' => 'form-label mt-2'
                        ],
                    ],
                    'second_options' => [
                        'attr' => [
                            'class' => 'w-11/12 focus:outline-none focus:text-gray-600 p-2'
                        ],
                        'label' => 'Confirmer le nouveau mot de passe',
                        'label_attr' => [
                            'class' => 'form-label mt-2'
                        ],
                    ]
                ])
            ;
        }
    }