<?php

namespace App\DataFixtures;

use App\Entity\Transaction;
use App\Service\TransactionService;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TransactionFixtures extends Fixture implements DependentFixtureInterface
{
    private $transactionService;

    public function __construct(TransactionService $transaction){
        $this->transactionService = $transaction;
    }
    public function load(ObjectManager $manager)
    {
        foreach ($this->getTransactionData() as [$car, $customer, $pickup_date, $return_date]) {
            $transaction = new Transaction();
            $car = $this->getReference((string)$car);
            $customer = $this->getReference((string)$customer);

            $days = $this->transactionService->dateDiff($pickup_date, $return_date);
            $amount = $this->transactionService->calculateAmount($car->getDailyPrice(), $days);
            $expectedCarKM = $this->transactionService->calculateExpectedCarKM($car->getDailyMaxKm(), $days);

            $transaction->setCarId($car)
                ->setCustomerId($customer)
                ->setPickupDate(new DateTime($pickup_date))
                ->setPickupCarKm($car->getKm())
                ->setReturnDate(new DateTime($return_date))
                ->setReturnCarKm($expectedCarKM)
                ->setAmount($amount)
                ->setDate(new DateTime());

            $car->setAvailable(false);
            $manager->persist($car);
            $manager->persist($transaction);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return array(
            CompanyFixtures::class,
        );
    }

    private function getTransactionData(): array
    {
        return [
            // $transactionData = [$car_id, $customer_id, $pickup_date, $return_date];
            ['car1', 'customer2', '2021-01-22', '2021-01-29'],
            ['car2', 'customer3', '2021-01-18', '2021-01-23'],
            ['car3', 'customer1', '2021-01-23', '2021-01-26'],
        ];
    }
}
