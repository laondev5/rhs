<?php

// app/Console/Commands/GenerateDailyNumber.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DailyNumber;
use App\Models\MonthlyTotal;
use Carbon\Carbon;

class GenerateDailyNumber extends Command
{
    // Define the command signature (name) and description
    protected $signature = 'generate:daily-number';
    protected $description = 'Generate and save daily random number if it\'s a new day';

    // The handle method is where the command's logic is executed
    public function handle()
    {
        // Get the latest daily number's date, or null if none exists
        $lastDate = DailyNumber::latest('date')->first()->date ?? null;

        // Get the current date, set to the start of the day (00:00:00)
        $currentDate = Carbon::now()->startOfDay();

        // Check if there's a gap in daily numbers (more than 1 day difference)
        if (!$lastDate || $lastDate->diffInDays($currentDate) > 1) {
            // Calculate the start date to generate numbers from
            $startDate = $lastDate ? $lastDate->addDay() : $currentDate->copy()->subDay();

            // Get an array of dates between the start date and the current date
            $dates = $startDate->daysUntil($currentDate);

            // Generate and save daily numbers for each date in the gap
            foreach ($dates as $date) {
                $this->generateNumber($date);
            }
        } 
        // Check if it's a new day (1 day difference) and the current hour is 0 or more
        elseif ($lastDate->diffInDays($currentDate) == 1 && $currentDate->hour >= 0) {
            // Generate and save the daily number for the current date
            $this->generateNumber($currentDate);
        }

        // Update the monthly total for the current date
        $this->updateMonthlyTotal($currentDate);
    }

    // Private method to generate a daily number for a given date
    private function generateNumber($date)
    {
        // Generate a random number between 100 and 235
        $number = rand(100, 235);

        // Calculate the amount based on the generated number
        $amount = $number * 200;

        // Create a new daily number record with the generated data
        DailyNumber::create([
            'date' => $date,
            'day' => $date->format('l'), // 'l' format code returns the full textual representation of the day of the week
            'amount' => $amount,
            'number' => $number,
        ]);
    }

    // Private method to update the monthly total for a given date
    private function updateMonthlyTotal($date)
    {
        // Get the month name (e.g. January, February, etc.)
        $month = $date->format('F');

        // Get the monthly total record for the current month, or create a new one if none exists
        $monthlyTotal = MonthlyTotal::firstOrNew(['month' => $month]);

        // Get all daily numbers for the current month
        $dailyNumbers = DailyNumber::whereMonth('date', $date->month)->get();

        // Calculate the total number and amount for the month
        $monthlyTotal->total_number = $dailyNumbers->sum('number');
        $monthlyTotal->total_amount = $dailyNumbers->sum('amount');

        // Save the updated monthly total record
        $monthlyTotal->save();
    }
}
