<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';

interface Props {
    stats: {
        total_transaksi_hari_ini: number;
        total_penjualan_hari_ini: number;
        total_transaksi_bulan_ini: number;
        total_penjualan_bulan_ini: number;
        total_produk: number;
        total_pelanggan: number;
        total_pengguna: number;
        produk_stok_rendah: number;
    };
}

const props = defineProps<Props>();

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};
</script>

<template>
    <Head title="Laporan" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Manajemen /</span> Laporan</h4>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="bx bx-receipt avatar-initial bg-primary rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Transaksi Hari Ini</span>
                        <h3 class="card-title mb-2">{{ stats.total_transaksi_hari_ini }}</h3>
                        <small class="text-muted">Total transaksi</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="bx bx-money avatar-initial bg-success rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Penjualan Hari Ini</span>
                        <h3 class="card-title mb-2">{{ formatCurrency(stats.total_penjualan_hari_ini) }}</h3>
                        <small class="text-muted">Total penjualan</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="bx bx-calendar avatar-initial bg-info rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Transaksi Bulan Ini</span>
                        <h3 class="card-title mb-2">{{ stats.total_transaksi_bulan_ini }}</h3>
                        <small class="text-muted">Total transaksi</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="bx bx-trending-up avatar-initial bg-warning rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Penjualan Bulan Ini</span>
                        <h3 class="card-title mb-2">{{ formatCurrency(stats.total_penjualan_bulan_ini) }}</h3>
                        <small class="text-muted">Total penjualan</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Quick Stats -->
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Statistik Sistem</h5>
                        <small class="text-muted">Dashboard Laporan</small>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="d-flex align-items-center bg-light rounded p-3">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial bg-primary rounded-circle">
                                            <i class="bx bx-package"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-0">{{ stats.total_produk }}</h4>
                                        <small class="text-muted">Total Produk</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="d-flex align-items-center bg-light rounded p-3">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial bg-success rounded-circle">
                                            <i class="bx bx-user"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-0">{{ stats.total_pelanggan }}</h4>
                                        <small class="text-muted">Total Pelanggan</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="d-flex align-items-center bg-light rounded p-3">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial bg-info rounded-circle">
                                            <i class="bx bx-user-circle"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-0">{{ stats.total_pengguna }}</h4>
                                        <small class="text-muted">Total Pengguna</small>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-6 mb-4">
                                <div class="d-flex align-items-center bg-light rounded p-3">
                                    <div class="avatar me-3">
                                        <span class="avatar-initial bg-warning rounded-circle">
                                            <i class="bx bx-error"></i>
                                        </span>
                                    </div>
                                    <div>
                                        <h4 class="fw-bold mb-0">{{ stats.produk_stok_rendah }}</h4>
                                        <small class="text-muted">Produk Stok Rendah</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Dashboard Info -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Ringkasan Penjualan</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-3">
                            <span>Hari Ini:</span>
                            <strong>{{ formatCurrency(stats.total_penjualan_hari_ini) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Bulan Ini:</span>
                            <strong>{{ formatCurrency(stats.total_penjualan_bulan_ini) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Rata-rata/Hari (bulan ini):</span>
                            <strong>{{ formatCurrency(stats.total_penjualan_bulan_ini / new Date().getDate()) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Informasi Penting</h6>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-warning d-flex align-items-center mb-3" role="alert" v-if="stats.produk_stok_rendah > 0">
                            <i class="bx bx-error-circle me-2"></i>
                            <div><strong>Perhatian!</strong> Ada {{ stats.produk_stok_rendah }} produk dengan stok rendah.</div>
                        </div>

                        <div class="alert alert-info d-flex align-items-center" role="alert">
                            <i class="bx bx-info-circle me-2"></i>
                            <div><strong>Navigasi:</strong> Gunakan menu sidebar untuk mengakses berbagai jenis laporan.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
