<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Upload;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'products' => Product::with('category')->orderBy('sort_order')->get(),
            'categories' => Category::orderBy('sort_order')->get(),
            'uploads' => Upload::latest()->get(),
        ]);
    }
}
