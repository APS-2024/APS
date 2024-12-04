<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdunitReport extends Model
{
    use HasFactory;

    protected $fillable = ['ad_unit_id', 'ad_unit_name','date', 'impressions', 'revenue','status','ecpm','clicks','user_allow'];
}

