<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface PenjualanProduk {
    id_produk: string;
    nama_produk: string;
    nama_kategori: string;
    harga: number;
    total_terjual: number;
    total_revenue: number;
    total_hpp: number;
    profit: number;
}

interface PenjualanKategori {
    id_kategori: string;
    nama_kategori: string;
    jumlah_produk: number;
    total_terjual: number;
    total_revenue: number;
    profit: number;
}

interface Kategori {
    id_kategori: string;
    nama: string;
}

interface Summary {
    total_revenue: number;
    total_hpp: number;
    total_profit: number;
    margin_profit: number;
    total_item_terjual: number;
    jumlah_produk_terjual: number;
    produk_terlaris: string;
    kategori_terlaris: string;
    periode: {
        mulai: string;
        selesai: string;
    };
}

interface Props {
    penjualanProduk?: PenjualanProduk[];
    penjualanKategori?: PenjualanKategori[];
    kategori?: Kategori[];
    summary?: Summary;
    filters?: {
        tanggal_mulai?: string;
        tanggal_selesai?: string;
        kategori?: string;
    };
}

const props = defineProps<Props>();

const tanggalMulai = ref(props.filters?.tanggal_mulai || '');
const tanggalSelesai = ref(props.filters?.tanggal_selesai || '');
const selectedKategori = ref(props.filters?.kategori || '');
const showAnalisis = ref('produk'); // 'produk' atau 'kategori'

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};

const formatPercent = (value: number) => {
    return value.toFixed(2) + '%';
};

const generateReport = () => {
    if (!tanggalMulai.value || !tanggalSelesai.value) {
        alert('Silakan pilih tanggal mulai dan selesai');
        return;
    }

    const params: any = {
        tanggal_mulai: tanggalMulai.value,
        tanggal_selesai: tanggalSelesai.value,
    };

    if (selectedKategori.value) {
        params.kategori = selectedKategori.value;
    }

    router.get(route('laporan.penjualan'), params);
};

const resetFilters = () => {
    tanggalMulai.value = '';
    tanggalSelesai.value = '';
    selectedKategori.value = '';
    router.get(route('laporan.penjualan'));
};
</script>

<template>
    <Head title="Laporan Penjualan" />

    <DashboardLayout>
        <div class="mb-4">
            <h4 class="fw-bold mb-0 py-3"><span class="text-muted fw-light">Laporan /</span> Penjualan</h4>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filter Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" v-model="tanggalMulai" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" v-model="tanggalSelesai" />
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="kategori" class="form-label">Kategori</label>
                        <select class="form-control" id="kategori" v-model="selectedKategori">
                            <option value="">Semua Kategori</option>
                            <option v-for="kat in kategori" :key="kat.id_kategori" :value="kat.id_kategori">
                                {{ kat.nama }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
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
            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="bx bx-money avatar-initial bg-success rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Revenue</span>
                        <h3 class="card-title mb-2">{{ formatCurrency(summary.total_revenue) }}</h3>
                        <small class="text-muted">{{ summary.periode.mulai }} - {{ summary.periode.selesai }}</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="bx bx-trending-up avatar-initial bg-info rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Profit</span>
                        <h3 class="card-title mb-2">{{ formatCurrency(summary.total_profit) }}</h3>
                        <small class="text-success">Margin: {{ formatPercent(summary.margin_profit) }}</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="bx bx-package avatar-initial bg-primary rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Item Terjual</span>
                        <h3 class="card-title mb-2">{{ summary.total_item_terjual.toLocaleString('id-ID') }}</h3>
                        <small class="text-muted">{{ summary.jumlah_produk_terjual }} produk berbeda</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="bx bx-trophy avatar-initial bg-warning rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Produk Terlaris</span>
                        <h6 class="card-title mb-1">{{ summary.produk_terlaris }}</h6>
                        <small class="text-muted">Kategori: {{ summary.kategori_terlaris }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analysis Tabs -->
        <div v-if="summary" class="row">
            <div class="col-12">
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link"
                                :class="{ active: showAnalisis === 'produk' }"
                                role="tab"
                                @click="showAnalisis = 'produk'"
                            >
                                <i class="tf-icons bx bx-package me-1"></i>
                                Analisis per Produk
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link"
                                :class="{ active: showAnalisis === 'kategori' }"
                                role="tab"
                                @click="showAnalisis = 'kategori'"
                            >
                                <i class="tf-icons bx bx-category me-1"></i>
                                Analisis per Kategori
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    {{ showAnalisis === 'produk' ? 'Penjualan per Produk' : 'Penjualan per Kategori' }}
                </h5>
            </div>
            <div class="card-body">
                <div v-if="!summary" class="py-4 text-center">
                    <i class="bx bx-file-blank display-4 text-muted"></i>
                    <p class="text-muted mt-2">Silakan pilih periode untuk melihat laporan penjualan</p>
                </div>

                <!-- Tabel Produk -->
                <div v-else-if="showAnalisis === 'produk'" class="table-responsive">
                    <table class="table-hover table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga Satuan</th>
                                <th>Qty Terjual</th>
                                <th>Revenue</th>
                                <th>HPP</th>
                                <th>Profit</th>
                                <th>Margin</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr v-for="item in penjualanProduk" :key="item.id_produk">
                                <td>
                                    <strong>{{ item.nama_produk }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ item.nama_kategori }}</span>
                                </td>
                                <td>{{ formatCurrency(item.harga) }}</td>
                                <td>
                                    <span class="badge bg-info">{{ item.total_terjual.toLocaleString('id-ID') }}</span>
                                </td>
                                <td>
                                    <strong class="text-success">{{ formatCurrency(item.total_revenue) }}</strong>
                                </td>
                                <td>{{ formatCurrency(item.total_hpp) }}</td>
                                <td>
                                    <strong class="text-primary">{{ formatCurrency(item.profit) }}</strong>
                                </td>
                                <td>
                                    <span
                                        class="badge"
                                        :class="item.total_revenue > 0 && (item.profit / item.total_revenue) * 100 > 20 ? 'bg-success' : 'bg-warning'"
                                    >
                                        {{ item.total_revenue > 0 ? formatPercent((item.profit / item.total_revenue) * 100) : '0%' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tabel Kategori -->
                <div v-else class="table-responsive">
                    <table class="table-hover table">
                        <thead>
                            <tr>
                                <th>Kategori</th>
                                <th>Jumlah Produk</th>
                                <th>Total Terjual</th>
                                <th>Revenue</th>
                                <th>Profit</th>
                                <th>Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr v-for="item in penjualanKategori" :key="item.id_kategori">
                                <td>
                                    <strong>{{ item.nama_kategori }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ item.jumlah_produk }} produk</span>
                                </td>
                                <td>{{ item.total_terjual.toLocaleString('id-ID') }} item</td>
                                <td>
                                    <strong class="text-success">{{ formatCurrency(item.total_revenue) }}</strong>
                                </td>
                                <td>
                                    <strong class="text-primary">{{ formatCurrency(item.profit) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{
                                            summary && summary.total_revenue > 0
                                                ? formatPercent((item.total_revenue / summary.total_revenue) * 100)
                                                : '0%'
                                        }}
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
