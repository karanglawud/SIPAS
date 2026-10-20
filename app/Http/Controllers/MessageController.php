<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $categories = Category::with(['admins', 'messages' => function ($q) use ($user) {
            $q->where('recipient_id', $user->id)->latest();
        }])->get();

        $selectedCategorySlug = $request->get('kategori', $categories->first()?->slug);
        $activeCategory = $categories->firstWhere('slug', $selectedCategorySlug) ?? $categories->first();

        $messages = collect();
        if ($activeCategory) {
            $messages = Message::with(['sender', 'loan.tool'])
                ->where('recipient_id', $user->id)
                ->where('category_id', $activeCategory->id)
                ->oldest()
                ->get();

            Message::where('recipient_id', $user->id)
                ->where('category_id', $activeCategory->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('messages.index', compact('categories', 'activeCategory', 'messages', 'user'));
    }
}
