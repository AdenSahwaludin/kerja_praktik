<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Pelanggan {
    id_pelanggan: string;
    nama: string;
    telepon: string;
    alamat: string;
    transaksi_count: number;
    transaksi_sum_total_harga: number;
}

interface Summary {
    total_pelanggan: number;
    total_transaksi: number;
    total_penjualan: number;
    rata_rata_per_pelanggan: number;
}

interface Props {
    pelanggan?: Pelanggan[];
    summary?: Summary;
    filters?: {
        tanggal_mulai?: string;
        tanggal_selesai?: string;
        pelanggan?: string;
    };
}

const props = defineProps<Props>();

const tanggalMulai = ref(props.filters?.tanggal_mulai || '');
const tanggalSelesai = ref(props.filters?.tanggal_selesai || '');
const pelangganSearch = ref(props.filters?.pelanggan || '');

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};

const applyFilter = () => {
    const params: any = {};
    if (tanggalMulai.value) params.tanggal_mulai = tanggalMulai.value;
    if (tanggalSelesai.value) params.tanggal_selesai = tanggalSelesai.value;
    if (pelangganSearch.value) params.pelanggan = pelangganSearch.value;
    router.get(route('laporan.pelanggan'), params);
};

const resetFilters = () => {
    tanggalMulai.value = '';
    tanggalSelesai.value = '';
    pelangganSearch.value = '';
    router.get(route('laporan.pelanggan'));
};
</script>

<template>
    <Head title="Laporan Pelanggan" />

    <DashboardLayout>
        <div class="mb-4">
            <h4 class="fw-bold mb-0 py-3"><span class="text-muted fw-light">Laporan /</span> Pelanggan</h4>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filter Laporan (Opsional)</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" v-model="tanggalMulai" @change="applyFilter" />
                        <small class="text-muted">Kosongkan untuk semua data</small>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" v-model="tanggalSelesai" @change="applyFilter" />
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="pelanggan" class="form-label">Pelanggan</label>
                        <input
                            type="text"
                            class="form-control mb-2"
                            id="pelanggan"
                            v-model="pelangganSearch"
                            @input="applyFilter"
                            placeholder="Cari pelanggan"
                            list="pelangganList"
                        />
                        <datalist id="pelangganList">
                            <option v-for="p in props.pelanggan" :key="p.id_pelanggan" :value="p.nama" />
                        </datalist>
                        <button type="button" class="btn btn-outline-secondary mt-1" @click="resetFilters">
                            <i class="bx bx-refresh me-1"></i>
                            Reset
                        </button>
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
                                <i class="bx bx-user avatar-initial bg-primary rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Pelanggan</span>
                        <h3 class="card-title mb-2">{{ summary.total_pelanggan }}</h3>
                        <small class="text-muted">Pelanggan aktif</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="bx bx-receipt avatar-initial bg-success rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Transaksi</span>
                        <h3 class="card-title mb-2">{{ summary.total_transaksi }}</h3>
                        <small class="text-muted">Dari semua pelanggan</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                                <i class="bx bx-money avatar-initial bg-info rounded-circle"></i>
                            </div>
                        </div>
                        <span class="fw-semibold d-block mb-1">Total Penjualan</span>
                        <h3 class="card-title mb-2">{{ formatCurrency(summary.total_penjualan) }}</h3>
                        <small class="text-muted">Revenue total</small>
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
                        <span class="fw-semibold d-block mb-1">Rata-rata/Pelanggan</span>
                        <h3 class="card-title mb-2">{{ formatCurrency(summary.rata_rata_per_pelanggan) }}</h3>
                        <small class="text-muted">Per pelanggan</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Data Pelanggan</h5>
            </div>
            <div class="card-body">
                <div v-if="!pelanggan || pelanggan.length === 0" class="py-4 text-center">
                    <i class="bx bx-user display-4 text-muted"></i>
                    <p class="text-muted mt-2">
                        {{ summary ? 'Tidak ada data pelanggan untuk filter yang dipilih' : 'Klik Generate untuk melihat laporan pelanggan' }}
                    </p>
                </div>

                <div v-else class="table-responsive">
                    <table class="table-hover table">
                        <thead>
                            <tr>
                                <th>ID Pelanggan</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Total Transaksi</th>
                                <th>Total Pembelian</th>
                                <th>Rata-rata/Transaksi</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr v-for="item in pelanggan" :key="item.id_pelanggan">
                                <td>
                                    <span class="badge bg-primary">{{ item.id_pelanggan }}</span>
                                </td>
                                <td>
                                    <strong>{{ item.nama }}</strong>
                                </td>
                                <td>{{ item.telepon || 'N/A' }}</td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 150px" :title="item.alamat">
                                        {{ item.alamat || 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ item.transaksi_count || 0 }}</span>
                                </td>
                                <td>
                                    <strong>{{ formatCurrency(item.transaksi_sum_total_harga || 0) }}</strong>
                                </td>
                                <td>
                                    {{
                                        item.transaksi_count > 0
                                            ? formatCurrency((item.transaksi_sum_total_harga || 0) / item.transaksi_count)
                                            : 'Rp 0'
                                    }}
                                </td>
                                <td>
                                    <span v-if="item.transaksi_count > 10" class="badge bg-success">VIP</span>
                                    <span v-else-if="item.transaksi_count > 5" class="badge bg-warning">Regular</span>
                                    <span v-else-if="item.transaksi_count > 0" class="badge bg-info">New</span>
                                    <span v-else class="badge bg-secondary">Inactive</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
