<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Loan;
use App\Models\Tool;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $totalToolsCount = Tool::sum('total_stock');
        $availableToolsCount = Tool::sum('available_stock');
        $activeLoansCount = Loan::whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])->count();
        $completedLoansCount = Loan::where('status', 'selesai')->count();
        $categories = Category::all();

        $stats = [
            'total_units' => $totalToolsCount . ' Unit',
            'available_units' => $availableToolsCount . ' Siap Pakai',
            'active_loans' => $activeLoansCount . ' Sedang Berjalan',
            'completed_loans' => $completedLoansCount . ' Selesai Tertib',
        ];

        return view('home', compact('categories', 'stats'));
    }
}
