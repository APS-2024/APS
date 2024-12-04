<?php

namespace App\Http\Controllers;

use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Modules\Blog\Models\Blog;

class HomeController extends Controller
{

    public function index()
    {

        $latestBlogs = Blog::orderBy('id', 'DESC')->limit(3)->get();
        return view('home',compact('latestBlogs'));
    }
}
