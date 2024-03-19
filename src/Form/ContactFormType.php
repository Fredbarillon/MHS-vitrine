<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('budget', ChoiceType::class, [
                    'label' => 'Votre budget',
                    'required'=>false,
                    'mapped' => false,
                    'choices' => ['Entre 5.000 et 10.000 euros' => '5k-10k',
                                  'Entre 10.000 et 20.000 euros' => '10k-20k',
                                  'Plus de 20.000 euros' => '20k+',
                    ],
                    'data' => '10k-20k', 
                ])
        
            ->add('last_name', TextType::class, [
                'label' => false, 'attr' => ['placeholder' => 'Nom'],
                'constraints' => [
                    new Length([
                        'max' => 150,
                        'maxMessage' => 'Le nom ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ -]+$/',
                        'message' => 'Le nom ne doit pas comporter de caractères spéciaux.',
                    ]),
                ],
            ])
            ->add('first_name', TextType::class, [
                'label' => false, 'attr' => ['placeholder' => 'Prénom'],
                'constraints' => [
                    new Length([
                        'max' => 150,
                        'maxMessage' => 'Le prénom ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ -]+$/',
                        'message' => 'Le prénom ne doit pas comporter de caractères spéciaux.',
                    ]),
                ],
            ])
            ->add('address', TextType::class, [
                'label' => false, 'attr' => ['placeholder' => 'Adresse (n°, rue, etc.)'],
                'constraints' => [
                    new Length([
                        'max' => 255,
                        'maxMessage' => 'L\'adresse ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ0-9 -]+$/'
                        ,
                        'message' => 'L\'adresse ne doit pas comporter de caractères spéciaux.',
                    ]),
                ],
            ])
            ->add('zip_code', TextType::class, [
                'label' => false, 'attr' => ['placeholder' => 'Code postal'],
                'constraints' => [
                    new Length([
                        'max' => 5,
                        'maxMessage' => 'Le code postal ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^\d+$/',
                        'message' => 'Le code postal doit contenir uniquement des chiffres.',
                    ]),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => false, 'attr' => ['placeholder' => 'Ville'],
                'constraints' => [
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'La ville ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                    new Regex([
                        'pattern' => '/^[A-Za-zÀ-ÖØ-öø-ÿ -]+$/',
                        'message' => 'Le nom de la ville ne doit pas comporter de caractères spéciaux.',
                    ]),
                ],
            ])
            ->add('telephone', IntegerType::class, [
                'label' => false, 'attr' => ['placeholder' => 'Téléphone'],
                'constraints' => [                 
                    new Regex([
                        'pattern' => '/^\d{1,12}$/',
                        'message' => 'Le numéro de téléphone doit contenir uniquement des chiffres (12).',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => false, 'attr' => ['placeholder' => 'E-mail'],
                'constraints' => [
                    new Length([
                        'max' => 150,
                        'maxMessage' => 'L\'e-mail ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
                // to only validate when creating contact

            ])
            ->add('message', TextareaType::class, [
                'label' => false,
                'attr' => ['placeholder' => 'Votre message...', 'rows' => 8],
                'constraints' => [
                    new Length([
                        'max' => 5000,
                        'maxMessage' => 'Le message ne doit pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('terraceInterest', CheckboxType::class, [
                'mapped'=> false,
                'required'=> false,
                'label'=>'Seriez vous interressé par une térrasse avec votre mobil-home ?'
            ])
            ->add('image1', FileType::class, [
                'label' => 'Upload image 1:',
                'label_attr'=> ['class'=> 'me-3'],
                'mapped'=> false ,
                'required' => false,
            ])
            ->add('image2', FileType::class, [
                'label' => 'Upload image 2:',
                'label_attr'=> ['class'=> 'me-3'],
                'mapped'=> false ,
                'required' => false,
            ])
            ->add('image3', FileType::class, [
                'label' => 'Upload image 3:',
                'label_attr'=> ['class'=> 'me-3'],
                'mapped'=> false ,
                'required' => false,
            ])
            ->add('image4', FileType::class, [
                'label' => 'Upload image 4:',
                'label_attr'=> ['class'=> 'me-3'],
                'mapped'=> false ,
                'required' => false,
            ])
            
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'contact',
                'locale' => 'fr',
            ]);
             
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
            'justWantInfo' => null,            
        ]);
    }
}

