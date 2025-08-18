<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Laporan" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Manajemen /</span> Laporan</h4>

        <div class="row">
            <div class="col-lg-8 order-md-3 order-lg-2 order-2 col-12 mb-4">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Generator Laporan</h5>
                        <button class="btn btn-primary btn-sm">
                            <i class="bx bx-download me-1"></i>
                            Download PDF
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Halaman untuk membuat dan mengunduh laporan akan dikembangkan di sini.</p>
                        <div class="alert alert-info" role="alert">
                            <i class="bx bx-info-circle me-2"></i>
                            Fitur generator laporan sedang dalam pengembangan.
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 order-md-2 order-lg-1 order-1 col-12 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Jenis Laporan</h5>
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bx bx-line-chart me-2"></i>
                                Laporan Penjualan
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bx bx-receipt me-2"></i>
                                Laporan Transaksi
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bx bx-package me-2"></i>
                                Laporan Stok
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <i class="bx bx-user me-2"></i>
                                Laporan Pelanggan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
