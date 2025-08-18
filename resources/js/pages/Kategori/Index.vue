<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface Kategori {
    id_kategori: number;
    nama: string;
    produk_count: number;
    created_at: string;
    updated_at: string;
}

interface PaginatedData {
    data: Kategori[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
}

const props = defineProps<{
    kategori: PaginatedData;
}>();

const searchTerm = ref('');
const showDeleteModal = ref(false);
const showCreateModal = ref(false);
const showEditModal = ref(false);
const selectedKategori = ref<Kategori | null>(null);

const createForm = useForm({
    nama: '',
});

const editForm = useForm({
    nama: '',
});

const deleteForm = useForm({});

const filteredKategori = computed(() => {
    if (!searchTerm.value) return props.kategori.data;

    return props.kategori.data.filter((item) => item.nama.toLowerCase().includes(searchTerm.value.toLowerCase()));
});

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    });
};

const confirmDelete = (kategori: Kategori) => {
    selectedKategori.value = kategori;
    showDeleteModal.value = true;
};

const deleteKategori = () => {
    if (selectedKategori.value) {
        deleteForm.delete(route('kategori.destroy', selectedKategori.value.id_kategori), {
            onSuccess: () => {
                showDeleteModal.value = false;
                selectedKategori.value = null;
            },
        });
    }
};

const openCreateModal = () => {
    createForm.reset();
    showCreateModal.value = true;
};

const createKategori = () => {
    createForm.post(route('kategori.store'), {
        onSuccess: () => {
            showCreateModal.value = false;
            createForm.reset();
        },
    });
};

const openEditModal = (kategori: Kategori) => {
    selectedKategori.value = kategori;
    editForm.nama = kategori.nama;
    showEditModal.value = true;
};

const updateKategori = () => {
    if (selectedKategori.value) {
        editForm.put(route('kategori.update', selectedKategori.value.id_kategori), {
            onSuccess: () => {
                showEditModal.value = false;
                selectedKategori.value = null;
                editForm.reset();
            },
        });
    }
};

const resetSearch = () => {
    searchTerm.value = '';
};
</script>

