<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('telepon', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'nama');
        $sortOrder = $request->get('sort_order', 'asc');
        $query->orderBy($sortBy, $sortOrder);

        $pengguna = $query->paginate(10)->withQueryString();

        return Inertia::render('Pengguna/Index', [
            'pengguna' => $pengguna,
            'filters' => $request->only(['search', 'role']),
            'roles' => ['admin', 'kasir', 'manager'],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Pengguna/Create', [
            'roles' => ['admin', 'kasir', 'manager'],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'telepon' => 'nullable|string|max:20',
            'role' => 'required|in:admin,kasir,manager',
            'kata_sandi' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        User::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'role' => $request->role,
            'kata_sandi' => Hash::make($request->kata_sandi),
        ]);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $pengguna)
    {
        return Inertia::render('Pengguna/Show', [
            'pengguna' => $pengguna,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $pengguna)
    {
        return Inertia::render('Pengguna/Edit', [
            'pengguna' => $pengguna,
            'roles' => ['admin', 'kasir', 'manager'],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $pengguna)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $pengguna->id,
            'telepon' => 'nullable|string|max:20',
            'role' => 'required|in:admin,kasir,manager',
            'kata_sandi' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $updateData = [
            'nama' => $request->nama,
            'email' => $request->email,
            'telepon' => $request->telepon,
            'role' => $request->role,
        ];

        if ($request->filled('kata_sandi')) {
            $updateData['kata_sandi'] = Hash::make($request->kata_sandi);
        }

        $pengguna->update($updateData);

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $pengguna)
    {
        // Prevent deleting the current authenticated user
        if ($pengguna->getKey() === Auth::id()) {
            return redirect()->route('pengguna.index')
                ->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $pengguna->delete();

        return redirect()->route('pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Toggle user status (activate/deactivate)
     */
    public function toggleStatus(User $pengguna)
    {
        $pengguna->update([
            'aktif' => !$pengguna->aktif
        ]);

        $status = $pengguna->aktif ? 'diaktifkan' : 'dinonaktifkan';
        
        return redirect()->route('pengguna.index')
            ->with('success', "Pengguna berhasil {$status}.");
    }
}
