<?php

namespace App\Http\Controllers;

use App\Models\Category;

class AboutPjController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('tools')->get();

        return view('about.pj', compact('categories'));
    }
}
