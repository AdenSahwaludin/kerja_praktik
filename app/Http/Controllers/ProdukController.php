<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $produk = Produk::with('kategori')
            ->orderBy('nama')
            ->paginate(10);

        $kategori = Kategori::orderBy('nama')->get();

        return Inertia::render('Produk/Index', [
            'produk' => $produk,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama')->get();

        return Inertia::render('Produk/Create', [
            'kategori' => $kategori,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_produk' => 'required|string|max:13|unique:produk',
            'nama' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nomor_bpom' => 'nullable|string|max:255',
            'harga' => 'required|numeric|min:0',
            'biaya_produk' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'batas_stok' => 'required|integer|min:0',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        Produk::create($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $produk = Produk::with('kategori')->findOrFail($id);

        return Inertia::render('Produk/Show', [
            'produk' => $produk,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produk = Produk::findOrFail($id);
        $kategori = Kategori::orderBy('nama')->get();

        return Inertia::render('Produk/Edit', [
            'produk' => $produk,
            'kategori' => $kategori,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $produk = Produk::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'id_kategori' => 'required|exists:kategori,id_kategori',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'nomor_bpom' => 'nullable|string|max:255',
            'harga' => 'required|numeric|min:0',
            'biaya_produk' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'batas_stok' => 'required|integer|min:0',
        ]);

        $data = $request->all();

        if ($request->hasFile('gambar')) {
            // Delete old image if exists
            if ($produk->gambar) {
                Storage::disk('public')->delete($produk->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('produk', 'public');
        }

        $produk->update($data);

        return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $produk = Produk::findOrFail($id);

        // Delete image if exists
        if ($produk->gambar) {
            Storage::disk('public')->delete($produk->gambar);
        }

        $produk->delete();

        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
    /**
     * Export products as CSV.
     */
    public function export()
    {
        $fileName = 'produk_export_' . now()->format('Ymd_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];
        $callback = function () {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID Produk', 'Nama', 'Kategori', 'Harga', 'Biaya Produk', 'Stok', 'Batas Stok', 'Nomor BPOM']);
            Produk::with('kategori')->chunk(100, function ($produks) use ($handle) {
                foreach ($produks as $p) {
                    fputcsv($handle, [
                        $p->id_produk,
                        $p->nama,
                        $p->kategori?->nama,
                        $p->harga,
                        $p->biaya_produk,
                        $p->stok,
                        $p->batas_stok,
                        $p->nomor_bpom,
                    ]);
                }
            });
            fclose($handle);
        };
        return response()->streamDownload($callback, $fileName, $headers);
    }
    /**
     * Import products from CSV.
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:csv,txt']);
        $rows = array_map('str_getcsv', file($request->file('file')->getRealPath()));
        $header = array_shift($rows);
        foreach ($rows as $row) {
            $data = array_combine($header, $row);
            Produk::create([
                'id_kategori' => Kategori::where('nama', $data['Kategori'] ?? '')->value('id_kategori') ?: 1,
                'nama' => $data['Nama'] ?? null,
                'harga' => $data['Harga'] ?? 0,
                'biaya_produk' => $data['Biaya Produk'] ?? 0,
                'stok' => $data['Stok'] ?? 0,
                'batas_stok' => $data['Batas Stok'] ?? 0,
                'nomor_bpom' => $data['Nomor BPOM'] ?? null,
                'gambar' => null,
            ]);
        }
        return redirect()->route('produk.index')->with('success', 'Produk berhasil diimpor.');
    }
}
