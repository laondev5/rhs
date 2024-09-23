<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyTotal extends Model
{
    use HasFactory;
     protected $fillable = ['month', 'total_number', 'total_amount', 'month_name'];

     protected $casts = [
     'month' => 'date',
     ];
}
