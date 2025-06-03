<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomclassController extends Controller
{
    //
    public function index(){
        return view('ohmclass.creat_class');
    }
}
