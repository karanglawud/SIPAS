<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Loan;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AdminMessageController extends Controller
{
    private function checkAdmin(): ?RedirectResponse
    {
        if (! Auth::check() || ! Auth::user()->isAdmin()) {
            return redirect()->route('login');
        }

        return null;
    }

    public function index(Request $request): View|RedirectResponse
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $admin = Auth::user();

        $category = null;
        if ($admin->category_scope && $admin->category_scope !== 'all') {
            $category = Category::where('slug', $admin->category_scope)->first();
        }

        // Get borrowers (siswa & guru)
        $borrowersQuery = User::whereIn('role', ['siswa', 'guru']);
        if ($request->filled('q')) {
            $q = $request->input('q');
            $borrowersQuery->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('identifier', 'like', "%{$q}%")
                    ->orWhere('class_or_dept', 'like', "%{$q}%");
            });
        }
        $borrowers = $borrowersQuery->orderBy('name')->take(50)->get();

        // Get sent messages by this admin
        $sentMessagesQuery = Message::with(['recipient', 'loan.tool', 'category'])
            ->where('sender_id', $admin->id)
            ->latest();

        if ($request->filled('filter_type')) {
            $sentMessagesQuery->where('type', $request->input('filter_type'));
        }

        $sentMessages = $sentMessagesQuery->paginate(15);

        // Active loans under this admin's scope for quick message targeting
        $loansQuery = Loan::with(['user', 'tool.category'])
            ->whereIn('status', ['menunggu', 'disetujui', 'dipinjam']);

        if ($category) {
            $loansQuery->whereHas('tool', fn ($q) => $q->where('category_id', $category->id));
        }

        $activeLoans = $loansQuery->latest()->take(30)->get();

        return view('admin.messages.index', compact(
            'admin',
            'category',
            'borrowers',
            'sentMessages',
            'activeLoans'
        ));
    }

    public function store(Request $request): RedirectResponse
    {
        if ($redirect = $this->checkAdmin()) {
            return $redirect;
        }

        $admin = Auth::user();

        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'loan_id' => 'nullable|exists:loans,id',
            'type' => 'required|in:info,warning,approval',
            'message' => 'required|string|max:2000',
        ], [
            'recipient_id.required' => 'Pilih peminjam (siswa/guru) penerima pesan.',
            'message.required' => 'Isi pesan wajib ditulis.',
        ]);

        $category = null;
        if (! empty($validated['category_id'])) {
            $category = Category::find($validated['category_id']);
        } elseif ($admin->category_scope && $admin->category_scope !== 'all') {
            $category = Category::where('slug', $admin->category_scope)->first();
        }

        $recipient = User::findOrFail($validated['recipient_id']);

        Message::create([
            'sender_id' => $admin->id,
            'recipient_id' => $recipient->id,
            'category_id' => $category?->id,
            'loan_id' => $validated['loan_id'] ?? null,
            'type' => $validated['type'],
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return back()->with('success', 'Pemberitahuan berhasil dikirimkan kepada '.$recipient->name.' ('.($recipient->class_or_dept ?? $recipient->role).').');
    }
}
