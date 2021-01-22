<?php

namespace App\Form;

use App\Entity\Car;
use App\Entity\Transaction;
use App\Entity\User;
use App\Repository\CarRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    private $carRepository;
    private $userRepository;

    public function __construct(CarRepository $repo, UserRepository $user) {
        $this->carRepository = $repo;
        $this->userRepository = $user;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('car_id', EntityType::class, [
                'class' => Car::class,
                'label' => 'Car',
                'choices' => $this->carRepository->findAllAvailableCarsByCompany($options['company_id'])
            ])
            ->add('customer_id', EntityType::class, [
                'class' => User::class,
                'label' => 'Customer',
                'choices' => $this->userRepository->findAllCustomers(),
                'choice_label' => function ($user) {
                    return $user->getFullName();
                }
            ])
            ->add('reservationDate', TextType::class, [
                'mapped' => false,
                'required' => true,
                'label' => 'Pickup and Return time range:'
            ])

            ->add('save', SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
            'company_id' => 1
        ]);

        $resolver->setAllowedTypes('company_id', 'int');
    }
}
