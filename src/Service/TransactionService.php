<?php

namespace App\Service;

final class TransactionService {

    /**
     * Calculate days count from dates
     * @param $date1
     * @param $date2
     * @return int
     */
    public function dateDiff($date1, $date2): int
    {
        $startTimeStamp = strtotime($date1);
        $endTimeStamp = strtotime($date2);

        $timeDiff = abs($endTimeStamp - $startTimeStamp);

        $numberDays = $timeDiff/86400;  // 86400 seconds in one day

        return intval($numberDays);
    }

    /**
     * @param $daily_price
     * @param $days
     * @return float
     */
    public function calculateAmount($daily_price, $days): float {
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

    /**
     * @param $daily_max_km
     * @param $days
     * @return int
     */
    public function calculateExpectedCarKM($daily_max_km, $days): int {
        return $daily_max_km * $days;
    }


    /**
     * @param string $date
     * @return array
     */
    public function parseDate(string $date): array {
        $a = explode(" - ", $date);

        $date1 = strtotime($a[0]);
        $date2 = strtotime($a[1]);

        return [
            date('Y-m-d H:i', $date1),
            date('Y-m-d H:i', $date2)
        ];
    }
}
