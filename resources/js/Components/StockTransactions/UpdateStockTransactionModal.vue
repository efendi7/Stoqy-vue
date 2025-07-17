<script setup>
import { useForm } from '@inertiajs/vue3'
import { watch } from 'vue' // <-- 1. Import 'watch'
import Modal from '@/Components/Modal.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'

const props = defineProps({
  show: Boolean,
  transaction: Object,
  products: Object,
  users: Object,
})

const emit = defineEmits(['close'])

// 2. Inisialisasi form dengan nilai kosong/default agar tidak error
const form = useForm({
  product_id: '',
  user_id: '',
  type: 'Masuk',
  quantity: 0,
  transaction_date: '',
  notes: '',
})

// 3. Awasi perubahan pada props.transaction
watch(() => props.transaction, (newTransaction) => {
  // Hanya jalankan jika newTransaction memiliki data (tidak null)
  if (newTransaction) {
    // Isi form dengan data dari prop yang baru
    form.defaults({
        product_id: newTransaction.product_id,
        user_id: newTransaction.user_id,
        type: newTransaction.type,
        quantity: newTransaction.quantity,
        // Format tanggal agar sesuai dengan input type="date" (YYYY-MM-DD)
        transaction_date: newTransaction.transaction_date.split('T')[0],
        notes: newTransaction.notes || '',
    });
    form.reset(); // Reset form ke nilai default yang baru
  }
})

const submit = () => {
  // Pastikan props.transaction ada sebelum submit
  if (!props.transaction) return;

  form.put(route('stock_transactions.update', props.transaction.id), {
    preserveScroll: true,
    onSuccess: () => emit('close'),
    // form.reset() tidak perlu di sini karena akan ditangani oleh handleClose
  })
}

const handleClose = () => {
  emit('close')
}
</script>

<template>
  <Modal :show="show" @close="handleClose" max-width="md">
    <div v-if="transaction" class="p-6 bg-white dark:bg-gray-800 rounded-2xl">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-6 pt-2">
        Edit Transaksi Stok
      </h2>

      <form @submit.prevent="submit" class="space-y-5">
        <!-- Produk -->
        <div>
          <InputLabel for="product_id" value="Produk" />
          <select
            id="product_id"
            v-model="form.product_id"
            class="w-full mt-1 p-2 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
          >
            <option disabled value="">-- Pilih Produk --</option>
            <option v-for="product in products.data" :key="product.id" :value="product.id">
  {{ product.name }}
</option>
          </select>
          <InputError :message="form.errors.product_id" class="mt-1" />
        </div>

        <!-- User -->
        <div>
          <InputLabel for="user_id" value="User" />
          <select
            id="user_id"
            v-model="form.user_id"
            class="w-full mt-1 p-2 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
          >
            <option disabled value="">-- Pilih User --</option>
            <option v-for="user in users.data" :key="user.id" :value="user.id">
  {{ user.name }}
</option>
          </select>
          <InputError :message="form.errors.user_id" class="mt-1" />
        </div>

        <!-- Jenis Transaksi -->
        <div>
          <InputLabel for="type" value="Jenis Transaksi" />
          <select
            id="type"
            v-model="form.type"
            class="w-full mt-1 p-2 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
          >
            <option value="Masuk">Masuk</option>
            <option value="Keluar">Keluar</option>
          </select>
          <InputError :message="form.errors.type" class="mt-1" />
        </div>

        <!-- Kuantitas -->
        <div>
          <InputLabel for="quantity" value="Kuantitas" />
          <TextInput
            id="quantity"
            type="number"
            class="mt-1 block w-full"
            v-model="form.quantity"
            required
          />
          <InputError :message="form.errors.quantity" class="mt-1" />
        </div>

        <!-- Tanggal -->
        <div>
          <InputLabel for="transaction_date" value="Tanggal Transaksi" />
          <TextInput
            id="transaction_date"
            type="date"
            class="mt-1 block w-full"
            v-model="form.transaction_date"
            required
          />
          <InputError :message="form.errors.transaction_date" class="mt-1" />
        </div>

        <!-- Catatan -->
        <div>
          <InputLabel for="notes" value="Catatan" />
          <textarea
            id="notes"
            v-model="form.notes"
            rows="3"
            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
          ></textarea>
          <InputError :message="form.errors.notes" class="mt-1" />
        </div>

        <!-- Tombol -->
        <div class="flex justify-end pt-4 border-t dark:border-gray-700">
          <SecondaryButton :disabled="form.processing" @click="handleClose">
            Batal
          </SecondaryButton>
          <PrimaryButton
            type="submit"
            class="ml-3"
            :disabled="form.processing"
            :class="{ 'opacity-25': form.processing }"
          >
            {{ form.processing ? 'Memperbarui...' : 'Perbarui Transaksi' }}
          </PrimaryButton>
        </div>
      </form>
    </div>
  </Modal>
</template>
