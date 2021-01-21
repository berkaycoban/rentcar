<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Company;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('brand')
            ->add('model')
            ->add('year', NumberType::class)
            ->add('fuel', ChoiceType::class, [
                'choices' => [
                    'Gasoline' => 'Gasoline',
                    'Gas & LPG' => 'Gas & LPG',
                    'Diesel' => 'Diesel',
                    'Hybrid' => 'Hybrid',
                    'Electric' => 'Electric'
                ]
            ])
            ->add('gear', ChoiceType::class, [
                'choices' => [
                    'Manuel' => 'Manuel',
                    'Semi Automatic' => 'Semi Automatic',
                    'Automatic' => 'Automatic'
                ]
            ])
            ->add('class', ChoiceType::class, [
                'choices' => [
                    'Economic' => 'Economic',
                    'Comfort' => 'Comfort',
                    'Premium' => 'Premium',
                    'Suv' => 'Suv'
                ]
            ])
            ->add('daily_price', MoneyType::class, [
                'currency' => 'TRY'
            ])
            ->add('owner_id', EntityType::class, [
                'class' => Company::class,
            ])
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/*',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image format'
                    ])
                ]
            ])
            ->add('daily_max_km', NumberType::class)
            ->add('km', NumberType::class)
            ->add('min_license_year', NumberType::class)
            ->add('min_driver_age', NumberType::class)
            ->add('luggage_volume', NumberType::class, [
                'required' => false
            ])
            ->add('seats', NumberType::class)
            ->add('airbag')
            ->add('air_conditioning')
            ->add('img_alt', TextType::class, ['required' => false])
            ->add('save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
