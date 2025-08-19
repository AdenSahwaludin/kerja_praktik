<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps<{
    roles: string[];
}>();

const form = useForm({
    nama: '',
    email: '',
    telepon: '',
    role: '',
    kata_sandi: '',
    kata_sandi_confirmation: '',
});

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const submit = () => {
    form.post(route('pengguna.store'), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Tambah Pengguna" />

    <DashboardLayout>
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold py-3 mb-0">
                <span class="text-muted fw-light">Manajemen / Pengguna /</span> Tambah Pengguna
            </h4>
            <Link :href="route('pengguna.index')" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i>
                Kembali
            </Link>
        </div>

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Informasi Pengguna</h5>
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="nama">Nama Lengkap</label>
                                    <input
                                        id="nama"
                                        v-model="form.nama"
                                        type="text"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.nama }"
                                        placeholder="Masukkan nama lengkap"
                                        required
                                    />
                                    <div v-if="form.errors.nama" class="invalid-feedback">
                                        {{ form.errors.nama }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="email">Email</label>
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.email }"
                                        placeholder="contoh@email.com"
                                        required
                                    />
                                    <div v-if="form.errors.email" class="invalid-feedback">
                                        {{ form.errors.email }}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="telepon">Nomor Telepon</label>
                                    <input
                                        id="telepon"
                                        v-model="form.telepon"
                                        type="tel"
                                        class="form-control"
                                        :class="{ 'is-invalid': form.errors.telepon }"
                                        placeholder="08xxxxxxxxxx"
                                    />
                                    <div v-if="form.errors.telepon" class="invalid-feedback">
                                        {{ form.errors.telepon }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="role">Role</label>
                                    <select
                                        id="role"
                                        v-model="form.role"
                                        class="form-select"
                                        :class="{ 'is-invalid': form.errors.role }"
                                        required
                                    >
                                        <option value="">Pilih Role</option>
                                        <option v-for="role in roles" :key="role" :value="role">
                                            {{ role.charAt(0).toUpperCase() + role.slice(1) }}
                                        </option>
                                    </select>
                                    <div v-if="form.errors.role" class="invalid-feedback">
                                        {{ form.errors.role }}
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label" for="kata_sandi">Kata Sandi</label>
                                    <div class="input-group">
                                        <input
                                            id="kata_sandi"
                                            v-model="form.kata_sandi"
                                            :type="showPassword ? 'text' : 'password'"
                                            class="form-control"
                                            :class="{ 'is-invalid': form.errors.kata_sandi }"
                                            placeholder="Masukkan kata sandi"
                                            required
                                        />
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            @click="showPassword = !showPassword"
                                        >
                                            <i :class="showPassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                                        </button>
                                    </div>
                                    <div v-if="form.errors.kata_sandi" class="invalid-feedback">
                                        {{ form.errors.kata_sandi }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label" for="kata_sandi_confirmation">Konfirmasi Kata Sandi</label>
                                    <div class="input-group">
                                        <input
                                            id="kata_sandi_confirmation"
                                            v-model="form.kata_sandi_confirmation"
                                            :type="showConfirmPassword ? 'text' : 'password'"
                                            class="form-control"
                                            placeholder="Ulangi kata sandi"
                                            required
                                        />
                                        <button
                                            type="button"
                                            class="btn btn-outline-secondary"
                                            @click="showConfirmPassword = !showConfirmPassword"
                                        >
                                            <i :class="showConfirmPassword ? 'bx bx-hide' : 'bx bx-show'"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <Link :href="route('pengguna.index')" class="btn btn-outline-secondary">
                                    Batal
                                </Link>
                                <button
                                    type="submit"
                                    class="btn btn-primary"
                                    :disabled="form.processing"
                                >
                                    <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                                    <i v-else class="bx bx-save me-1"></i>
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Pengguna' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Informasi Role</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-danger me-2">Admin</span>
                                <small class="text-muted">Akses penuh sistem</small>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <span class="badge bg-warning me-2">Manager</span>
                                <small class="text-muted">Akses laporan & analisis</small>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="badge bg-info me-2">Kasir</span>
                                <small class="text-muted">Akses transaksi & produk</small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Tips Keamanan</h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0 small">
                            <li class="mb-2">
                                <i class="bx bx-check text-success me-1"></i>
                                Gunakan kata sandi minimal 8 karakter
                            </li>
                            <li class="mb-2">
                                <i class="bx bx-check text-success me-1"></i>
                                Kombinasi huruf besar, kecil, dan angka
                            </li>
                            <li class="mb-2">
                                <i class="bx bx-check text-success me-1"></i>
                                Hindari informasi pribadi
                            </li>
                            <li>
                                <i class="bx bx-check text-success me-1"></i>
                                Gunakan karakter khusus (!@#$)
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped>
.input-group .btn {
    border-left: 0;
}

.form-control:focus + .btn {
    border-color: #86b7fe;
}
</style>
