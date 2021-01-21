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
        $user = new User();
        $user->setEmail("berkay@rentcar.com")
            ->setName("Berkay")
            ->setSurname("Coban")
            ->setPassword($this->passwordEncoder->encodePassword($user, '12345678'))
            ->setRoles(["ROLE_SUPER_ADMIN"])
            ->setCompany($this->getReference(CompanyFixtures::REFERANCE_NAME))
            ->setCreatedAt(new DateTime());

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            CompanyFixtures::class,
        );
    }
}
