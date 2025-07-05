@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 dark:from-gray-950 dark:to-gray-900">
        {{-- Header Section --}}
        <div class="border-b border-gray-200/60 dark:border-gray-800/60 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm">
            <div class="container mx-auto px-6 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Kategori</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola kategori produk Anda</p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button id="openAddCategoryModal" type="button"
                            class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:bg-blue-700 dark:hover:bg-blue-800">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Tambah Kategori
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mx-auto px-6 py-8">
            {{-- Flash Messages --}}
            @if (session('success'))
                <div
                    class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 dark:bg-green-900/20 dark:border-green-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        <button onclick="this.parentElement.parentElement.remove()"
                            class="ml-auto text-green-400 hover:text-green-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/20 dark:border-red-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div class="text-sm text-red-800 dark:text-red-200">
                            <strong class="font-bold">Terjadi Kesalahan!</strong>
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        <button onclick="this.parentElement.parentElement.remove()"
                            class="ml-auto text-red-400 hover:text-red-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            @endif

            {{-- Search Form --}}
            <div class="mb-6">
                <form method="GET" action="{{ route('categories.index') }}" class="flex items-center w-full">
                    <div class="relative w-full">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari kategori berdasarkan nama..." aria-label="Cari kategori"
                            class="block w-full h-11 pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>
                    <button type="submit"
                        class="ml-3 inline-flex items-center justify-center w-auto px-5 h-11 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                        Cari
                    </button>
                </form>
            </div>

            {{-- Categories Table --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Nama</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Deskripsi</th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($categories as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $category->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ $category->description ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-4">
                                            {{-- Tombol View untuk membuka modal detail --}}
                                            <button onclick="openDetailCategoryModal({{ json_encode($category) }})"
                                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                                                View
                                            </button>

                                            {{-- Form Hapus --}}
                                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus kategori `{{ $category->name }}`?')"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h2zm0 0l-2.5 2.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada kategori
                                                ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Sticky Wrapper for Pagination and Footer --}}
    <div class="sticky bottom-0 z-10">
        {{-- Pagination Section --}}
        @if ($categories->hasPages())
            <div class="bg-gray-900/80 backdrop-blur-sm border-t border-gray-700 py-3">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{ $categories->appends(request()->input())->links('vendor.pagination.custom') }}
                </div>
            </div>
        @endif

        {{-- Footer Section --}}
        @include('partials.footer')
    </div>

    {{-- Add Category Modal --}}
    @include('categories.create_modal')
    @include('categories.detail_modal')

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // === Inisialisasi Variabel Modal ===
            const modal = document.getElementById('addCategoryModal');
            const openModalBtn = document.getElementById('openAddCategoryModal');
            const closeButton = document.getElementById('closeCategoryModalButton');
            const cancelButton = document.getElementById('cancelCategoryModalButton');
            const form = document.getElementById('addCategoryForm');
            const submitButton = form.querySelector('button[type="submit"]');

            // === Fungsi untuk Menampilkan dan Menyembunyikan Modal ===
            function openModal() {
                modal.classList.remove('hidden');
                modal.classList.add('flex'); // PENTING: Untuk mengaktifkan flexbox centering
                document.body.style.overflow = 'hidden';
            }

            function closeModal() {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
                form.reset(); // Reset form saat modal ditutup
                clearErrors(); // Hapus pesan error
            }

            // === Fungsi untuk Menangani Pesan Error ===
            function showErrors(errors) {
                const errorDiv = document.getElementById('modalCategoryErrors');
                const errorList = errorDiv.querySelector('ul');
                errorList.innerHTML = ''; // Kosongkan list error sebelumnya

                Object.values(errors).forEach(messages => {
                    messages.forEach(message => {
                        const li = document.createElement('li');
                        li.textContent = message;
                        errorList.appendChild(li);
                    });
                });
                errorDiv.classList.remove('hidden');
            }

            function clearErrors() {
                const errorDiv = document.getElementById('modalCategoryErrors');
                errorDiv.classList.add('hidden');
            }


            // === Event Listeners (Pemicu Aksi) ===
            if (openModalBtn) {
                openModalBtn.addEventListener('click', openModal);
            }
            if (closeButton) {
                closeButton.addEventListener('click', closeModal);
            }
            if (cancelButton) {
                cancelButton.addEventListener('click', closeModal);
            }
            // Menutup modal jika klik di luar area konten
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
            // Menutup modal dengan tombol 'Escape'
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            // === Penanganan Form Submission dengan AJAX ===
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(form);
                    const originalButtonText = submitButton.textContent;

                    // Nonaktifkan tombol dan hapus error lama
                    submitButton.disabled = true;
                    submitButton.textContent = 'Menyimpan...';
                    clearErrors();

                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json().then(data => ({
                            status: response.status,
                            body: data
                        })))
                        .then(({
                            status,
                            body
                        }) => {
                            if (status === 201 || status === 200) { // Berhasil
                                closeModal();
                                window.location.reload(); // Reload halaman untuk menampilkan data baru
                            } else if (status === 422) { // Error validasi
                                showErrors(body.errors);
                            } else { // Error lainnya
                                alert(body.message || 'Terjadi kesalahan pada server.');
                            }
                        })
                        .catch(error => {
                            console.error('Submit error:', error);
                            alert('Tidak dapat terhubung ke server. Silakan coba lagi.');
                        })
                        .finally(() => {
                            // Aktifkan kembali tombol
                            submitButton.disabled = false;
                            submitButton.textContent = originalButtonText;
                        });
                });
            }
        });
    </script>
@endpush
