<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Kategori {
    id_kategori: number;
    nama: string;
}

interface Produk {
    id_produk: string;
    nama: string;
    id_kategori: number;
    kategori: Kategori;
    gambar: string | null;
    nomor_bpom: string | null;
    harga: number;
    biaya_produk: number;
    stok: number;
    batas_stok: number;
}

interface PaginatedData {
    data: Produk[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

const props = defineProps<{
    produk: PaginatedData;
    kategori: Kategori[];
}>();

const searchTerm = ref('');
const selectedKategori = ref('');
const showDeleteModal = ref(false);
const selectedProduk = ref<Produk | null>(null);

const deleteForm = useForm({});

const filteredProduk = computed(() => {
    let filtered = props.produk.data;

    if (searchTerm.value) {
        filtered = filtered.filter(
            (item) =>
                item.nama.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                item.id_produk.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
                item.kategori.nama.toLowerCase().includes(searchTerm.value.toLowerCase()),
        );
    }

    if (selectedKategori.value) {
        filtered = filtered.filter((item) => item.id_kategori.toString() === selectedKategori.value);
    }

    return filtered;
});

const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
    }).format(amount);
};

const getStokStatus = (stok: number, batasStok: number) => {
    if (stok <= 0) return { class: 'bg-danger', text: 'Habis' };
    if (stok <= batasStok) return { class: 'bg-warning', text: 'Rendah' };
    return { class: 'bg-success', text: 'Tersedia' };
};

const confirmDelete = (produk: Produk) => {
    selectedProduk.value = produk;
    showDeleteModal.value = true;
};

const deleteProduk = () => {
    if (selectedProduk.value) {
        deleteForm.delete(route('produk.destroy', selectedProduk.value.id_produk), {
            onSuccess: () => {
                showDeleteModal.value = false;
                selectedProduk.value = null;
            },
        });
    }
};

const resetFilters = () => {
    searchTerm.value = '';
    selectedKategori.value = '';
};
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
                    <Link :href="route('produk.create')" class="btn btn-primary btn-sm">
                        <i class="bx bx-plus me-1"></i>
                        Tambah Produk
                    </Link>
                </div>
            </div>

            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input v-model="searchTerm" type="text" class="form-control" placeholder="Cari produk, kode, atau kategori..." />
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select v-model="selectedKategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <option v-for="kat in kategori" :key="kat.id_kategori" :value="kat.id_kategori.toString()">
                                {{ kat.nama }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button @click="resetFilters" class="btn btn-outline-secondary w-100">
                            <i class="bx bx-refresh me-1"></i>
                            Reset
                        </button>
                    </div>
                </div>

                <!-- Table -->
                <div class="table-responsive">
                    <table class="table-hover table">
                        <thead class="table-light">
                            <tr>
                                <th>Gambar</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredProduk.length === 0">
                                <td colspan="8" class="text-muted py-4 text-center">
                                    <i class="bx bx-package fs-1 d-block mb-2"></i>
                                    Tidak ada produk ditemukan
                                </td>
                            </tr>
                            <tr v-for="item in filteredProduk" :key="item.id_produk">
                                <td>
                                    <div class="avatar avatar-sm">
                                        <img v-if="item.gambar" :src="`/storage/${item.gambar}`" :alt="item.nama" class="rounded" />
                                        <span v-else class="avatar-initial bg-label-secondary rounded">
                                            <i class="bx bx-package"></i>
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <strong>{{ item.id_produk }}</strong>
                                    <div v-if="item.nomor_bpom" class="small text-muted">BPOM: {{ item.nomor_bpom }}</div>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ item.nama }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-label-primary">{{ item.kategori.nama }}</span>
                                </td>
                                <td>
                                    <div class="fw-medium">{{ formatCurrency(item.harga) }}</div>
                                    <div class="small text-muted">HPP: {{ formatCurrency(item.biaya_produk) }}</div>
                                </td>
                                <td>
                                    <span class="fw-medium">{{ item.stok }}</span>
                                    <div class="small text-muted">Min: {{ item.batas_stok }}</div>
                                </td>
                                <td>
                                    <span :class="['badge', getStokStatus(item.stok, item.batas_stok).class]">
                                        {{ getStokStatus(item.stok, item.batas_stok).text }}
                                    </span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <Link :href="route('produk.show', item.id_produk)" class="dropdown-item">
                                                    <i class="bx bx-show me-2"></i>
                                                    Detail
                                                </Link>
                                            </li>
                                            <li>
                                                <Link :href="route('produk.edit', item.id_produk)" class="dropdown-item">
                                                    <i class="bx bx-edit me-2"></i>
                                                    Edit
                                                </Link>
                                            </li>
                                            <li><hr class="dropdown-divider" /></li>
                                            <li>
                                                <a @click.prevent="confirmDelete(item)" class="dropdown-item text-danger" href="#">
                                                    <i class="bx bx-trash me-2"></i>
                                                    Hapus
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Info -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">Menampilkan {{ produk.from }} hingga {{ produk.to }} dari {{ produk.total }} produk</div>
                    <nav v-if="produk.last_page > 1">
                        <ul class="pagination pagination-sm mb-0">
                            <li :class="['page-item', { disabled: produk.current_page === 1 }]">
                                <Link
                                    :href="route('produk.index', { page: produk.current_page - 1 })"
                                    class="page-link"
                                    :class="{ 'text-muted': produk.current_page === 1 }"
                                >
                                    Previous
                                </Link>
                            </li>
                            <li
                                v-for="page in Math.min(produk.last_page, 5)"
                                :key="page"
                                :class="['page-item', { active: page === produk.current_page }]"
                            >
                                <Link :href="route('produk.index', { page })" class="page-link">
                                    {{ page }}
                                </Link>
                            </li>
                            <li :class="['page-item', { disabled: produk.current_page === produk.last_page }]">
                                <Link
                                    :href="route('produk.index', { page: produk.current_page + 1 })"
                                    class="page-link"
                                    :class="{ 'text-muted': produk.current_page === produk.last_page }"
                                >
                                    Next
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" :class="{ show: showDeleteModal }" :style="{ display: showDeleteModal ? 'block' : 'none' }" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus</h5>
                        <button type="button" class="btn-close" @click="showDeleteModal = false"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Apakah Anda yakin ingin menghapus produk <strong>{{ selectedProduk?.nama }}</strong
                            >?
                        </p>
                        <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="showDeleteModal = false">Batal</button>
                        <button type="button" class="btn btn-danger" @click="deleteProduk" :disabled="deleteForm.processing">
                            <span v-if="deleteForm.processing" class="spinner-border spinner-border-sm me-2"></span>
                            Hapus
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showDeleteModal" class="modal-backdrop fade show"></div>
    </DashboardLayout>
</template>
