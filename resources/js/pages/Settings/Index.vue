<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Pengaturan Sistem" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Pengaturan Sistem / Pengaturan /</span> Pengaturan Sistem</h4>

        <div class="row">
            <div class="col-12">
                <div class="nav-align-top mb-4">
                    <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link active"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-top-home"
                                aria-controls="navs-pills-top-home"
                                aria-selected="true"
                            >
                                <i class="tf-icons bx bx-cog"></i> General
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-top-profile"
                                aria-controls="navs-pills-top-profile"
                                aria-selected="false"
                            >
                                <i class="tf-icons bx bx-store"></i> Toko
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-top-messages"
                                aria-controls="navs-pills-top-messages"
                                aria-selected="false"
                            >
                                <i class="tf-icons bx bx-credit-card"></i> Pembayaran
                            </button>
                        </li>
                        <li class="nav-item">
                            <button
                                type="button"
                                class="nav-link"
                                role="tab"
                                data-bs-toggle="tab"
                                data-bs-target="#navs-pills-top-backup"
                                aria-controls="navs-pills-top-backup"
                                aria-selected="false"
                            >
                                <i class="tf-icons bx bx-hdd"></i> Backup
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="navs-pills-top-home" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Pengaturan Umum</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label for="appName" class="form-label">Nama Aplikasi</label>
                                                <input
                                                    class="form-control"
                                                    type="text"
                                                    id="appName"
                                                    name="appName"
                                                    value="Laravue KP"
                                                    placeholder="Nama Aplikasi"
                                                />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="appVersion" class="form-label">Versi</label>
                                                <input
                                                    class="form-control"
                                                    type="text"
                                                    id="appVersion"
                                                    name="appVersion"
                                                    value="1.0.0"
                                                    placeholder="Versi Aplikasi"
                                                />
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="timezone" class="form-label">Timezone</label>
                                                <select id="timezone" class="form-select">
                                                    <option value="Asia/Jakarta" selected>Asia/Jakarta (WIB)</option>
                                                    <option value="Asia/Makassar">Asia/Makassar (WITA)</option>
                                                    <option value="Asia/Jayapura">Asia/Jayapura (WIT)</option>
                                                </select>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label for="language" class="form-label">Bahasa</label>
                                                <select id="language" class="form-select">
                                                    <option value="id" selected>Bahasa Indonesia</option>
                                                    <option value="en">English</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <button type="submit" class="btn btn-primary me-2">Simpan Perubahan</button>
                                            <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-top-profile" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Pengaturan Toko</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Pengaturan informasi toko akan dikembangkan di sini.</p>
                                    <div class="alert alert-info" role="alert">
                                        <i class="bx bx-info-circle me-2"></i>
                                        Fitur pengaturan toko sedang dalam pengembangan.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-top-messages" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Pengaturan Pembayaran</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Pengaturan metode pembayaran akan dikembangkan di sini.</p>
                                    <div class="alert alert-info" role="alert">
                                        <i class="bx bx-info-circle me-2"></i>
                                        Fitur pengaturan pembayaran sedang dalam pengembangan.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="navs-pills-top-backup" role="tabpanel">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="mb-0">Backup & Restore</h5>
                                </div>
                                <div class="card-body">
                                    <p class="text-muted">Fitur backup dan restore data akan dikembangkan di sini.</p>
                                    <div class="alert alert-warning" role="alert">
                                        <i class="bx bx-shield me-2"></i>
                                        Fitur backup & restore sedang dalam pengembangan.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>
