<script setup>
import { useForm } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'

const props = defineProps({
  show: Boolean,
   products: Object, // <-- BENAR. Sekarang bisa menerima objek paginasi
    users: Object,    // <-- BENAR.
})

const emit = defineEmits(['close'])

const form = useForm({
  product_id: '',
  user_id: '',
  type: 'Masuk',
  quantity: '',
  transaction_date: '',
  notes: '',
})

const submit = () => {
  form.post(route('stock_transactions.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset()
      emit('close')
    },
  })
}

const handleClose = () => {
  form.reset()
  emit('close')
}
</script>

<template>
  <Modal :show="show" @close="handleClose" max-width="2xl"> <div class="p-6 bg-white dark:bg-gray-800 rounded-lg">
      <h2 class="text-xl font-bold text-gray-900 dark:text-white text-center mb-6 pt-2">
        Tambah Transaksi Stok
      </h2>

      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

          <div class="space-y-5">
            <div>
              <InputLabel for="product_id" value="Produk" />
              <select
                id="product_id"
                v-model="form.product_id"
                class="w-full mt-1 p-2 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
                required
              >
                <option disabled value="">-- Pilih Produk --</option>
    <option v-if="products && products.data" v-for="product in products.data" :key="product.id" :value="product.id">
        {{ product.name }}
    </option>
              </select>
              <InputError class="mt-1" :message="form.errors.product_id" />
            </div>

            <div>
              <InputLabel for="user_id" value="User" />
              <select
                id="user_id"
                v-model="form.user_id"
                class="w-full mt-1 p-2 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
                required
              >
                <option disabled value="">-- Pilih User --</option>
    <option v-if="users && users.data" v-for="user in users.data" :key="user.id" :value="user.id">
        {{ user.name }}
    </option>
              </select>
              <InputError class="mt-1" :message="form.errors.user_id" />
            </div>

            <div>
              <InputLabel for="type" value="Jenis" />
              <select
                id="type"
                v-model="form.type"
                class="w-full mt-1 p-2 rounded-md border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
              >
                <option value="Masuk">Masuk</option>
                <option value="Keluar">Keluar</option>
              </select>
              <InputError class="mt-1" :message="form.errors.type" />
            </div>
          </div>

          <div class="space-y-5">
            <div>
              <InputLabel for="quantity" value="Kuantitas" />
              <TextInput
                id="quantity"
                type="number"
                v-model="form.quantity"
                required
                class="mt-1 block w-full"
              />
              <InputError class="mt-1" :message="form.errors.quantity" />
            </div>

            <div>
              <InputLabel for="transaction_date" value="Tanggal Transaksi" />
              <TextInput
                id="transaction_date"
                type="date"
                v-model="form.transaction_date"
                required
                class="mt-1 block w-full"
              />
              <InputError class="mt-1" :message="form.errors.transaction_date" />
            </div>

            <div>
              <InputLabel for="notes" value="Catatan" />
              <textarea
                id="notes"
                v-model="form.notes"
                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 rounded-md shadow-sm"
                rows="3"
              ></textarea>
              <InputError class="mt-1" :message="form.errors.notes" />
            </div>
          </div>
        </div>

        <div class="flex justify-end border-t pt-4 mt-6 dark:border-gray-700">
          <SecondaryButton @click="handleClose" :disabled="form.processing"> Batal </SecondaryButton>
          <PrimaryButton
            type="submit"
            class="ml-3"
            :disabled="form.processing"
            :class="{ 'opacity-25': form.processing }"
          >
            {{ form.processing ? 'Menyimpan...' : 'Simpan Transaksi' }}
          </PrimaryButton>
        </div>
      </form>
    </div>
  </Modal>
</template>