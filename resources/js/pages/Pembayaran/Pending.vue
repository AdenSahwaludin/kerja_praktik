<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Pembayaran Belum Selesai" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Operasi Utama / Pembayaran /</span> Pembayaran Belum Selesai</h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Pembayaran Belum Selesai</h5>
                <button class="btn btn-warning btn-sm">
                    <i class="bx bx-refresh me-1"></i>
                    Refresh Status
                </button>
            </div>
            <div class="card-body">
                <p class="text-muted">Halaman untuk melihat pembayaran yang belum selesai akan dikembangkan di sini.</p>
                <div class="alert alert-warning" role="alert">
                    <i class="bx bx-time me-2"></i>
                    Fitur tracking pembayaran pending sedang dalam pengembangan.
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
