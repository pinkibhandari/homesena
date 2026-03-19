<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExpertController extends Controller
{

    public function index()
    {
        return view('admin.experts.index');
    }
     public function create()
    {
        return view('admin.experts.create');
    }
     public function edit()
    {
        return view('admin.experts.edit');
    }
   
}
