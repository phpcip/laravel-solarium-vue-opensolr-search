<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Solarium\Client;

class HomeController extends Controller
{

    public function __construct(){

    }

    public function index(){

        return view('home');

    }
}
