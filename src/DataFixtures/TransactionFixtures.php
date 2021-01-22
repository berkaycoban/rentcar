<?php

namespace App\DataFixtures;

use App\Entity\Transaction;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TransactionFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Calculate days count from dates
     * @param $date1
     * @param $date2
     * @return int
     */
    private function dateDiff($date1, $date2): int
    {
        $startTimeStamp = strtotime($date1);
        $endTimeStamp = strtotime($date2);

        $timeDiff = abs($endTimeStamp - $startTimeStamp);

        $numberDays = $timeDiff/86400;  // 86400 seconds in one day

        return intval($numberDays);
    }

    private function calculateAmount($daily_price, $days): float {
        $amount = $daily_price * $days;
        if($days >= 7){
            $amount *= 0.95; // discount
        }else if($days >= 30){
            $amount *= 0.9; // discount
        }else if($days >= 365) {
            $amount *= 0.7; // discount
        }
        return $amount;
    }

    private function calculateExpectedCarKM($daily_max_km, $days): int {
        return $daily_max_km * $days;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->getTransactionData() as [$car, $customer, $pickup_date, $return_date]) {
            $transaction = new Transaction();
            $car = $this->getReference((string)$car);
            $customer = $this->getReference((string)$customer);

            $days = $this->dateDiff($pickup_date, $return_date);
            $amount = $this->calculateAmount($car->getDailyPrice(), $days);
            $expectedCarKM = $this->calculateExpectedCarKM($car->getDailyMaxKm(), $days);

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