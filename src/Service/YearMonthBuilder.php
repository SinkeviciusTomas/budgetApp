<?php

namespace App\Service;

class YearMonthBuilder
{
    public function buildMonthYearMap(array $dates): array
    {
        $result = [];
        $dates = array_column($dates, 'date');
        foreach ($dates as $date) {
            $date = new \DateTime($date);

            $year = $date->format('Y');
            $monthNum = $date->format('n');
            $monthName = $date->format('F');

            $monthData = [
                'number' => $monthNum,
                'name' => $monthName,
            ];

            if (!isset($result[$year])) {
                $result[$year] = [];
            }
            $isDuplicate = false;
            foreach ($result[$year] as $month) {
                if ($month['number'] == $monthNum) {
                    $isDuplicate = true;
                    break;
                }
            }
            if (!$isDuplicate) {
                $result[$year][] = $monthData;
            }
        }
        return $result;
    }
}
