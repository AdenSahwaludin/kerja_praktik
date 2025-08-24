<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Transaksi {
    id_transaksi: string;
    id_pelanggan: string;
    total_harga: number;
    status: string;
    created_at: string;
    pelanggan: {
        nama: string;
    };
    detail: Array<{
        produk: {
            nama: string;
        };
        jumlah: number;
        harga: number;
    }>;
    pembayaran: {
        metode_pembayaran: string;
        status_pembayaran: string;
    };
    user: {
        name: string;
    };
}

interface AnalisisPembayaran {
    metode_pembayaran: string;
    jumlah_transaksi: number;
    total_nilai: number;
    rata_rata_nilai: number;
}

interface AnalisisJam {
    jam: number;
    jumlah_transaksi: number;
    total_nilai: number;
}

interface AnalisisKasir {
    id: number;
    nama_kasir: string;
    jumlah_transaksi: number;
    total_nilai: number;
    rata_rata_per_transaksi: number;
}

interface Pelanggan {
    id_pelanggan: string;
    nama: string;
}

interface Summary {
    total_transaksi: number;
    total_nilai: number;
    rata_rata_transaksi: number;
    transaksi_per_status: {
        selesai: number;
        pending: number;
        dibatalkan: number;
    };
    transaksi_tertinggi: number;
    transaksi_terendah: number;
    jam_tersibuk: string | number;
    metode_terpopuler: string;
    periode: {
        mulai: string;
        selesai: string;
    };
}

interface Props {
    transaksi?: Transaksi[];
    analisisPembayaran?: AnalisisPembayaran[];
    analisisJam?: AnalisisJam[];
    analisisKasir?: AnalisisKasir[];
    pelanggan?: Pelanggan[];
    summary?: Summary;
    filters?: {
        tanggal_mulai?: string;
        tanggal_selesai?: string;
        status?: string;
        pelanggan?: string;
        metode_pembayaran?: string;
    };
}

const props = defineProps<Props>();

const tanggalMulai = ref(props.filters?.tanggal_mulai || '');
const tanggalSelesai = ref(props.filters?.tanggal_selesai || '');
const status = ref(props.filters?.status || 'semua');
const selectedPelanggan = ref(props.filters?.pelanggan || '');
const metodePembayaran = ref(props.filters?.metode_pembayaran || 'semua');
const showAnalisis = ref('transaksi'); // 'transaksi', 'pembayaran', 'jam', 'kasir'

const formatCurrency = (value: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(value);
};

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const formatJam = (jam: number) => {
    return `${jam.toString().padStart(2, '0')}:00`;
};

const getStatusBadge = (status: string) => {
    switch (status?.toLowerCase()) {
        case 'selesai':
            return { class: 'bg-success', text: 'Selesai' };
        case 'pending':
            return { class: 'bg-warning', text: 'Pending' };
        case 'dibatalkan':
            return { class: 'bg-danger', text: 'Dibatalkan' };
        default:
            return { class: 'bg-secondary', text: status || 'N/A' };
    }
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

    if (status.value !== 'semua') {
        params.status = status.value;
    }

    if (selectedPelanggan.value) {
        params.pelanggan = selectedPelanggan.value;
    }

    if (metodePembayaran.value !== 'semua') {
        params.metode_pembayaran = metodePembayaran.value;
    }

    router.get(route('laporan.transaksi'), params);
};

const resetFilters = () => {
    tanggalMulai.value = '';
    tanggalSelesai.value = '';
    status.value = 'semua';
    selectedPelanggan.value = '';
    metodePembayaran.value = 'semua';
    router.get(route('laporan.transaksi'));
};
</script>

