<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Daftar Produk" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Manajemen / Produk /</span> Daftar Produk</h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Produk</h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm">
                        <i class="bx bx-import me-1"></i>
                        Import
                    </button>
                    <button class="btn btn-outline-primary btn-sm">
                        <i class="bx bx-export me-1"></i>
                        Export
                    </button>
                    <button class="btn btn-primary btn-sm">
                        <i class="bx bx-plus me-1"></i>
                        Tambah Produk
                    </button>
                </div>
            </div>
            <div class="card-body">
                <p class="text-muted">Halaman untuk mengelola daftar produk akan dikembangkan di sini.</p>
                <div class="alert alert-info" role="alert">
                    <i class="bx bx-info-circle me-2"></i>
                    Fitur manajemen produk sedang dalam pengembangan.
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
