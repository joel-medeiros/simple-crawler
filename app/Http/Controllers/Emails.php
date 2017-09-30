<?php

namespace App\Http\Controllers;

use App\Email;
use Illuminate\Http\Request;

class Emails extends Controller
{
    public function index()
    {
        return Email::orderBy("id", "desc")->limit(10)->get();
    }
}
