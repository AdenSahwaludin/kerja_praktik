<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Riwayat Transaksi" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Operasi Utama / Transaksi /</span> Riwayat Transaksi</h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Riwayat Transaksi</h5>
                <button class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i>
                    Export Data
                </button>
            </div>
            <div class="card-body">
                <p class="text-muted">Halaman untuk melihat riwayat transaksi akan dikembangkan di sini.</p>
                <div class="alert alert-info" role="alert">
                    <i class="bx bx-info-circle me-2"></i>
                    Fitur riwayat transaksi sedang dalam pengembangan.
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
