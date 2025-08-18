<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface Produk {
    id_produk: string;
    nama: string;
    gambar: string | null;
    harga: number;
    stok: number;
}

interface Kategori {
    id_kategori: number;
    nama: string;
    created_at: string;
    updated_at: string;
    produk: Produk[];
}

const props = defineProps<{
    kategori: Kategori;
}>();

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};
</script>

<template>
    <Head title="Detail Kategori" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Manajemen / Produk /</span> Detail Kategori</h4>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Informasi Kategori</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4 text-center">
                            <div class="avatar avatar-xl">
                                <span class="avatar-initial bg-label-primary rounded-circle">
                                    <i class="bx bx-category fs-2"></i>
                                </span>
                            </div>
                        </div>

                        <table class="table-borderless table">
                            <tr>
                                <td class="fw-medium">ID Kategori:</td>
                                <td>#{{ kategori.id_kategori }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Nama:</td>
                                <td>{{ kategori.nama }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Jumlah Produk:</td>
                                <td>
                                    <span class="badge bg-label-info">{{ kategori.produk.length }} Produk</span>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Dibuat:</td>
                                <td>{{ formatDate(kategori.created_at) }}</td>
                            </tr>
                            <tr>
                                <td class="fw-medium">Diperbarui:</td>
                                <td>{{ formatDate(kategori.updated_at) }}</td>
                            </tr>
                        </table>

                        <div class="d-grid gap-2">
                            <Link :href="route('kategori.edit', kategori.id_kategori)" class="btn btn-primary">
                                <i class="bx bx-edit me-1"></i>
                                Edit Kategori
                            </Link>
                            <Link :href="route('kategori.index')" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back me-1"></i>
                                Kembali
                            </Link>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">Produk dalam Kategori</h5>
                        <Link :href="route('produk.create')" class="btn btn-primary btn-sm">
                            <i class="bx bx-plus me-1"></i>
                            Tambah Produk
                        </Link>
                    </div>
                    <div class="card-body">
                        <div v-if="kategori.produk.length === 0" class="text-muted py-4 text-center">
                            <i class="bx bx-package fs-1 d-block mb-2"></i>
                            <p class="mb-0">Belum ada produk dalam kategori ini</p>
                        </div>

                        <div v-else class="table-responsive">
                            <table class="table-hover table">
                                <thead class="table-light">
                                    <tr>
                                        <th>Gambar</th>
                                        <th>Kode Produk</th>
                                        <th>Nama Produk</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="produk in kategori.produk" :key="produk.id_produk">
                                        <td>
                                            <div class="avatar avatar-sm">
                                                <img v-if="produk.gambar" :src="`/storage/${produk.gambar}`" :alt="produk.nama" class="rounded" />
                                                <span v-else class="avatar-initial bg-label-secondary rounded">
                                                    <i class="bx bx-package"></i>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ produk.id_produk }}</strong>
                                        </td>
                                        <td>
                                            <div class="fw-medium">{{ produk.nama }}</div>
                                        </td>
                                        <td>{{ formatCurrency(produk.harga) }}</td>
                                        <td>
                                            <span :class="['badge', produk.stok > 0 ? 'bg-success' : 'bg-danger']">
                                                {{ produk.stok }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-sm btn-outline-secondary dropdown-toggle"
                                                    type="button"
                                                    data-bs-toggle="dropdown"
                                                >
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <Link :href="route('produk.show', produk.id_produk)" class="dropdown-item">
                                                            <i class="bx bx-show me-2"></i>
                                                            Detail
                                                        </Link>
                                                    </li>
                                                    <li>
                                                        <Link :href="route('produk.edit', produk.id_produk)" class="dropdown-item">
                                                            <i class="bx bx-edit me-2"></i>
                                                            Edit
                                                        </Link>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
