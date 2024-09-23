<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MonthlyTotal;

class MonthlyTotalCont extends Controller
{
   public function index(Request $request)
   {
   $query = MonthlyTotal::query();

   if ($request->has('month')) {
   $query->whereDate('month', $request->month);
   }

   if ($request->has('start_month') && $request->has('end_month')) {
   $query->whereBetween('month', [$request->start_month, $request->end_month]);
   }

   $monthlyTotals = $query->orderBy('month', 'desc')->paginate(15);

   return view('monthly_totals.index', compact('monthlyTotals'));
   }
}
