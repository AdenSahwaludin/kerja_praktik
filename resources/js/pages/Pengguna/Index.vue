<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

interface User {
    id: number;
    nama: string;
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
    roles: string[];
}>();

// Real-time filters
const searchTerm = ref('');
const roleFilter = ref('');

const filteredPengguna = computed(() => {
    return props.pengguna.data.filter((user) => {
        const matchesSearch =
            user.nama.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            user.email.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
            (user.telepon ?? '').toLowerCase().includes(searchTerm.value.toLowerCase());
        const matchesRole = roleFilter.value ? user.role === roleFilter.value : true;
        return matchesSearch && matchesRole;
    });
});

const deleteUser = (user: User) => {
    if (confirm(`Apakah Anda yakin ingin menghapus pengguna "${user.nama}"?`)) {
        router.delete(route('pengguna.destroy', user.id), {
            preserveScroll: true,
        });
    }
};

/**
 * Reset search and role filters
 */
const resetFilters = () => {
    searchTerm.value = '';
    roleFilter.value = '';
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
                        <input v-model="searchTerm" type="text" class="form-control" placeholder="Cari nama, email,atau telepon..." />
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Filter Role</label>
                        <select v-model="roleFilter" class="form-select">
                            <option value="">Semua Role</option>
                            <option v-for="role in roles" :key="role" :value="role">
                                {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                            </option>
                        </select>
                    </div>
                    <div class="col-md-5">
                        <div class="d-flex gap-2">
                            <button
                                @click="
                                    () => {
                                        searchTerm = '';
                                        roleFilter = '';
                                    }
                                "
                                class="btn btn-outline-secondary"
                            >
                                <i class="bx bx-refresh me-1"></i>
                                Reset Filters
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
                <span class="badge bg-primary">{{ filteredPengguna.length }} Pengguna</span>
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
                        <tr v-for="user in filteredPengguna" :key="user.id">
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-2">
                                        <div class="avatar-initial bg-label-primary rounded-circle">
                                            {{ user.nama?.charAt(0).toUpperCase() || 'U' }}
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ user.nama || 'Unknown' }}</h6>
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
                        <tr v-if="filteredPengguna.length === 0">
                            <td colspan="7" class="py-4 text-center">
                                <i class="bx bx-user-x display-4 text-muted"></i>
                                <p class="text-muted mt-2">Tidak ada pengguna ditemukan</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="filteredPengguna.length > props.pengguna.per_page" class="card-footer">
                <nav aria-label="Page navigation">
                    <ul class="pagination pagination-sm justify-content-center mb-0">
                        <li
                            v-for="link in props.pengguna.links"
                            :key="link.label"
                            :class="['page-item', { active: link.active, disabled: !link.url }]"
                        >
                            <Link v-if="link.url" :href="link.url" class="page-link" v-html="link.label" preserve-state />
                            <span v-else class="page-link" v-html="link.label" />
                        </li>
                    </ul>
                </nav>
                <div class="text-muted small mt-2 text-center">
                    Menampilkan {{ props.pengguna.from }} - {{ props.pengguna.to }} dari {{ props.pengguna.total }} pengguna
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
