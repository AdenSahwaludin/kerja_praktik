<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Pelanggan" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Operasi Utama /</span> Pelanggan</h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Pelanggan</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-search me-1"></i>
                        Cari Pelanggan
                    </button>
                    <button class="btn btn-primary btn-sm">
                        <i class="bx bx-user-plus me-1"></i>
                        Tambah Pelanggan
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted">Halaman untuk mengelola data pelanggan akan dikembangkan di sini.</p>
                <div class="alert alert-info" role="alert">
                    <i class="bx bx-info-circle me-2"></i>
                    Fitur manajemen pelanggan sedang dalam pengembangan.
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
