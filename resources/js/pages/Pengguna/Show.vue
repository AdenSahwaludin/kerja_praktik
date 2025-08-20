<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

interface User {
    id: number;
    name: string;
    email: string;
    telepon?: string;
    role: string;
    terakhir_login?: string;
    created_at: string;
    updated_at: string;
}

const props = defineProps<{
    pengguna: User;
}>();

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const getRoleBadgeClass = (role: string) => {
    const roleClasses = {
        admin: 'bg-danger',
        manager: 'bg-warning',
        kasir: 'bg-info',
    };
    return roleClasses[role] || 'bg-secondary';
};
</script>

<template>
    <Head title="Detail Pengguna" />

    <DashboardLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold mb-0 py-3"><span class="text-muted fw-light">Manajemen / Pengguna /</span> Detail Pengguna</h4>
            <div class="d-flex gap-2">
                <Link :href="route('pengguna.edit', pengguna.id)" class="btn btn-primary">
                    <i class="bx bx-edit-alt me-1"></i>
                    Edit Pengguna
                </Link>
                <Link :href="route('pengguna.index')" class="btn btn-outline-secondary">
                    <i class="bx bx-arrow-back me-1"></i>
                    Kembali
                </Link>
            </div>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Informasi Pengguna</h5>
                        <span :class="`badge ${getRoleBadgeClass(pengguna.role)} fs-6`">
                            {{ pengguna.role.charAt(0).toUpperCase() + pengguna.role.slice(1) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h6 class="text-muted mb-1">Nama Lengkap</h6>
                                    <p class="fs-5 fw-semibold mb-0">{{ pengguna.name }}</p>
                                </div>

                                <div class="mb-4">
                                    <h6 class="text-muted mb-1">Email</h6>
                                    <p class="mb-0">
                                        <i class="bx bx-envelope me-2"></i>
                                        <a :href="`mailto:${pengguna.email}`" class="text-decoration-none">
                                            {{ pengguna.email }}
                                        </a>
                                    </p>
                                </div>

                                <div class="mb-4" v-if="pengguna.telepon">
                                    <h6 class="text-muted mb-1">Nomor Telepon</h6>
                                    <p class="mb-0">
                                        <i class="bx bx-phone me-2"></i>
                                        <a :href="`tel:${pengguna.telepon}`" class="text-decoration-none">
                                            {{ pengguna.telepon }}
                                        </a>
                                    </p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-4">
                                    <h6 class="text-muted mb-1">Role</h6>
                                    <span :class="`badge ${getRoleBadgeClass(pengguna.role)} fs-6 px-3 py-2`">
                                        <i class="bx bx-user-circle me-1"></i>
                                        {{ pengguna.role.charAt(0).toUpperCase() + pengguna.role.slice(1) }}
                                    </span>
                                </div>

                                <div class="mb-4" v-if="pengguna.terakhir_login">
                                    <h6 class="text-muted mb-1">Terakhir Login</h6>
                                    <p class="mb-0">
                                        <i class="bx bx-time me-2"></i>
                                        {{ formatDate(pengguna.terakhir_login) }}
                                    </p>
                                </div>

                                <div class="mb-4" v-else>
                                    <h6 class="text-muted mb-1">Status Login</h6>
                                    <span class="badge bg-warning">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Belum Pernah Login
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Riwayat Aktivitas -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Riwayat Akun</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Akun Dibuat</h6>
                                    <p class="text-muted mb-0">
                                        <i class="bx bx-calendar me-1"></i>
                                        {{ formatDate(pengguna.created_at) }}
                                    </p>
                                </div>
                            </div>

                            <div class="timeline-item" v-if="pengguna.updated_at !== pengguna.created_at">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Terakhir Diperbarui</h6>
                                    <p class="text-muted mb-0">
                                        <i class="bx bx-edit me-1"></i>
                                        {{ formatDate(pengguna.updated_at) }}
                                    </p>
                                </div>
                            </div>

                            <div class="timeline-item" v-if="pengguna.terakhir_login">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Terakhir Login</h6>
                                    <p class="text-muted mb-0">
                                        <i class="bx bx-log-in me-1"></i>
                                        {{ formatDate(pengguna.terakhir_login) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Aksi Cepat</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <Link :href="route('pengguna.edit', pengguna.id)" class="btn btn-primary">
                                <i class="bx bx-edit-alt me-2"></i>
                                Edit Pengguna
                            </Link>

                            <a :href="`mailto:${pengguna.email}`" class="btn btn-outline-info">
                                <i class="bx bx-envelope me-2"></i>
                                Kirim Email
                            </a>

                            <a v-if="pengguna.telepon" :href="`tel:${pengguna.telepon}`" class="btn btn-outline-success">
                                <i class="bx bx-phone me-2"></i>
                                Hubungi
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Role Information -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Informasi Role</h6>
                    </div>
                    <div class="card-body">
                        <div v-if="pengguna.role === 'admin'" class="text-center">
                            <i class="bx bx-shield-check text-danger display-4"></i>
                            <h6 class="mt-2">Administrator</h6>
                            <p class="text-muted small mb-0">Memiliki akses penuh ke semua fitur sistem</p>
                        </div>

                        <div v-else-if="pengguna.role === 'manager'" class="text-center">
                            <i class="bx bx-user-check text-warning display-4"></i>
                            <h6 class="mt-2">Manager</h6>
                            <p class="text-muted small mb-0">Mengelola operasional dan laporan</p>
                        </div>

                        <div v-else-if="pengguna.role === 'kasir'" class="text-center">
                            <i class="bx bx-money text-info display-4"></i>
                            <h6 class="mt-2">Kasir</h6>
                            <p class="text-muted small mb-0">Melakukan transaksi dan penjualan</p>
                        </div>
                    </div>
                </div>

                <!-- Account Status -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Status Akun</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="flex-shrink-0">
                                <span class="badge bg-success fs-6">Aktif</span>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-0">Akun Aktif</h6>
                                <small class="text-muted">Dapat mengakses sistem</small>
                            </div>
                        </div>

                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <i class="bx bx-check-circle text-success"></i>
                            </div>
                            <div class="ms-3 flex-grow-1">
                                <h6 class="mb-0">Email Terverifikasi</h6>
                                <small class="text-muted">{{ pengguna.email }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped>
.timeline {
    position: relative;
    padding-left: 2rem;
}

.timeline-item {
    position: relative;
    margin-bottom: 1.5rem;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -1.875rem;
    top: 1.5rem;
    width: 2px;
    height: calc(100% + 0.5rem);
    background-color: #e3e6f0;
}

.timeline-marker {
    position: absolute;
    left: -2rem;
    top: 0.25rem;
    width: 0.75rem;
    height: 0.75rem;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 3px #e3e6f0;
}

.timeline-content {
    margin-left: 0.5rem;
}

.badge.fs-6 {
    font-size: 0.875rem !important;
}

.display-4 {
    font-size: 2.5rem;
}
</style>