<template>
    <Head title="Laporan Transaksi" />

    <DashboardLayout>
        <div class="mb-4">
            <h4 class="fw-bold mb-0 py-3"><span class="text-muted fw-light">Laporan /</span> Transaksi</h4>
        </div>

        <!-- Filter Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Filter Laporan</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2 mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                        <input type="date" class="form-control" id="tanggal_mulai" v-model="tanggalMulai" />
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                        <input type="date" class="form-control" id="tanggal_selesai" v-model="tanggalSelesai" />
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" v-model="status">
                            <option value="semua">Semua Status</option>
                            <option value="selesai">Selesai</option>
                            <option value="pending">Pending</option>
                            <option value="dibatalkan">Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="pelanggan" class="form-label">Pelanggan</label>
                        <select class="form-select" id="pelanggan" v-model="selectedPelanggan">
                            <option value="">Semua Pelanggan</option>
                            <option v-for="p in pelanggan" :key="p.id_pelanggan" :value="p.id_pelanggan">
                                {{ p.nama }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="metode_pembayaran" class="form-label">Metode Bayar</label>
                        <select class="form-select" id="metode_pembayaran" v-model="metodePembayaran">
                            <option value="semua">Semua Metode</option>
                            <option value="tunai">Tunai</option>
                            <option value="kredit">Kredit</option>
                            <option value="transfer">Transfer</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label">&nbsp;</label>
                        <div class="d-flex gap-1">
                            <button type="button" class="btn btn-primary btn-sm" @click="generateReport">
                                <i class="bx bx-search"></i>
                            </button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" @click="resetFilters">
                                <i class="bx bx-refresh"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Cards -->
        <div v-if="summary" class="row mb-4">
            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <div class="card">
                    <div class="card-body p-2 text-center">
                        <div class="avatar mx-auto mb-1">
                            <i class="bx bx-receipt avatar-initial bg-primary rounded-circle"></i>
                        </div>
                        <h5 class="mb-0">{{ summary.total_transaksi }}</h5>
                        <small class="text-muted">Total Transaksi</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <div class="card">
                    <div class="card-body p-2 text-center">
                        <div class="avatar mx-auto mb-1">
                            <i class="bx bx-money avatar-initial bg-success rounded-circle"></i>
                        </div>
                        <h6 class="small mb-0">{{ formatCurrency(summary.total_nilai) }}</h6>
                        <small class="text-muted">Total Nilai</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <div class="card">
                    <div class="card-body p-2 text-center">
                        <div class="avatar mx-auto mb-1">
                            <i class="bx bx-trending-up avatar-initial bg-info rounded-circle"></i>
                        </div>
                        <h6 class="small mb-0">{{ formatCurrency(summary.rata_rata_transaksi) }}</h6>
                        <small class="text-muted">Rata-rata</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <div class="card">
                    <div class="card-body p-2 text-center">
                        <div class="avatar mx-auto mb-1">
                            <i class="bx bx-time avatar-initial bg-warning rounded-circle"></i>
                        </div>
                        <h6 class="mb-0">{{ typeof summary.jam_tersibuk === 'number' ? formatJam(summary.jam_tersibuk) : summary.jam_tersibuk }}</h6>
                        <small class="text-muted">Jam Tersibuk</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <div class="card">
                    <div class="card-body p-2 text-center">
                        <div class="avatar mx-auto mb-1">
                            <i class="bx bx-credit-card avatar-initial bg-secondary rounded-circle"></i>
                        </div>
                        <h6 class="small mb-0">{{ summary.metode_terpopuler }}</h6>
                        <small class="text-muted">Metode Populer</small>
                    </div>
                </div>
            </div>

            <div class="col-lg-2 col-md-4 col-6 mb-3">
                <div class="card">
                    <div class="card-body p-2 text-center">
                        <div class="avatar mx-auto mb-1">
                            <i class="bx bx-bar-chart avatar-initial bg-dark rounded-circle"></i>
                        </div>
                        <h6 class="small mb-0">{{ formatCurrency(summary.transaksi_tertinggi) }}</h6>
                        <small class="text-muted">Tertinggi</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Summary -->
        <div v-if="summary" class="row mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <i class="bx bx-check-circle avatar-initial bg-success rounded-circle"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ summary.transaksi_per_status.selesai }}</h5>
                                    <small class="text-muted">Transaksi Selesai</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <i class="bx bx-time-five avatar-initial bg-warning rounded-circle"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ summary.transaksi_per_status.pending }}</h5>
                                    <small class="text-muted">Transaksi Pending</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="avatar me-3">
                                    <i class="bx bx-x-circle avatar-initial bg-danger rounded-circle"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ summary.transaksi_per_status.dibatalkan }}</h5>
                                    <small class="text-muted">Transaksi Dibatalkan</small>
                                </div>
                            </div>
                        </div>
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
                                :class="{ active: showAnalisis === 'transaksi' }"
                                role="tab"
                                @click="showAnalisis = 'transaksi'"
                            >
                                <i class="tf-icons bx bx-receipt me-1"></i>
                                Data Transaksi
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link"
                                :class="{ active: showAnalisis === 'pembayaran' }"
                                role="tab"
                                @click="showAnalisis = 'pembayaran'"
                            >
                                <i class="tf-icons bx bx-credit-card me-1"></i>
                                Analisis Pembayaran
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link"
                                :class="{ active: showAnalisis === 'jam' }"
                                role="tab"
                                @click="showAnalisis = 'jam'"
                            >
                                <i class="tf-icons bx bx-time me-1"></i>
                                Analisis per Jam
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link"
                                :class="{ active: showAnalisis === 'kasir' }"
                                role="tab"
                                @click="showAnalisis = 'kasir'"
                            >
                                <i class="tf-icons bx bx-user me-1"></i>
                                Performa Kasir
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Data Content -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <span v-if="showAnalisis === 'transaksi'">Data Transaksi</span>
                    <span v-else-if="showAnalisis === 'pembayaran'">Analisis Metode Pembayaran</span>
                    <span v-else-if="showAnalisis === 'jam'">Analisis Transaksi per Jam</span>
                    <span v-else-if="showAnalisis === 'kasir'">Performa Kasir</span>
                </h5>
            </div>
            <div class="card-body">
                <div v-if="!summary" class="py-4 text-center">
                    <i class="bx bx-receipt display-4 text-muted"></i>
                    <p class="text-muted mt-2">Silakan pilih periode untuk melihat laporan transaksi</p>
                </div>

                <!-- Tabel Transaksi -->
                <div v-else-if="showAnalisis === 'transaksi'" class="table-responsive">
                    <table class="table-hover table">
                        <thead>
                            <tr>
                                <th>ID Transaksi</th>
                                <th>Tanggal & Waktu</th>
                                <th>Pelanggan</th>
                                <th>Kasir</th>
                                <th>Total Item</th>
                                <th>Total Harga</th>
                                <th>Metode Bayar</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr v-for="item in transaksi" :key="item.id_transaksi">
                                <td>
                                    <span class="badge bg-primary">{{ item.id_transaksi }}</span>
                                </td>
                                <td>
                                    <small>{{ formatDate(item.created_at) }}</small>
                                </td>
                                <td>{{ item.pelanggan?.nama || 'N/A' }}</td>
                                <td>{{ item.user?.name || 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ item.detail?.length || 0 }} item</span>
                                </td>
                                <td>
                                    <strong>{{ formatCurrency(item.total_harga) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ item.pembayaran?.metode_pembayaran || 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="badge" :class="getStatusBadge(item.status).class">
                                        {{ getStatusBadge(item.status).text }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Analisis Pembayaran -->
                <div v-else-if="showAnalisis === 'pembayaran'" class="table-responsive">
                    <table class="table-hover table">
                        <thead>
                            <tr>
                                <th>Metode Pembayaran</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total Nilai</th>
                                <th>Rata-rata per Transaksi</th>
                                <th>Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr v-for="item in analisisPembayaran" :key="item.metode_pembayaran">
                                <td>
                                    <strong>{{ item.metode_pembayaran }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ item.jumlah_transaksi }} transaksi</span>
                                </td>
                                <td>
                                    <strong class="text-success">{{ formatCurrency(item.total_nilai) }}</strong>
                                </td>
                                <td>{{ formatCurrency(item.rata_rata_nilai) }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ summary && summary.total_nilai > 0 ? ((item.total_nilai / summary.total_nilai) * 100).toFixed(1) : 0 }}%
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Analisis per Jam -->
                <div v-else-if="showAnalisis === 'jam'" class="table-responsive">
                    <table class="table-hover table">
                        <thead>
                            <tr>
                                <th>Jam</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total Nilai</th>
                                <th>Rata-rata per Transaksi</th>
                                <th>Aktivitas</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr v-for="item in analisisJam" :key="item.jam">
                                <td>
                                    <strong>{{ formatJam(item.jam) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ item.jumlah_transaksi }} transaksi</span>
                                </td>
                                <td>
                                    <strong class="text-success">{{ formatCurrency(item.total_nilai) }}</strong>
                                </td>
                                <td>{{ formatCurrency(item.jumlah_transaksi > 0 ? item.total_nilai / item.jumlah_transaksi : 0) }}</td>
                                <td>
                                    <div class="progress" style="height: 6px">
                                        <div
                                            class="progress-bar"
                                            :style="{
                                                width:
                                                    analisisJam && analisisJam.length > 0
                                                        ? (item.jumlah_transaksi / Math.max(...analisisJam.map((a) => a.jumlah_transaksi))) * 100 +
                                                          '%'
                                                        : '0%',
                                            }"
                                        ></div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Performa Kasir -->
                <div v-else-if="showAnalisis === 'kasir'" class="table-responsive">
                    <table class="table-hover table">
                        <thead>
                            <tr>
                                <th>Kasir</th>
                                <th>Jumlah Transaksi</th>
                                <th>Total Nilai</th>
                                <th>Rata-rata per Transaksi</th>
                                <th>Kontribusi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            <tr v-for="item in analisisKasir" :key="item.id">
                                <td>
                                    <strong>{{ item.nama_kasir }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ item.jumlah_transaksi }} transaksi</span>
                                </td>
                                <td>
                                    <strong class="text-success">{{ formatCurrency(item.total_nilai) }}</strong>
                                </td>
                                <td>{{ formatCurrency(item.rata_rata_per_transaksi) }}</td>
                                <td>
                                    <span class="badge bg-primary">
                                        {{ summary && summary.total_nilai > 0 ? ((item.total_nilai / summary.total_nilai) * 100).toFixed(1) : 0 }}%
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
