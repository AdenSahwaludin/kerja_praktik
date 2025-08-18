<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Kategori {
    id_kategori: number;
    nama: string;
}

const props = defineProps<{
    kategori: Kategori[];
}>();

const form = useForm({
    id_produk: '',
    nama: '',
    id_kategori: '',
    gambar: null as File | null,
    nomor_bpom: '',
    harga: '',
    biaya_produk: '',
    stok: '',
    batas_stok: '',
});

const imagePreview = computed(() => {
    if (form.gambar) {
        return URL.createObjectURL(form.gambar);
    }
    return null;
});

const handleImageChange = (event: Event) => {
    const target = event.target as HTMLInputElement;
    if (target.files && target.files[0]) {
        form.gambar = target.files[0];
    }
};

const submit = () => {
    form.post(route('produk.store'));
};
</script>

<template>
    <Head title="Tambah Produk" />

    <DashboardLayout>
        <h4 class="fw-bold mb-4 py-3"><span class="text-muted fw-light">Manajemen / Produk /</span> Tambah Produk</h4>

        <form @submit.prevent="submit">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Informasi Produk</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_produk" class="form-label">Kode Produk <span class="text-danger">*</span></label>
                                        <input
                                            id="id_produk"
                                            v-model="form.id_produk"
                                            type="text"
                                            class="form-control"
                                            :class="{ 'is-invalid': form.errors.id_produk }"
                                            placeholder="Masukkan kode produk (max 13 karakter)"
                                            maxlength="13"
                                            required
                                        />
                                        <div v-if="form.errors.id_produk" class="invalid-feedback">
                                            {{ form.errors.id_produk }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nama" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                        <input
                                            id="nama"
                                            v-model="form.nama"
                                            type="text"
                                            class="form-control"
                                            :class="{ 'is-invalid': form.errors.nama }"
                                            placeholder="Masukkan nama produk"
                                            required
                                        />
                                        <div v-if="form.errors.nama" class="invalid-feedback">
                                            {{ form.errors.nama }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="id_kategori" class="form-label">Kategori <span class="text-danger">*</span></label>
                                        <select
                                            id="id_kategori"
                                            v-model="form.id_kategori"
                                            class="form-select"
                                            :class="{ 'is-invalid': form.errors.id_kategori }"
                                            required
                                        >
                                            <option value="">Pilih Kategori</option>
                                            <option v-for="kat in kategori" :key="kat.id_kategori" :value="kat.id_kategori">
                                                {{ kat.nama }}
                                            </option>
                                        </select>
                                        <div v-if="form.errors.id_kategori" class="invalid-feedback">
                                            {{ form.errors.id_kategori }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nomor_bpom" class="form-label">Nomor BPOM</label>
                                        <input
                                            id="nomor_bpom"
                                            v-model="form.nomor_bpom"
                                            type="text"
                                            class="form-control"
                                            :class="{ 'is-invalid': form.errors.nomor_bpom }"
                                            placeholder="Masukkan nomor BPOM (opsional)"
                                        />
                                        <div v-if="form.errors.nomor_bpom" class="invalid-feedback">
                                            {{ form.errors.nomor_bpom }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="harga" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input
                                                id="harga"
                                                v-model="form.harga"
                                                type="number"
                                                class="form-control"
                                                :class="{ 'is-invalid': form.errors.harga }"
                                                placeholder="0"
                                                min="0"
                                                step="100"
                                                required
                                            />
                                        </div>
                                        <div v-if="form.errors.harga" class="invalid-feedback">
                                            {{ form.errors.harga }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="biaya_produk" class="form-label">Harga Pokok Produksi <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input
                                                id="biaya_produk"
                                                v-model="form.biaya_produk"
                                                type="number"
                                                class="form-control"
                                                :class="{ 'is-invalid': form.errors.biaya_produk }"
                                                placeholder="0"
                                                min="0"
                                                step="100"
                                                required
                                            />
                                        </div>
                                        <div v-if="form.errors.biaya_produk" class="invalid-feedback">
                                            {{ form.errors.biaya_produk }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stok" class="form-label">Stok <span class="text-danger">*</span></label>
                                        <input
                                            id="stok"
                                            v-model="form.stok"
                                            type="number"
                                            class="form-control"
                                            :class="{ 'is-invalid': form.errors.stok }"
                                            placeholder="0"
                                            min="0"
                                            required
                                        />
                                        <div v-if="form.errors.stok" class="invalid-feedback">
                                            {{ form.errors.stok }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="batas_stok" class="form-label">Batas Minimum Stok <span class="text-danger">*</span></label>
                                        <input
                                            id="batas_stok"
                                            v-model="form.batas_stok"
                                            type="number"
                                            class="form-control"
                                            :class="{ 'is-invalid': form.errors.batas_stok }"
                                            placeholder="0"
                                            min="0"
                                            required
                                        />
                                        <div v-if="form.errors.batas_stok" class="invalid-feedback">
                                            {{ form.errors.batas_stok }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Gambar Produk</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="gambar" class="form-label">Upload Gambar</label>
                                <input
                                    id="gambar"
                                    type="file"
                                    class="form-control"
                                    :class="{ 'is-invalid': form.errors.gambar }"
                                    accept="image/jpeg,image/png,image/jpg,image/gif"
                                    @change="handleImageChange"
                                />
                                <div class="form-text">Format: JPEG, PNG, JPG, GIF. Max: 2MB</div>
                                <div v-if="form.errors.gambar" class="invalid-feedback">
                                    {{ form.errors.gambar }}
                                </div>
                            </div>

                            <div v-if="imagePreview" class="text-center">
                                <img :src="imagePreview" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px" />
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" :disabled="form.processing">
                                    <span v-if="form.processing" class="spinner-border spinner-border-sm me-2"></span>
                                    <i class="bx bx-save me-1"></i>
                                    Simpan Produk
                                </button>
                                <Link :href="route('produk.index')" class="btn btn-outline-secondary">
                                    <i class="bx bx-arrow-back me-1"></i>
                                    Kembali
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </DashboardLayout>
</template>
