<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TrainingController extends Controller
{

    public function index()
    {
        return view('admin.training_centers.index');
    }
    public function create()
    {
        return view('admin.training_centers.create');
    }
    public function edit()
    {
        return view('admin.training_centers.edit');
    }
}
