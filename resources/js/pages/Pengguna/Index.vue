<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string; // Changed from 'nama' to 'name'
    email: string;
    telepon?: string;
    role: string;
    terakhir_login?: string;
    created_at: string;
}

interface PaginatedData {
    data: User[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    from: number;
    to: number;
    links: Array<{
        url?: string;
        label: string;
        active: boolean;
    }>;
}

const props = defineProps<{
    pengguna: PaginatedData;
    filters: {
        search?: string;
        role?: string;
    };
    roles: string[];
}>();

// Search and filters
const searchForm = useForm({
    search: props.filters.search || '',
    role: props.filters.role || '',
});

const search = () => {
    searchForm.get(route('pengguna.index'), {
        preserveState: true,
        replace: true,
    });
};

const clearFilters = () => {
    searchForm.reset();
    searchForm.get(route('pengguna.index'));
};

// Delete user
const deleteUser = (user: User) => {
    if (confirm(`Apakah Anda yakin ingin menghapus pengguna "${user.name}"?`)) {
        router.delete(route('pengguna.destroy', user.id), {
            preserveScroll: true,
        });
    }
};

// Role badge colors
const getRoleBadgeColor = (role: string) => {
    const colors: Record<string, string> = {
        admin: 'bg-danger',
        manager: 'bg-warning',
        kasir: 'bg-info',
    };
    return colors[role] || 'bg-secondary';
};

// Format date
const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};
</script>

<template>
    <Head title="Manajemen Pengguna" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Manajemen /</span> Pengguna</h4>

        <!-- Filters and Search -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Cari Pengguna</label>
                        <input
                            v-model="searchForm.search"
                            type="text"
                            class="form-control"
                            placeholder="Nama, email, atau telepon..."
                            @keyup.enter="search"
                        />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter Role</label>
                        <select v-model="searchForm.role" class="form-select">
                            <option value="">Semua Role</option>
                            <option v-for="role in roles" :key="role" :value="role">
                                {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex gap-2">
                            <button @click="search" class="btn btn-primary">
                                <i class="bx bx-search me-1"></i>
                                Cari
                            </button>
                            <button @click="clearFilters" class="btn btn-outline-secondary">
                                <i class="bx bx-refresh me-1"></i>
                                Reset
                            </button>
                            <Link :href="route('pengguna.create')" class="btn btn-success ms-auto">
                                <i class="bx bx-user-plus me-1"></i>
                                Tambah Pengguna
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Pengguna</h5>
                <span class="badge bg-primary">{{ pengguna.total }} Pengguna</span>
            </div>

            <div class="table-responsive">
                <table class="table-hover table">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Role</th>
                            <th>Terakhir Login</th>
                            <th>Bergabung</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in pengguna.data" :key="user.id">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-initial bg-label-primary rounded-circle">
                                            {{ user.name?.charAt(0).toUpperCase() || 'U' }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ user.name || 'Unknown' }}</h6>
                                    </div>
                                </div>
                            </td>
                            <td>{{ user.email }}</td>
                            <td>
                                <span v-if="user.telepon" class="text-body">{{ user.telepon }}</span>
                                <span v-else class="text-muted">-</span>
                            </td>
                            <td>
                                <span :class="['badge', getRoleBadgeColor(user.role || 'user')]">
                                    {{ user.role?.charAt(0).toUpperCase() + user.role?.slice(1) || 'User' }}
                                </span>
                            </td>
                            <td>
                                <span v-if="user.terakhir_login" class="text-body">
                                    {{ formatDate(user.terakhir_login) }}
                                </span>
                                <span v-else class="text-muted">Belum pernah login</span>
                            </td>
                            <td>{{ formatDate(user.created_at) }}</td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <Link :href="route('pengguna.show', user.id)" class="dropdown-item">
                                                <i class="bx bx-show me-2"></i>
                                                Lihat Detail
                                            </Link>
                                        </li>
                                        <li>
                                            <Link :href="route('pengguna.edit', user.id)" class="dropdown-item">
                                                <i class="bx bx-edit me-2"></i>
                                                Edit
                                            </Link>
                                        </li>
                                        <li><hr class="dropdown-divider" /></li>
                                        <li>
                                            <button @click="deleteUser(user)" class="dropdown-item text-danger">
                                                <i class="bx bx-trash me-2"></i>
                                                Hapus
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="pengguna.data.length === 0">
                            <td colspan="7" class="py-4 text-center">
                                <i class="bx bx-user-x display-4 text-muted"></i>
                                <p class="text-muted mt-2">Tidak ada pengguna ditemukan</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="pengguna.last_page > 1" class="card-footer">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <li v-for="link in pengguna.links" :key="link.label" :class="['page-item', { active: link.active, disabled: !link.url }]">
                            <Link v-if="link.url" :href="link.url" class="page-link" v-html="link.label" preserve-state />
                            <span v-else class="page-link" v-html="link.label" />
                        </li>
                    </ul>
                </nav>
                <div class="text-muted small mt-2 text-center">
                    Menampilkan {{ pengguna.from }} - {{ pengguna.to }} dari {{ pengguna.total }} pengguna
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped>
.avatar-initial {
    font-size: 0.875rem;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.dropdown-toggle::after {
    display: none;
}
</style>