<template>
    <Head title="Daftar Kategori" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Manajemen / Produk /</span> Daftar Kategori</h4>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Kategori</h5>
                <button @click="openCreateModal" class="btn btn-primary btn-sm">
                    <i class="bx bx-plus me-1"></i>
                    Tambah Kategori
                </button>
            </div>

            <div class="card-body">
                <!-- Search -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input v-model="searchTerm" type="text" class="form-control" placeholder="Cari kategori..." />
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button @click="resetSearch" class="btn btn-outline-secondary w-100">
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
                                <th>ID</th>
                                <th>Nama Kategori</th>
                                <th>Jumlah Produk</th>
                                <th>Dibuat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-if="filteredKategori.length === 0">
                                <td colspan="5" class="text-muted py-4 text-center">
                                    <i class="bx bx-category fs-1 d-block mb-2"></i>
                                    Tidak ada kategori ditemukan
                                </td>
                            </tr>
                            <tr v-for="item in filteredKategori" :key="item.id_kategori">
                                <td>
                                    <span class="fw-medium">#{{ item.id_kategori }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar avatar-sm me-2">
                                            <span class="avatar-initial bg-label-primary rounded">
                                                <i class="bx bx-category"></i>
                                            </span>
                                        </div>
                                        <div class="fw-medium">{{ item.nama }}</div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-label-info">{{ item.produk_count }} Produk</span>
                                </td>
                                <td>
                                    <span class="text-muted">{{ formatDate(item.created_at) }}</span>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <Link :href="route('kategori.show', item.id_kategori)" class="dropdown-item">
                                                    <i class="bx bx-show me-2"></i>
                                                    Detail
                                                </Link>
                                            </li>
                                            <li>
                                                <a @click.prevent="openEditModal(item)" class="dropdown-item" href="#">
                                                    <i class="bx bx-edit me-2"></i>
                                                    Edit
                                                </a>
                                            </li>
                                            <li><hr class="dropdown-divider" /></li>
                                            <li>
                                                <a
                                                    @click.prevent="confirmDelete(item)"
                                                    class="dropdown-item text-danger"
                                                    :class="{ disabled: item.produk_count > 0 }"
                                                    href="#"
                                                >
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
                    <div class="text-muted">Menampilkan {{ kategori.from }} hingga {{ kategori.to }} dari {{ kategori.total }} kategori</div>
                    <nav v-if="kategori.last_page > 1">
                        <ul class="pagination pagination-sm mb-0">
                            <li :class="['page-item', { disabled: kategori.current_page === 1 }]">
                                <Link
                                    :href="route('kategori.index', { page: kategori.current_page - 1 })"
                                    class="page-link"
                                    :class="{ 'text-muted': kategori.current_page === 1 }"
                                >
                                    Previous
                                </Link>
                            </li>
                            <li
                                v-for="page in Math.min(kategori.last_page, 5)"
                                :key="page"
                                :class="['page-item', { active: page === kategori.current_page }]"
                            >
                                <Link :href="route('kategori.index', { page })" class="page-link">
                                    {{ page }}
                                </Link>
                            </li>
                            <li :class="['page-item', { disabled: kategori.current_page === kategori.last_page }]">
                                <Link
                                    :href="route('kategori.index', { page: kategori.current_page + 1 })"
                                    class="page-link"
                                    :class="{ 'text-muted': kategori.current_page === kategori.last_page }"
                                >
                                    Next
                                </Link>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- Create Modal -->
        <div class="modal fade" :class="{ show: showCreateModal }" :style="{ display: showCreateModal ? 'block' : 'none' }" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah Kategori Baru</h5>
                        <button type="button" class="btn-close" @click="showCreateModal = false"></button>
                    </div>
                    <form @submit.prevent="createKategori">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="create-nama" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input
                                    id="create-nama"
                                    v-model="createForm.nama"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': createForm.errors.nama }"
                                    placeholder="Masukkan nama kategori"
                                    required
                                />
                                <div v-if="createForm.errors.nama" class="invalid-feedback">
                                    {{ createForm.errors.nama }}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="showCreateModal = false">Batal</button>
                            <button type="submit" class="btn btn-primary" :disabled="createForm.processing">
                                <span v-if="createForm.processing" class="spinner-border spinner-border-sm me-2"></span>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-if="showCreateModal" class="modal-backdrop fade show"></div>

        <!-- Edit Modal -->
        <div class="modal fade" :class="{ show: showEditModal }" :style="{ display: showEditModal ? 'block' : 'none' }" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Kategori</h5>
                        <button type="button" class="btn-close" @click="showEditModal = false"></button>
                    </div>
                    <form @submit.prevent="updateKategori">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="edit-nama" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                                <input
                                    id="edit-nama"
                                    v-model="editForm.nama"
                                    type="text"
                                    class="form-control"
                                    :class="{ 'is-invalid': editForm.errors.nama }"
                                    placeholder="Masukkan nama kategori"
                                    required
                                />
                                <div v-if="editForm.errors.nama" class="invalid-feedback">
                                    {{ editForm.errors.nama }}
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="showEditModal = false">Batal</button>
                            <button type="submit" class="btn btn-primary" :disabled="editForm.processing">
                                <span v-if="editForm.processing" class="spinner-border spinner-border-sm me-2"></span>
                                Update
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div v-if="showEditModal" class="modal-backdrop fade show"></div>

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
                            Apakah Anda yakin ingin menghapus kategori <strong>{{ selectedKategori?.nama }}</strong
                            >?
                        </p>
                        <p v-if="selectedKategori?.produk_count && selectedKategori.produk_count > 0" class="text-danger small">
                            <i class="bx bx-warning me-1"></i>
                            Kategori ini memiliki {{ selectedKategori.produk_count }} produk dan tidak dapat dihapus.
                        </p>
                        <p v-else class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="showDeleteModal = false">Batal</button>
                        <button
                            v-if="!selectedKategori?.produk_count || selectedKategori.produk_count === 0"
                            type="button"
                            class="btn btn-danger"
                            @click="deleteKategori"
                            :disabled="deleteForm.processing"
                        >
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
