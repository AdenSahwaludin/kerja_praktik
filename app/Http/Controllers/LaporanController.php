<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Produk;
use App\Models\Pelanggan;
use App\Models\Pembayaran;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LaporanController extends Controller
{
 /**
  * Display the main reports page.
  */
 public function index()
 {
  // Get summary statistics for dashboard
  $today = Carbon::today();
  $thisMonth = Carbon::now()->startOfMonth();
  $thisYear = Carbon::now()->startOfYear();

  $stats = [
   'total_transaksi_hari_ini' => Transaksi::whereDate('created_at', $today)->count(),
   'total_penjualan_hari_ini' => Transaksi::whereDate('created_at', $today)->sum('total_harga'),
   'total_transaksi_bulan_ini' => Transaksi::whereDate('created_at', '>=', $thisMonth)->count(),
   'total_penjualan_bulan_ini' => Transaksi::whereDate('created_at', '>=', $thisMonth)->sum('total_harga'),
   'total_produk' => Produk::count(),
   'total_pelanggan' => Pelanggan::count(),
   'total_pengguna' => User::count(),
   'produk_stok_rendah' => Produk::whereRaw('stok <= batas_stok')->count(),
  ];

  return Inertia::render('Laporan/Index', [
   'stats' => $stats,
  ]);
 }

 /**
  * Generate sales report - fokus pada analisis penjualan per produk dan kategori.
  */
 public function penjualan(Request $request)
 {
  $request->validate([
   'tanggal_mulai' => 'required|date',
   'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
   'kategori' => 'nullable|exists:kategori,id_kategori',
  ]);

  $tanggalMulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
  $tanggalSelesai = Carbon::parse($request->tanggal_selesai)->endOfDay();

  // Ambil data penjualan per produk
  $penjualanProduk = DB::table('detail')
   ->join('produk', 'detail.id_produk', '=', 'produk.id_produk')
   ->join('kategori', 'produk.id_kategori', '=', 'kategori.id_kategori')
   ->join('transaksi', 'detail.id_transaksi', '=', 'transaksi.id_transaksi')
   ->whereBetween('transaksi.created_at', [$tanggalMulai, $tanggalSelesai])
   ->where('transaksi.status', 'selesai')
   ->when($request->kategori, function ($query, $kategori) {
    return $query->where('produk.id_kategori', $kategori);
   })
   ->select([
    'produk.id_produk',
    'produk.nama as nama_produk',
    'kategori.nama as nama_kategori',
    'produk.harga',
    DB::raw('SUM(detail.jumlah) as total_terjual'),
    DB::raw('SUM(detail.jumlah * detail.harga) as total_revenue'),
    DB::raw('SUM(detail.jumlah * produk.biaya_produk) as total_hpp'),
    DB::raw('SUM(detail.jumlah * detail.harga) - SUM(detail.jumlah * produk.biaya_produk) as profit')
   ])
   ->groupBy(['produk.id_produk', 'produk.nama', 'kategori.nama', 'produk.harga'])
   ->orderBy('total_revenue', 'desc')
   ->get();

  // Analisis per kategori
  $penjualanKategori = DB::table('detail')
   ->join('produk', 'detail.id_produk', '=', 'produk.id_produk')
   ->join('kategori', 'produk.id_kategori', '=', 'kategori.id_kategori')
   ->join('transaksi', 'detail.id_transaksi', '=', 'transaksi.id_transaksi')
   ->whereBetween('transaksi.created_at', [$tanggalMulai, $tanggalSelesai])
   ->where('transaksi.status', 'selesai')
   ->select([
    'kategori.id_kategori',
    'kategori.nama as nama_kategori',
    DB::raw('COUNT(DISTINCT produk.id_produk) as jumlah_produk'),
    DB::raw('SUM(detail.jumlah) as total_terjual'),
    DB::raw('SUM(detail.jumlah * detail.harga) as total_revenue'),
    DB::raw('SUM(detail.jumlah * detail.harga) - SUM(detail.jumlah * produk.biaya_produk) as profit')
   ])
   ->groupBy(['kategori.id_kategori', 'kategori.nama'])
   ->orderBy('total_revenue', 'desc')
   ->get();

  $summary = [
   'total_revenue' => $penjualanProduk->sum('total_revenue'),
   'total_hpp' => $penjualanProduk->sum('total_hpp'),
   'total_profit' => $penjualanProduk->sum('profit'),
   'margin_profit' => $penjualanProduk->sum('total_revenue') > 0 ?
    ($penjualanProduk->sum('profit') / $penjualanProduk->sum('total_revenue')) * 100 : 0,
   'total_item_terjual' => $penjualanProduk->sum('total_terjual'),
   'jumlah_produk_terjual' => $penjualanProduk->count(),
   'produk_terlaris' => $penjualanProduk->first()?->nama_produk ?? 'N/A',
   'kategori_terlaris' => $penjualanKategori->first()?->nama_kategori ?? 'N/A',
   'periode' => [
    'mulai' => $tanggalMulai->format('d/m/Y'),
    'selesai' => $tanggalSelesai->format('d/m/Y'),
   ],
  ];

  return Inertia::render('Laporan/Penjualan', [
   'penjualanProduk' => $penjualanProduk,
   'penjualanKategori' => $penjualanKategori,
   'summary' => $summary,
   'filters' => $request->only(['tanggal_mulai', 'tanggal_selesai', 'kategori']),
   'kategori' => \App\Models\Kategori::orderBy('nama')->get(),
  ]);
 }

 /**
  * Generate stock report.
  */
 public function stok(Request $request)
 {
  $request->validate([
   'kategori' => 'nullable|exists:kategori,id_kategori',
   'status_stok' => 'nullable|in:semua,rendah,habis',
  ]);

  $query = Produk::with('kategori');

  if ($request->filled('kategori')) {
   $query->where('id_kategori', $request->kategori);
  }

  if ($request->status_stok === 'rendah') {
   $query->whereRaw('stok <= batas_stok AND stok > 0');
  } elseif ($request->status_stok === 'habis') {
   $query->where('stok', 0);
  }

  $produk = $query->orderBy('nama')->get();

  $summary = [
   'total_produk' => $produk->count(),
   'total_stok' => $produk->sum('stok'),
   'nilai_stok' => $produk->sum(function ($item) {
    return $item->stok * $item->biaya_produk;
   }),
   'produk_stok_rendah' => $produk->filter(function ($item) {
    return $item->stok <= $item->batas_stok && $item->stok > 0;
   })->count(),
   'produk_habis' => $produk->where('stok', 0)->count(),
  ];

  return Inertia::render('Laporan/Stok', [
   'produk' => $produk,
   'summary' => $summary,
   'filters' => $request->only(['kategori', 'status_stok']),
   'kategori' => \App\Models\Kategori::orderBy('nama')->get(),
  ]);
 }

 /**
  * Generate customer report.
  */
 public function pelanggan(Request $request)
 {
  $request->validate([
   'tanggal_mulai' => 'nullable|date',
   'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
  ]);

  $query = Pelanggan::withCount('transaksi')
   ->withSum('transaksi', 'total_harga');

  if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
   $tanggalMulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
   $tanggalSelesai = Carbon::parse($request->tanggal_selesai)->endOfDay();

   $query->whereHas('transaksi', function ($q) use ($tanggalMulai, $tanggalSelesai) {
    $q->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
   });
  }

  $pelanggan = $query->orderBy('transaksi_sum_total_harga', 'desc')->get();

  $summary = [
   'total_pelanggan' => $pelanggan->count(),
   'total_transaksi' => $pelanggan->sum('transaksi_count'),
   'total_penjualan' => $pelanggan->sum('transaksi_sum_total_harga'),
   'rata_rata_per_pelanggan' => $pelanggan->count() > 0 ? $pelanggan->sum('transaksi_sum_total_harga') / $pelanggan->count() : 0,
  ];

  return Inertia::render('Laporan/Pelanggan', [
   'pelanggan' => $pelanggan,
   'summary' => $summary,
   'filters' => $request->only(['tanggal_mulai', 'tanggal_selesai']),
  ]);
 }

 /**
  * Generate transaction report - fokus pada detail operasional transaksi.
  */
 public function transaksi(Request $request)
 {
  $request->validate([
   'tanggal_mulai' => 'required|date',
   'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
   'status' => 'nullable|in:semua,selesai,pending,dibatalkan',
   'pelanggan' => 'nullable|exists:pelanggan,id_pelanggan',
   'metode_pembayaran' => 'nullable|in:semua,tunai,kredit,transfer',
  ]);

  $tanggalMulai = Carbon::parse($request->tanggal_mulai)->startOfDay();
  $tanggalSelesai = Carbon::parse($request->tanggal_selesai)->endOfDay();

  $query = Transaksi::with(['pelanggan', 'detail.produk', 'pembayaran', 'user'])
   ->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);

  if ($request->filled('status') && $request->status !== 'semua') {
   $query->where('status', $request->status);
  }

  if ($request->filled('pelanggan')) {
   $query->where('id_pelanggan', $request->pelanggan);
  }

  if ($request->filled('metode_pembayaran') && $request->metode_pembayaran !== 'semua') {
   $query->whereHas('pembayaran', function ($q) use ($request) {
    $q->where('metode_pembayaran', $request->metode_pembayaran);
   });
  }

  $transaksi = $query->orderBy('created_at', 'desc')->get();

  // Analisis pembayaran
  $analisisPembayaran = DB::table('pembayaran')
   ->join('transaksi', 'pembayaran.id_transaksi', '=', 'transaksi.id_transaksi')
   ->whereBetween('transaksi.created_at', [$tanggalMulai, $tanggalSelesai])
   ->select([
    'pembayaran.metode_pembayaran',
    DB::raw('COUNT(*) as jumlah_transaksi'),
    DB::raw('SUM(transaksi.total_harga) as total_nilai'),
    DB::raw('AVG(transaksi.total_harga) as rata_rata_nilai')
   ])
   ->groupBy('pembayaran.metode_pembayaran')
   ->orderBy('total_nilai', 'desc')
   ->get();

  // Analisis per jam
  $analisisJam = DB::table('transaksi')
   ->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai])
   ->where('status', 'selesai')
   ->select([
    DB::raw('HOUR(created_at) as jam'),
    DB::raw('COUNT(*) as jumlah_transaksi'),
    DB::raw('SUM(total_harga) as total_nilai')
   ])
   ->groupBy(DB::raw('HOUR(created_at)'))
   ->orderBy('jam')
   ->get();

  // Analisis kasir/pengguna
  $analisisKasir = DB::table('transaksi')
   ->join('users', 'transaksi.id_user', '=', 'users.id')
   ->whereBetween('transaksi.created_at', [$tanggalMulai, $tanggalSelesai])
   ->where('transaksi.status', 'selesai')
   ->select([
    'users.id',
    'users.name as nama_kasir',
    DB::raw('COUNT(*) as jumlah_transaksi'),
    DB::raw('SUM(transaksi.total_harga) as total_nilai'),
    DB::raw('AVG(transaksi.total_harga) as rata_rata_per_transaksi')
   ])
   ->groupBy(['users.id', 'users.name'])
   ->orderBy('total_nilai', 'desc')
   ->get();

  $summary = [
   'total_transaksi' => $transaksi->count(),
   'total_nilai' => $transaksi->sum('total_harga'),
   'rata_rata_transaksi' => $transaksi->count() > 0 ? $transaksi->sum('total_harga') / $transaksi->count() : 0,
   'transaksi_per_status' => [
    'selesai' => $transaksi->where('status', 'selesai')->count(),
    'pending' => $transaksi->where('status', 'pending')->count(),
    'dibatalkan' => $transaksi->where('status', 'dibatalkan')->count(),
   ],
   'transaksi_tertinggi' => $transaksi->max('total_harga') ?? 0,
   'transaksi_terendah' => $transaksi->where('total_harga', '>', 0)->min('total_harga') ?? 0,
   'jam_tersibuk' => $analisisJam->sortByDesc('jumlah_transaksi')->first()?->jam ?? 'N/A',
   'metode_terpopuler' => $analisisPembayaran->first()?->metode_pembayaran ?? 'N/A',
   'periode' => [
    'mulai' => $tanggalMulai->format('d/m/Y'),
    'selesai' => $tanggalSelesai->format('d/m/Y'),
   ],
  ];

  return Inertia::render('Laporan/Transaksi', [
   'transaksi' => $transaksi,
   'analisisPembayaran' => $analisisPembayaran,
   'analisisJam' => $analisisJam,
   'analisisKasir' => $analisisKasir,
   'summary' => $summary,
   'filters' => $request->only(['tanggal_mulai', 'tanggal_selesai', 'status', 'pelanggan', 'metode_pembayaran']),
   'pelanggan' => Pelanggan::orderBy('nama')->get(),
  ]);
 }
}
