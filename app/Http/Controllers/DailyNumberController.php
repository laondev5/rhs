<?php

namespace App\Http\Controllers;

use App\Models\DailyNumber;
use App\Models\MonthlyTotal;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DailyNumberController extends Controller
{
    public function index(Request $request)
    {
        // Generate number if needed
        $this->generateNumberIfNeeded();

        $dailyQuery = DailyNumber::query();
        $monthlyQuery = MonthlyTotal::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $dailyQuery->where(function($query) use ($search) {
                $query->whereDate('date', $search)
                    ->orWhere('day', 'like', "%{$search}%")
                    ->orWhere('number', 'like', "%{$search}%")
                    ->orWhere('amount', 'like', "%{$search}%");
            });
            $monthlyQuery->where(function($query) use ($search) {
                $query->where('month', 'like', "%{$search}%")
                    ->orWhere('total_number', 'like', "%{$search}%")
                    ->orWhere('total_amount', 'like', "%{$search}%");
            });
        }

        // Date range search
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $dailyQuery->whereBetween('date', [$request->start_date, $request->end_date]);
            $monthlyQuery->whereBetween('month', [
                Carbon::parse($request->start_date)->format('F'),
                Carbon::parse($request->end_date)->format('F')
            ]);
        }

        $dailyNumbers = $dailyQuery->latest('date')->paginate(10);
        $monthlyTotals = $monthlyQuery->latest('created_at')->get();

        $todayNumber = DailyNumber::whereDate('date', Carbon::today())->first();

        $dailyNumbers = $dailyQuery->orderBy('date', 'desc')->paginate(15);
        $monthlyTotals = $monthlyQuery->orderBy('month', 'desc')->paginate(15);

        return view('numbers.index', compact('todayNumber', 'dailyNumbers', 'monthlyTotals'));
    }

    private function generateNumberIfNeeded()
    {
        // $today = Carbon::today();
        // $lastRecord = DailyNumber::latest('date')->first();

        // if (!$lastRecord || $lastRecord->date->lt($today)) {
        //     $startDate = $lastRecord ? $lastRecord->date->addDay() : $today->copy()->subDay();
        //     $dates = $startDate->daysUntil($today);

        //     foreach ($dates as $date) {
        //         $this->generateNumber($date);
        //     }

        // $lastDate = DailyNumber::latest('date')->first()->date ?? null;
        // $currentDate = Carbon::now()->startOfDay();

        $lastDate = DailyNumber::latest('date')->first()->date ?? null;
        //$currentDate = Carbon::now()->startOfDay();
        $yesterday = Carbon::yesterday();

        if (!$lastDate || $lastDate->lt($yesterday)) {
        $startDate = $lastDate ? $lastDate->addDay() : $yesterday->copy()->subDay();
        $dates = $startDate->daysUntil($yesterday);

        foreach ($dates as $date) {
        $this->generateNumber($date);
        $this->updateMonthlyTotal($date);
        }
        } elseif ($lastDate->diffInDays($yesterday) == 1 ) {
        $this->generateNumber($yesterday);
        $this->updateMonthlyTotal($yesterday);
        }
       
    }

    private function generateNumber($date)
    {
    $number = rand(100, 235);
    $amount = $number * 200;

    DailyNumber::create([
    'date' => $date->startOfDay(),
    'day' => $date->format('l'),
    'amount' => $amount,
    'number' => $number,
    ]);

    //  $this->updateMonthlyTotal($date, $number, $amount);
    }

    private function updateMonthlyTotal($date)
    {
        $month = $date->format('F');
        $monthlyTotal = MonthlyTotal::firstOrNew(['month' => $month]);

        $dailyNumbers = DailyNumber::whereMonth('date', $date->month)->get();
        $monthlyTotal->total_number = $dailyNumbers->sum('number');
        // $monthlyTotal->total_number = ($monthlyTotal->total_number ?? 0) + $number;
        $monthlyTotal->total_amount = $dailyNumbers->sum('amount');
        // $monthlyTotal->total_amount = ($monthlyTotal->total_amount ?? 0) + $amount;
        $monthlyTotal->month_name = $date->format('F Y');
        $monthlyTotal->save();
    }
}
