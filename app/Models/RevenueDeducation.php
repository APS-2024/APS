<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RevenueDeducation extends Model
{
    use HasFactory;
    protected $table= 'deducation_revenue';
    protected $fillable = ['date', 'ad_unit_id','deducation', 'final_revenue','status'];

}
