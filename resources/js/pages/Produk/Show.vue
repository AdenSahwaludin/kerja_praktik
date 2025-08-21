<template>
  <Head title="Detail Produk" />
  <DashboardLayout>
    <h4 class="fw-bold mb-4 py-3">
      <span class="text-muted fw-light">Manajemen / Produk /</span> Detail Produk
    </h4>

    <div class="card shadow-sm">
      <div class="row g-0">
        <!-- Image Section -->
        <div class="col-md-4">
          <img
            v-if="produk.gambar"
            :src="`/storage/${produk.gambar}`"
            :alt="produk.nama"
            class="img-fluid h-100 w-100 object-fit-cover rounded-start"
          />
          <div
            v-else
            class="h-100 w-100 d-flex align-items-center justify-content-center bg-light rounded-start"
          >
            <i class="bx bx-image fs-1 text-muted"></i>
          </div>
        </div>
        <!-- Details Section -->
        <div class="col-md-8">
          <div class="card-body">
            <h5 class="card-title mb-3">{{ produk.nama }}</h5>
            <p class="text-muted small mb-3">Kode: <strong>{{ produk.id_produk }}</strong></p>

            <ul class="list-group list-group-flush">
              <li class="list-group-item d-flex justify-content-between">
                <span>Harga</span>
                <span class="fw-semibold">{{ formatCurrency(produk.harga) }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>HPP</span>
                <span class="fw-semibold">{{ formatCurrency(produk.biaya_produk) }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Stok (Min)</span>
                <span class="fw-semibold">{{ produk.stok }} ({{ produk.batas_stok }})</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Kategori</span>
                <span class="badge bg-primary">{{ produk.kategori.nama }}</span>
              </li>
              <li class="list-group-item d-flex justify-content-between">
                <span>Nomor BPOM</span>
                <span class="fw-semibold">{{ produk.nomor_bpom || '-' }}</span>
              </li>
            </ul>

            <div class="mt-4 d-flex justify-content-end">
              <Link :href="route('produk.edit', produk.id_produk)" class="btn btn-outline-primary me-2">
                <i class="bx bx-edit me-1"></i> Edit Produk
              </Link>
              <Link :href="route('produk.index')" class="btn btn-outline-secondary">
                <i class="bx bx-arrow-back me-1"></i> Kembali
              </Link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup lang="ts">
import DashboardLayout from '@/layouts/DashboardLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { defineProps } from 'vue';

interface Kategori {
  id_kategori: number;
  nama: string;
}
interface Produk {
  id_produk: string;
  nama: string;
  kategori: Kategori;
  harga: number;
  biaya_produk: number;
  stok: number;
  batas_stok: number;
  nomor_bpom?: string;
  gambar?: string;
}

const props = defineProps<{ produk: Produk }>();

const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
  }).format(amount);
};
</script>

<style scoped>
.form-label {
  font-weight: 600;
}
</style>
