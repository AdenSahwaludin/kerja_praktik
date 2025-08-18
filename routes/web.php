<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordResetController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\KategoriController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store']);

    Route::get('/forgot-password', function () {
        return Inertia::render('Auth/ForgotPassword');
    })->name('password.request');

    Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');

    Route::get('/reset-password/{token}', function (string $token) {
        return Inertia::render('Auth/ResetPassword', [
            'token' => $token,
            'email' => request('email')
        ]);
    })->name('password.reset');

    Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Operasi Utama Routes
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/create', function () {
            return Inertia::render('Transaksi/Create');
        })->name('create');

        Route::get('/', function () {
            return Inertia::render('Transaksi/Index');
        })->name('index');
    });

    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Pembayaran/Index');
        })->name('index');

        Route::get('/pending', function () {
            return Inertia::render('Pembayaran/Pending');
        })->name('pending');
    });

    Route::prefix('pelanggan')->name('pelanggan.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Pelanggan/Index');
        })->name('index');
    });

    // Manajemen Routes
    Route::resource('produk', ProdukController::class);
    Route::resource('kategori', KategoriController::class);

    Route::prefix('pengguna')->name('pengguna.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Pengguna/Index');
        })->name('index');
    });

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Laporan/Index');
        })->name('index');
    });

    // Pengaturan Sistem Routes
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/edit', function () {
            return Inertia::render('Profile/Edit');
        })->name('edit');
    });

    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () {
            return Inertia::render('Settings/Index');
        })->name('index');
    });

    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
