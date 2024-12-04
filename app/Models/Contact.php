<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table= 'contact';
    protected $fillable = ['first_name', 'last_name','email', 'web_url','skype_contact','whatsapp_contact','page_view','adsense','message'];
}
