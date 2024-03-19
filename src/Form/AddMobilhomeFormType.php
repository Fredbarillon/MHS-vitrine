<?php

namespace App\Form;

use App\Entity\Mobilhome;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class AddMobilhomeFormType extends AbstractType
{
    const errorMessage = 'Ce champ ne peut pas être vide.';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('length', null, [
            'label' => 'Longueur (en mètres)',
            'label_attr'=> ['class'=> 'me-3'],
            'constraints' => [
                new NotBlank(['message' => self::errorMessage]),
            ],
        ])
        ->add('width',null,[
            'label' =>'Largeur (en mètres)',
            'label_attr'=> ['class'=> 'me-3'],
            'constraints' => [
                new NotBlank(['message' => self::errorMessage]),
            ],
        ])
        ->add('brand',null,[
            'label' =>'Marque',
            'label_attr'=> ['class'=> 'me-3'],
            'constraints' => [
                new NotBlank(['message' => self::errorMessage]),
            ],
        ])
        ->add('model',null,[
            'label' =>'Modèle',
            'label_attr'=> ['class'=> 'me-3'],
            'constraints' => [
                new NotBlank(['message' => self::errorMessage]),
            ],
        ])
        ->add('year',null,[
            'label' =>'Année',
            'label_attr'=> ['class'=> 'me-3'],
            'constraints' => [
                new NotBlank(['message' => self::errorMessage]),
            ],
        ])
        ->add('nb_bedroom',null,[
            'label' =>'Nombre de chambres',
            'label_attr'=> ['class'=> 'me-3'],
            'constraints' => [
                new NotBlank(['message' => self::errorMessage]),
            ],
        ])
        ->add('price',null,[
            'label' =>'Prix (en Euros)',
            'label_attr'=> ['class'=> 'me-3'],
            'constraints' => [
                new NotBlank(['message' => self::errorMessage]),
            ],
        ])
        ->add('central_livingroom', null, [
            'label' => 'Salon Panoramique ou central',
            'label_attr'=> ['class'=> 'me-3'],
        ])
        ->add('oven', null, [
            'label' => 'Four (cuisine équipée)',
            'label_attr'=> ['class'=> 'me-3'],
        ])
        ->add('AC', null, [
            'label' => 'Climatisation',
            'label_attr'=> ['class'=> 'me-3'],
        ])
        ->add('double_glazing', null, [
            'label' => 'Double vitrage',
            'label_attr'=> ['class'=> 'me-3'],
        ])
        ->add('stock', null, [
            'label' => 'Stock',
            'label_attr'=> ['class'=> 'me-3'],
        ])
        ->add('description',TextareaType::class,[
            'label' => 'Description du produit',
            'label_attr'=> ['class'=> 'me-3'],

            'constraints' => [
                new NotBlank(['message' => self::errorMessage]),
            ],
        ])
        ->add('salesArguments',TextareaType::class,[
            'label' => 'Arguments de vente',
            'label_attr'=> ['class'=> 'me-3'],
            'constraints' => [
                new NotBlank(['message' => self::errorMessage]),
            ],
        ])
        ->add('image1', FileType::class, [
            'label' => 'Upload image position 1',
            'label_attr'=> ['class'=> 'me-3'],
            'mapped'=> false ,
            'required' => false,
        ])
        ->add('image2', FileType::class, [
            'label' => 'Upload image position 2',
            'label_attr'=> ['class'=> 'me-3'],
            'mapped'=> false ,
            'required' => false,
        ])
        ->add('image3', FileType::class, [
            'label' => 'Upload image position 3',
            'label_attr'=> ['class'=> 'me-3'],
            'mapped'=> false ,
            'required' => false,
        ])
        ->add('image4', FileType::class, [
            'label' => 'Upload image position 4',
            'label_attr'=> ['class'=> 'me-3'],
            'mapped'=> false ,
            'required' => false,
        ])
        ->add('image5', FileType::class, [
            'label' => 'Upload image position 5',
            'label_attr'=> ['class'=> 'me-3'],
            'mapped'=> false ,
            'required' => false,
        ])
        ->add('image6', FileType::class, [
            'label' => 'Upload image position 6',
            'label_attr'=> ['class'=> 'me-3'],
            'mapped'=> false ,
            'required' => false,
        ])
        ->add('image7', FileType::class, [
            'label' => 'Upload image position 7',
            'label_attr'=> ['class'=> 'me-3'],
            'mapped'=> false ,
            'required' => false,
        ])
        ->add('image8', FileType::class, [
            'label' => 'Upload image position 8',
            'label_attr'=> ['class'=> 'me-3'],
            'mapped'=> false ,
            'required' => false,
        ])
        ->add('image9', FileType::class, [
            'label' => 'Upload image position 9',
            'label_attr'=> ['class'=> 'me-3'],
            'mapped'=> false ,
            'required' => false,
        ])
        ->add('image10', FileType::class, [
            'label' => 'Upload image position 10',
            'label_attr'=> ['class'=> 'me-3'],
            'mapped'=> false ,
            'required' => false,
        ])
        ->add('youtubeLink', TextType::class,[
            'label'=> "Adresse de la vidéo youtube (commence par : https://www.youtube.com/shorts/)",
            'label_attr'=> ['class'=> 'me-3'],
            'required'=> false,
        ])
        ->add('coupDeCoeur',CheckboxType::class,[
            'label'=>"Coup de coeur",
            'label_attr'=> ['class'=> 'me-3'],
            'required'=> false,
            
        ]);
    
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mobilhome::class,
        ]);
    }
}

