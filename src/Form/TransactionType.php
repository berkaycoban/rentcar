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
        // if user has have super_admin_role
        if ($options['role'] == 'ROLE_SUPER_ADMIN'){
            $builder->add('car_id', EntityType::class, [
                'class' => Car::class,
                'label' => 'Car',
                'choices' => $this->carRepository->findBy(['available' => 1]) // get all cars
            ]);
        }else {
            $builder->add('car_id', EntityType::class, [
                'class' => Car::class,
                'label' => 'Car',
                'choices' => $this->carRepository->findBy(['owner_id' => $options['company_id'], 'available' => 1])
            ]);
        }

        $builder
            ->add('customer_id', EntityType::class, [
                'class' => User::class,
                'label' => 'Customer',
                'choices' => $this->userRepository->findBy(['company' => null]),
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
            'company_id' => 1,
            'role' => 'ROLE_ADMIN'
        ]);

        $resolver->setAllowedTypes('company_id', 'int');
        $resolver->setAllowedTypes('role', 'string');
    }
}
