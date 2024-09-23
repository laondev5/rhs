<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DailyNumber;
use App\Models\MonthlyTotal;
use Carbon\Carbon;

class DailyNumberSeeder extends Seeder
{
    public function run()
    {
    // Clear existing data
    DailyNumber::truncate();
    MonthlyTotal::truncate();

    $startDate = Carbon::now()->startOfMonth();
    $endDate = Carbon::yesterday();

    $currentDate = $startDate;

    while ($currentDate <= $endDate) { $number=rand(100, 235); $amount=$number * 200; DailyNumber::create([ 'date'=>
        $currentDate,
        'day' => $currentDate->format('l'),
        'number' => $number,
        'amount' => $amount,
        ]);

        $this->updateMonthlyTotal($currentDate, $number, $amount);

        $currentDate->addDay();
        }

        // Generate today's number
        $today = Carbon::today();
        $todayNumber = rand(100, 235);
        $todayAmount = $todayNumber * 200;

        DailyNumber::create([
        'date' => $today,
        'day' => $today->format('l'),
        'number' => $todayNumber,
        'amount' => $todayAmount,
        ]);

        $this->updateMonthlyTotal($today, $todayNumber, $todayAmount);
        }

        private function updateMonthlyTotal(Carbon $date, int $number, float $amount)
        {
        $month = $date->format('F Y');

        $monthlyTotal = MonthlyTotal::firstOrNew(['month' => $month]);
        $monthlyTotal->total_number = ($monthlyTotal->total_number ?? 0) + $number;
        $monthlyTotal->total_amount = ($monthlyTotal->total_amount ?? 0) + $amount;
        $monthlyTotal->month_name = $month;
        $monthlyTotal->save();
        }
}
