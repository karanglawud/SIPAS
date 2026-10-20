<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class AboutPjController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('tools')->get();
        return view('about.pj', compact('categories'));
    }
}
