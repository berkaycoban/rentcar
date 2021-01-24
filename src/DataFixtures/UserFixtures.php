<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $passwordEncoder;

    /**
     * UserFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fullName, $email, $password, $company, $roles, $gender]) {
            $admin = new User();
            $admin->setFullName($fullName)
                ->setGender((bool)$gender)
                ->setEmail($email)
                ->setPassword($this->passwordEncoder->encodePassword(
                    $admin, $password
                ))
                ->setRoles($roles)
                ->setCompany($company)
                ->setCreatedAt(new DateTime());

            $manager->persist($admin);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            CompanyFixtures::class,
        );
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullName, $email, $password, $company, $roles];
            ['Berkay Coban', 'berkay@rentcar.com', '12345678', $this->getReference(CompanyFixtures::REFERENCE_NAME), ['ROLE_SUPER_ADMIN'], 0],
            ['Aytur Ates', 'aytur@rentcar.com', '12345678', $this->getReference(CompanyFixtures::REFERENCE_NAME_1), ['ROLE_ADMIN'], 0],
            ['Berkant Senol', 'berkant@rentcar.com', '12345678', $this->getReference(CompanyFixtures::REFERENCE_NAME_2), ['ROLE_ADMIN'], 0],
            ['Kamil Ayyildiz', 'kamil@rentcar.com', '12345678', $this->getReference(CompanyFixtures::REFERENCE_NAME_3), ['ROLE_ADMIN'], 0],
            ['Gizem Ayyildiz', 'gizem@rentcar.com', '12345678', $this->getReference(CompanyFixtures::REFERENCE_NAME), ['ROLE_ADMIN'], 1],
        ];
    }
}
