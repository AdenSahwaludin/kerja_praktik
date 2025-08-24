<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Produk {
    id_produk: string;
    nama: string;
    stok: number;
    batas_stok: number;
    biaya_produk: number;
    harga: number;
    kategori: {
        nama: string;
    };
}

interface Kategori {
    id_kategori: string;
    nama: string;
}

interface Summary {
    total_produk: number;
    total_stok: number;
    nilai_stok: number;
    produk_stok_rendah: number;
    produk_habis: number;
}

interface Props {
    produk?: Produk[];
    kategori: Kategori[];
    summary?: Summary;
    filters?: {
        kategori?: string;
        status_stok?: string;
    };
}

const props = defineProps<Props>();

const selectedKategori = ref(props.filters?.kategori || '');
const statusStok = ref(props.filters?.status_stok || 'semua');

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};

const getStockStatus = (produk: Produk) => {
    if (produk.stok === 0) {
        return { class: 'bg-danger', text: 'Habis' };
    } else if (produk.stok <= produk.batas_stok) {
        return { class: 'bg-warning', text: 'Stok Rendah' };
    } else {
        return { class: 'bg-success', text: 'Tersedia' };
    }
};

const generateReport = () => {
    const params: any = {};

    if (selectedKategori.value) {
        params.kategori = selectedKategori.value;
    }

    if (statusStok.value !== 'semua') {
        params.status_stok = statusStok.value;
    }

    router.get(route('laporan.stok'), params);
};

const resetFilters = () => {
    selectedKategori.value = '';
    statusStok.value = 'semua';
    router.get(route('laporan.stok'));
};
</script>

<template>
    <Head title="Laporan Produk" />

    <DashboardLayout>
        <div class="mb-4">
            <h4 class="fw-bold mb-0 py-3"><span class="text-muted fw-light">Laporan /</span> Produk</h4>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filter Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-select" id="kategori" v-model="selectedKategori">
                            <option value="">Semua Kategori</option>
                            <option v-for="item in kategori" :key="item.id_kategori" :value="item.id_kategori">
                                {{ item.nama }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="status_stok" class="form-label">Status Stok</label>
                        <select class="form-select" id="status_stok" v-model="statusStok">
                            <option value="semua">Semua Status</option>
                            <option value="rendah">Stok Rendah</option>
                            <option value="habis">Stok Habis</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary" @click="generateReport">
                                <i class="bx bx-search me-1"></i>
                                Generate
                            </button>
                            <button type="button" class="btn btn-outline-secondary" @click="resetFilters">
                                <i class="bx bx-refresh me-1"></i>
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div v-if="summary" class="row mb-4">
            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <i class="bx bx-package avatar-initial bg-primary rounded-circle"></i>
                        </div>
                        <h4 class="mb-1">{{ summary.total_produk }}</h4>
                        <small class="text-muted">Total Produk</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <i class="bx bx-cube avatar-initial bg-success rounded-circle"></i>
                        </div>
                        <h4 class="mb-1">{{ summary.total_stok }}</h4>
                        <small class="text-muted">Total Stok</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <i class="bx bx-money avatar-initial bg-info rounded-circle"></i>
                        </div>
                        <h6 class="small mb-1">{{ formatCurrency(summary.nilai_stok) }}</h6>
                        <small class="text-muted">Nilai Stok</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <i class="bx bx-error avatar-initial bg-warning rounded-circle"></i>
                        </div>
                        <h4 class="mb-1">{{ summary.produk_stok_rendah }}</h4>
                        <small class="text-muted">Stok Rendah</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="avatar mx-auto mb-2">
                            <i class="bx bx-x-circle avatar-initial bg-danger rounded-circle"></i>
                        </div>
                        <h4 class="mb-1">{{ summary.produk_habis }}</h4>
                        <small class="text-muted">Stok Habis</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <button class="btn btn-outline-primary btn-sm w-100" disabled>
                            <i class="bx bx-download me-1"></i>
                            Export
                        </button>
                        <small class="text-muted d-block mt-1">Segera</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Stok Produk</h5>
            </div>
            <div class="card-body">
                <div v-if="!produk || produk.length === 0" class="py-4 text-center">
                    <i class="bx bx-package display-4 text-muted"></i>
                    <p class="text-muted mt-2">
                        {{ summary ? 'Tidak ada produk sesuai filter yang dipilih' : 'Klik Generate untuk melihat laporan stok' }}
                    </p>
                </div>

                <div v-else class="table-responsive">
                    <table class="table-hover table">
                        <thead>
                            <tr>
                                <th>ID Produk</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Stok</th>
                                <th>Batas Stok</th>
                                <th>Harga Jual</th>
                                <th>Biaya Produk</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr v-for="item in produk" :key="item.id_produk">
                                <td>
                                    <span class="badge bg-primary">{{ item.id_produk }}</span>
                                </td>
                                <td>
                                    <strong>{{ item.nama }}</strong>
                                </td>
                                <td>{{ item.kategori?.nama || 'N/A' }}</td>
                                <td>
                                    <span class="fw-bold" :class="item.stok <= item.batas_stok ? 'text-danger' : 'text-success'">
                                        {{ item.stok }}
                                    </span>
                                </td>
                                <td>{{ item.batas_stok }}</td>
                                <td>{{ formatCurrency(item.harga) }}</td>
                                <td>{{ formatCurrency(item.biaya_produk) }}</td>
                                <td>
                                    <span class="badge" :class="getStockStatus(item).class">
                                        {{ getStockStatus(item).text }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
