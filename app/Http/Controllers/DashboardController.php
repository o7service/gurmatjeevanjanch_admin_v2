<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {

        $totalCategories = Category::count();
        $inActiveCategories = Category::where('status', 'inactive')->count();
        $totalProducts = Product::count();
        return view('dashboard.dashboard', compact("totalCategories", "totalProducts", "inActiveCategories"));
    }
}
