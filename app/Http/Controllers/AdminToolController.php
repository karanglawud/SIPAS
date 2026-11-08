<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tool;
use App\Models\ToolUnit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AdminToolController extends Controller
{
    private function getAvailableCategories()
    {
        $admin = Auth::user();
        if ($admin->category_scope && $admin->category_scope !== 'all') {
            return Category::where('slug', $admin->category_scope)->get();
        }

        return Category::all();
    }

    private function authorizeToolScope(Tool $tool): void
    {
        $categories = $this->getAvailableCategories();
        if (! $categories->contains('id', $tool->category_id)) {
            abort(403, 'Anda tidak memiliki hak akses mengelola alat pada kategori ini.');
        }
    }

    public function index(Request $request): View
    {
        $admin = Auth::user();
        $categories = $this->getAvailableCategories();
        $categoryIds = $categories->pluck('id');

        $query = Tool::with(['category', 'units'])->whereIn('category_id', $categoryIds);

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhere('code', 'like', "%{$q}%")
                    ->orWhere('location', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category') && $request->input('category') !== 'all') {
            $query->whereHas('category', function ($sub) use ($request) {
                $sub->where('slug', $request->input('category'));
            });
        }

        $tools = $query->latest()->paginate(12);

        $totalTools = Tool::whereIn('category_id', $categoryIds)->count();
        $totalUnits = ToolUnit::whereHas('tool', fn ($q) => $q->whereIn('category_id', $categoryIds))->count();
        $readyUnits = ToolUnit::whereHas('tool', fn ($q) => $q->whereIn('category_id', $categoryIds))
            ->where('status', 'tersedia')->count();
        $borrowedUnits = ToolUnit::whereHas('tool', fn ($q) => $q->whereIn('category_id', $categoryIds))
            ->where('status', 'dipinjam')->count();

        return view('admin.tools.index', compact(
            'tools',
            'categories',
            'admin',
            'totalTools',
            'totalUnits',
            'readyUnits',
            'borrowedUnits'
        ));
    }

    public function create(): View
    {
        $categories = $this->getAvailableCategories();

        return view('admin.tools.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $categories = $this->getAvailableCategories();
        $allowedCatIds = $categories->pluck('id')->toArray();

        $validated = $request->validate([
            'category_id' => 'required|in:'.implode(',', $allowedCatIds),
            'code' => 'required|string|max:50|unique:tools,code',
            'name' => 'required|string|max:255',
            'spec' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'total_stock' => 'required|integer|min:1|max:100',
            'fine_per_day' => 'required|integer|min:0',
            'max_days' => 'required|integer|min:1|max:30',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'image_gradient' => 'nullable|string|max:100',
        ], [
            'category_id.required' => 'Pilih kategori alat yang sesuai dengan wewenang Anda.',
            'code.unique' => 'Kode alat ini sudah digunakan oleh alat lain.',
            'name.required' => 'Nama alat praktik wajib diisi.',
            'location.required' => 'Lokasi ruang penyimpanan wajib diisi.',
            'image.image' => 'Berkas harus berupa gambar.',
            'image.mimes' => 'Format gambar harus berupa JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $gradient = $validated['image_gradient'] ?? 'from-slate-800 to-slate-950';

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images/unit', 'public');
        }

        $tool = Tool::create([
            'category_id' => $validated['category_id'],
            'code' => strtoupper($validated['code']),
            'name' => $validated['name'],
            'spec' => $validated['spec'] ?? null,
            'location' => $validated['location'],
            'total_stock' => $validated['total_stock'],
            'available_stock' => $validated['total_stock'],
            'fine_per_day' => $validated['fine_per_day'],
            'max_days' => $validated['max_days'],
            'image_gradient' => $gradient,
            'image' => $imagePath,
        ]);

        // Generate initial physical units automatically
        for ($i = 1; $i <= $validated['total_stock']; $i++) {
            $unitCode = strtoupper($tool->code).'-U'.str_pad((string) $i, 2, '0', STR_PAD_LEFT);
            ToolUnit::create([
                'tool_id' => $tool->id,
                'unit_code' => $unitCode,
                'status' => 'tersedia',
                'condition' => 'baik',
                'notes' => 'Unit perdana terdata',
            ]);
        }

        return redirect()->route('admin.tools.index')->with('success', 'Alat praktik baru "'.$tool->name.'" berhasil ditambahkan beserta '.$validated['total_stock'].' unit fisik.');
    }

    public function edit(Tool $tool): View
    {
        $this->authorizeToolScope($tool);

        $categories = $this->getAvailableCategories();
        $tool->load('units');

        return view('admin.tools.edit', compact('tool', 'categories'));
    }

    public function update(Request $request, Tool $tool): RedirectResponse
    {
        $this->authorizeToolScope($tool);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:tools,code,'.$tool->id,
            'spec' => 'nullable|string|max:1000',
            'location' => 'required|string|max:255',
            'fine_per_day' => 'required|integer|min:0',
            'max_days' => 'required|integer|min:1|max:30',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'remove_image' => 'nullable|boolean',
            'image_gradient' => 'nullable|string|max:100',
        ], [
            'name.required' => 'Nama alat praktik wajib diisi.',
            'code.unique' => 'Kode alat ini sudah digunakan oleh alat lain.',
            'location.required' => 'Lokasi ruang penyimpanan wajib diisi.',
            'image.image' => 'Berkas harus berupa gambar.',
            'image.mimes' => 'Format gambar harus berupa JPG, PNG, atau WebP.',
            'image.max' => 'Ukuran gambar maksimal 5 MB.',
        ]);

        $imagePath = $tool->image;
        if ($request->boolean('remove_image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = null;
        }

        if ($request->hasFile('image')) {
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('images/unit', 'public');
        }

        $tool->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'spec' => array_key_exists('spec', $validated) ? $validated['spec'] : $tool->spec,
            'location' => $validated['location'],
            'fine_per_day' => $validated['fine_per_day'],
            'max_days' => $validated['max_days'],
            'image_gradient' => $validated['image_gradient'] ?? $tool->image_gradient,
            'image' => $imagePath,
        ]);

        return redirect()->route('admin.tools.index')->with('success', 'Rincian alat praktik "'.$tool->name.'" berhasil diperbarui.');
    }

    public function destroy(Tool $tool): RedirectResponse
    {
        $this->authorizeToolScope($tool);

        $hasActiveLoans = $tool->loans()->whereIn('status', ['menunggu', 'disetujui', 'dipinjam'])->exists();
        if ($hasActiveLoans) {
            return back()->with('error', 'Alat ini tidak dapat dihapus karena masih ada peminjaman aktif atau menunggu verifikasi.');
        }

        if ($tool->image && Storage::disk('public')->exists($tool->image)) {
            Storage::disk('public')->delete($tool->image);
            $tool->update(['image' => null]);
        }

        $toolName = $tool->name;

        DB::transaction(function () use ($tool) {
            $tool->units()->delete();
            $tool->delete();
        });

        return redirect()->route('admin.tools.index')->with('success', 'Alat "'.$toolName.'" beserta unit fisiknya telah berhasil dinonaktifkan dari inventaris tanpa merusak riwayat peminjaman lampau.');
    }

    public function storeUnit(Request $request, Tool $tool): RedirectResponse
    {
        $this->authorizeToolScope($tool);

        $validated = $request->validate([
            'unit_code' => 'nullable|string|max:50|unique:tool_units,unit_code',
            'condition' => 'required|in:baik,rusak ringan,rusak berat',
            'notes' => 'nullable|string|max:255',
        ]);

        $unitCode = ! empty($validated['unit_code'])
            ? strtoupper($validated['unit_code'])
            : strtoupper($tool->code).'-U'.str_pad((string) ($tool->units()->count() + 1), 2, '0', STR_PAD_LEFT);

        ToolUnit::create([
            'tool_id' => $tool->id,
            'unit_code' => $unitCode,
            'status' => 'tersedia',
            'condition' => $validated['condition'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $tool->increment('total_stock');
        $tool->increment('available_stock');

        return back()->with('success', 'Unit fisik baru ('.$unitCode.') berhasil ditambahkan ke inventaris.');
    }

    public function updateUnit(Request $request, ToolUnit $unit): RedirectResponse
    {
        $this->authorizeToolScope($unit->tool);

        if ($unit->status === 'dipinjam') {
            return back()->with('error', 'Unit fisik '.$unit->unit_code.' sedang dalam status dipinjam oleh peminjam. Status unit tidak dapat diubah secara manual sampai proses pengembalian resmi dicatat oleh admin.');
        }

        $validated = $request->validate([
            'status' => 'required|in:tersedia,rusak,hilang',
            'condition' => 'required|in:baik,rusak ringan,rusak berat',
            'notes' => 'nullable|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($unit, $validated) {
                $lockedUnit = ToolUnit::where('id', $unit->id)->lockForUpdate()->firstOrFail();

                if ($lockedUnit->status === 'dipinjam') {
                    throw new \RuntimeException('Unit fisik '.$lockedUnit->unit_code.' sedang dalam status dipinjam dan tidak dapat diubah.');
                }

                $lockedUnit->update([
                    'status' => $validated['status'],
                    'condition' => $validated['condition'],
                    'notes' => array_key_exists('notes', $validated) ? $validated['notes'] : $lockedUnit->notes,
                ]);

                // Recalculate available_stock for the tool
                $availableCount = ToolUnit::where('tool_id', $lockedUnit->tool_id)
                    ->where('status', 'tersedia')
                    ->count();

                $lockedUnit->tool->update(['available_stock' => $availableCount]);
            });
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Status unit fisik '.$unit->unit_code.' berhasil diperbarui.');
    }

    public function destroyUnit(ToolUnit $unit): RedirectResponse
    {
        $this->authorizeToolScope($unit->tool);

        if ($unit->status === 'dipinjam') {
            return back()->with('error', 'Unit sedang dipinjam dan tidak dapat dihapus.');
        }

        $tool = $unit->tool;
        $unitCode = $unit->unit_code;
        $wasAvailable = $unit->status === 'tersedia';

        $unit->delete();

        $tool->decrement('total_stock');
        if ($wasAvailable && $tool->available_stock > 0) {
            $tool->decrement('available_stock');
        }

        return back()->with('success', 'Unit fisik '.$unitCode.' berhasil dihapus dari sistem.');
    }
}
