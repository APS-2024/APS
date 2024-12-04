<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adunitpercen extends Model
{
    use HasFactory;
    protected $table= 'ad_unit_percentage';
    protected $fillable = ['ad_unit_id', 'site_name','percentage', 'user_id'];

}
