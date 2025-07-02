{{-- products/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div
        class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16
                 dark:bg-gray-900 dark:text-gray-100">
        <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center
                   dark:text-gray-200">Daftar
            Produk</h1>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div id="flash-success"
                class="max-w-lg mx-auto bg-green-500 text-white p-3 rounded-lg mb-6 flex justify-between items-center shadow-lg transition-opacity opacity-90 hover:opacity-100 backdrop-blur-md mt-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200">✖</button>
            </div>
        @endif
        @if (session('error'))
            <div id="flash-error"
                class="max-w-lg mx-auto bg-red-500 text-white p-3 rounded-lg mb-6 flex justify-between items-center shadow-lg transition-opacity opacity-90 hover:opacity-100 backdrop-blur-md mt-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200">✖</button>
            </div>
        @endif

        {{-- Form Pencarian (keep as is) --}}
        <form method="GET" action="{{ route('products.index') }}" class="mb-6 flex gap-4">
            <input type="text" id="search" name="search" value="{{ request('search') }}"
                placeholder="Cari berdasarkan nama, SKU, atau kategori"
                class="w-full border border-gray-300 rounded-lg py-2 px-4 text-black bg-white bg-opacity-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all
                       dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400">
            <select name="status"
                class="border border-gray-300 rounded-lg py-2 px-4 bg-white text-black focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all
                           dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400">
                <option value="">Semua Status</option>
                <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                <option value="Warning" {{ request('status') == 'Warning' ? 'selected' : '' }}>Warning</option>
                <option value="Habis" {{ request('status') == 'Habis' ? 'selected' : '' }}>Habis</option>
            </select>
            <button type="submit"
                class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-all
                       dark:bg-blue-700 dark:hover:bg-blue-800">
                Cari
            </button>
        </form>

        @if (auth()->user()->role === 'admin')
            <div class="flex flex-wrap gap-4 mb-6">
                <button id="openAddProductModal" type="button"
                    class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition-all
                   dark:bg-green-700 dark:hover:bg-green-800">
                    + Tambah Produk
                </button>

                <a href="{{ route('categories.index') }}"
                    class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-all
                   dark:bg-blue-700 dark:hover:bg-blue-800">
                    📂 Lihat Kategori Produk
                </a>
                <a href="{{ route('product_attributes.index') }}"
                    class="bg-purple-600 text-white py-2 px-4 rounded-lg hover:bg-purple-700 transition-all
                   dark:bg-purple-700 dark:hover:bg-purple-800">
                    🔖 Lihat Atribut Produk
                </a>
                <a href="{{ route('products.import-export.index') }}"
                    class="bg-yellow-600 text-white py-2 px-4 rounded-lg hover:bg-yellow-700 transition-all
                   dark:bg-yellow-700 dark:hover:bg-yellow-800">
                    📥📤 Import/Export Produk
                </a>
            </div>
        @endif

        <div
            class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50
                    dark:bg-gray-800 dark:bg-opacity-70 dark:shadow-xl dark:border-gray-700">
            <table
                class="min-w-full bg-white bg-opacity-50 rounded-lg shadow overflow-hidden border border-gray-300
                          dark:bg-gray-800 dark:bg-opacity-70 dark:border-gray-700">
                <thead
                    class="bg-gray-800 bg-opacity-70 text-white
                              dark:bg-gray-900 dark:bg-opacity-90 dark:text-gray-100">
                    <tr>
                        <th class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">Nama</th>
                        <th class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">Gambar</th>
                        <th class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">SKU</th>
                        <th class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">Kategori</th>
                        <th class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">Supplier</th>
                        <th class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">Harga Beli</th>
                        <th class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">Harga Jual</th>
                        <th class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">Stok</th>
                        <th class="py-3 px-4 border text-center border-gray-300 dark:border-gray-700">Stok Minimum</th>
                        <th class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">Status</th>
                        <th class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 dark:divide-gray-700">
                    @if ($products->isEmpty())
                        <tr>
                            <td colspan="11"
                                class="py-4 text-center text-gray-500 italic
                                               dark:text-gray-400">
                                Tidak ada produk untuk saat ini.
                            </td>
                        </tr>
                    @else
                        @foreach ($products as $product)
                            <tr
                                class="product-row hover:bg-gray-100 bg-white bg-opacity-50 transition-all
                                   dark:bg-gray-800 dark:bg-opacity-50 dark:hover:bg-gray-700">
                                <td
                                    class="py-3 px-4 product-name border border-gray-300 text-center dark:border-gray-700 dark:text-gray-200">
                                    {{ $product->name ?? 'N/A' }}</td>
                                <td class="text-center border border-gray-300 dark:border-gray-700">
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="h-8 w-8 object-cover mx-auto rounded">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" alt=""
                                            class="h-8 w-8 object-cover mx-auto opacity-30">
                                    @endif
                                </td>
                                <td
                                    class="py-3 px-4 text-center product-sku border border-gray-300 dark:border-gray-700 dark:text-gray-200">
                                    {{ $product->sku ?? 'N/A' }}</td>
                                <td
                                    class="py-3 px-4 text-center product-category border border-gray-300 dark:border-gray-700 dark:text-gray-200">
                                    {{ $product->category->name ?? 'N/A' }}</td>
                                <td
                                    class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700 dark:text-gray-200">
                                    {{ $product->supplier->name ?? 'N/A' }}</td>
                                <td class="py-3 px-4 border border-gray-300 dark:border-gray-700 dark:text-gray-200">
                                    {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 border border-gray-300 dark:border-gray-700 dark:text-gray-200">
                                    {{ number_format($product->sale_price, 0, ',', '.') }}</td>
                                <td
                                    class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700 dark:text-gray-200">
                                    {{ $product->stock ?? 0 }}</td>
                                <td
                                    class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700 dark:text-gray-200">
                                    {{ $product->minimum_stock ?? 0 }}</td>
                                <td class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">
                                    @php
                                        $statusMap = [
                                            'Habis' =>
                                                'border-red-500 font-semibold text-red-500 dark:border-red-400 dark:text-red-400',
                                            'Warning' =>
                                                'border-yellow-500 font-semibold text-yellow-500 dark:border-yellow-400 dark:text-yellow-400',
                                            'Tersedia' =>
                                                'border-green-500 font-semibold text-green-500 dark:border-green-400 dark:text-green-400',
                                        ];

                                        if ($product->stock == 0) {
                                            $status = 'Habis';
                                        } elseif ($product->stock < $product->minimum_stock) {
                                            $status = 'Warning';
                                        } else {
                                            $status = 'Tersedia';
                                        }
                                    @endphp

                                    <span class="px-3 py-1 rounded-lg border {{ $statusMap[$status] }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-center border border-gray-300 dark:border-gray-700">
                                    {{-- Aksi sesuai role --}}
                                    @if (auth()->user()->role === 'warehouse_manager')
                                        <button onclick="openDetailModal({{ json_encode($product) }})"
                                            class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600 transition-all
            dark:bg-blue-600 dark:hover:bg-blue-700">Detail</button>
                                    @elseif(auth()->user()->role === 'admin')
                                        <div class="flex space-x-2 justify-center">
                                            <button onclick="openDetailModal({{ json_encode($product) }})"
                                                class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600 focus:outline-none text-sm
                dark:bg-blue-600 dark:hover:bg-blue-700">Detail</button>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 focus:outline-none text-sm
                    dark:bg-red-600 dark:hover:bg-red-700">Hapus</button>
                                            </form>
                                        </div>
                                    @elseif(auth()->user()->role === 'warehouse_staff')
                                        <span class="text-gray-500 text-sm italic dark:text-gray-400">Aksi tidak
                                            tersedia</span>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-center">
            {{ $products->appends(request()->input())->links('vendor.pagination.custom') }}
        </div>
    </div>

    {{-- MODAL STRUCTURE START --}}
    @include('products.create_modal')
    @include('products.detail_modal') {{-- Ini adalah perubahan utama --}}
    {{-- MODAL STRUCTURE END --}}

@endsection

@push('scripts')
    <script>
        // General flash message dismissal
        setTimeout(function() {
            const successMessage = document.getElementById('flash-success');
            const errorMessage = document.getElementById('flash-error');

            if (successMessage) {
                successMessage.remove();
            }
            if (errorMessage) {
                errorMessage.remove();
            }
        }, 5000); // 5000 ms = 5 detik

        // Modal functionality
        document.addEventListener('DOMContentLoaded', function() {
            const openModalBtn = document.getElementById('openAddProductModal');
            const modal = document.getElementById('addProductModal');
            const closeModalBtn = document.getElementById('closeModalButton');
            const form = document.getElementById('addProductForm');
            const modalErrors = document.getElementById('modalErrors');

            // Open modal
            if (openModalBtn) {
                openModalBtn.addEventListener('click', function() {
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden'; // Prevent body scroll when modal is open
                });
            }

            // Close modal function
            function closeModal() {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto'; // Restore body scroll
                form.reset(); // Reset form
                hideErrors(); // Hide any error messages
            }

            // Close modal when clicking close button
            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', closeModal);
            }

            // Close modal when clicking outside
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModal();
                }
            });

            // Function to show errors
            function showErrors(errors) {
                const errorList = modalErrors.querySelector('ul');
                errorList.innerHTML = '';

                Object.values(errors).forEach(errorMessages => {
                    if (Array.isArray(errorMessages)) {
                        errorMessages.forEach(message => {
                            const li = document.createElement('li');
                            li.textContent = message;
                            errorList.appendChild(li);
                        });
                    } else {
                        const li = document.createElement('li');
                        li.textContent = errorMessages;
                        errorList.appendChild(li);
                    }
                });

                modalErrors.classList.remove('hidden');
            }

            // Function to hide errors
            function hideErrors() {
                modalErrors.classList.add('hidden');
            }

            // Handle form submission
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault(); // Prevent default form submission

                    const formData = new FormData(form);
                    const submitBtn = form.querySelector('button[type="submit"]');

                    // Disable submit button and show loading state
                    submitBtn.disabled = true;
                    const originalText = submitBtn.textContent;
                    submitBtn.textContent = 'Menyimpan...';

                    // Hide previous errors
                    hideErrors();

                    // Send AJAX request
                    fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw new Error(JSON.stringify(data));
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Success - show success message and reload page
                            closeModal();

                            // Show success flash message
                            showFlashMessage('success', data.message || 'Produk berhasil ditambahkan!');

                            // Reload page to show new product
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        })
                        .catch(error => {
                            try {
                                const errorData = JSON.parse(error.message);
                                if (errorData.errors) {
                                    showErrors(errorData.errors);
                                } else {
                                    showFlashMessage('error', errorData.message ||
                                        'Terjadi kesalahan saat menyimpan produk.');
                                }
                            } catch (e) {
                                showFlashMessage('error', 'Terjadi kesalahan yang tidak diketahui.');
                            }
                        })
                        .finally(() => {
                            // Re-enable submit button
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                        });
                });
            }

            // Function to show flash messages
            function showFlashMessage(type, message) {
                const container = document.querySelector('.container');
                const isSuccess = type === 'success';

                const flashDiv = document.createElement('div');
                flashDiv.className =
                    `max-w-lg mx-auto ${isSuccess ? 'bg-green-500' : 'bg-red-500'} text-white p-3 rounded-lg mb-6 flex justify-between items-center shadow-lg transition-opacity opacity-90 hover:opacity-100 backdrop-blur-md mt-4`;

                flashDiv.innerHTML = `
                <div class="flex items-center space-x-2">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        ${isSuccess 
                            ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                            : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                        }
                    </svg>
                    <span>${message}</span>
                </div>
                <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200">✖</button>
            `;

                // Insert after the h1 title
                const title = container.querySelector('h1');
                title.insertAdjacentElement('afterend', flashDiv);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (flashDiv.parentNode) {
                        flashDiv.remove();
                    }
                }, 5000);
            }

            // Image validation
            const imageInput = document.getElementById('image_modal');
            const imageError = document.getElementById('image-error-modal');

            if (imageInput) {
                imageInput.addEventListener('change', function() {
                    const file = this.files[0];
                    imageError.classList.add('hidden');

                    if (file) {
                        // Check file size (max 2MB)
                        if (file.size > 2 * 1024 * 1024) {
                            imageError.textContent = 'Ukuran file tidak boleh lebih dari 2MB';
                            imageError.classList.remove('hidden');
                            this.value = '';
                            return;
                        }

                        // Check file type
                        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                        if (!allowedTypes.includes(file.type)) {
                            imageError.textContent = 'Format file harus JPEG, JPG, PNG, atau GIF';
                            imageError.classList.remove('hidden');
                            this.value = '';
                            return;
                        }
                    }
                });
            }
        });

        // Dropdown functionality (keep as is)
        document.addEventListener("DOMContentLoaded", function() {
            function toggleDropdown(event, dropdown) {
                event.stopPropagation();
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (menu !== dropdown) {
                        menu.classList.add('hidden');
                    }
                });
                dropdown.classList.toggle('hidden');
                let rect = dropdown.getBoundingClientRect();
                let windowHeight = window.innerHeight;
                if (rect.bottom > windowHeight) {
                    dropdown.classList.add("top-auto", "bottom-full", "mb-2");
                    dropdown.classList.remove("mt-2");
                } else {
                    dropdown.classList.remove("top-auto", "bottom-full", "mb-2");
                    dropdown.classList.add("mt-2");
                }
            }

            document.querySelectorAll(".btn-dropdown, .dropdown-button").forEach(button => {
                button.addEventListener("click", function(event) {
                    let dropdown = this.nextElementSibling;
                    if (dropdown) {
                        toggleDropdown(event, dropdown);
                    }
                });
            });

            document.addEventListener("click", function(event) {
                document.querySelectorAll('.dropdown-menu').forEach(menu => {
                    if (!menu.contains(event.target)) {
                        menu.classList.add('hidden');
                    }
                });
            });
        });

        // Live search functionality (keep as is)
        document.querySelector('#search').addEventListener('input', function() {
            const searchQuery = this.value.toLowerCase();
            const rows = document.querySelectorAll('.product-row');
            rows.forEach(row => {
                const nameCell = row.querySelector('.product-name');
                const skuCell = row.querySelector('.product-sku');
                const categoryCell = row.querySelector('.product-category');

                const name = nameCell.textContent.toLowerCase();
                const sku = skuCell.textContent.toLowerCase();
                const category = categoryCell.textContent.toLowerCase();

                nameCell.innerHTML = nameCell.textContent;
                skuCell.innerHTML = skuCell.textContent;
                categoryCell.innerHTML = categoryCell.textContent;

                if (name.includes(searchQuery) || sku.includes(searchQuery) || category.includes(
                        searchQuery)) {
                    row.style.display = '';
                    if (name.includes(searchQuery)) {
                        nameCell.innerHTML = name.replace(new RegExp(searchQuery, 'gi'), match =>
                            `<mark class="bg-yellow-300 dark:bg-yellow-600 dark:text-gray-900">${match}</mark>`
                        );
                    }
                    if (sku.includes(searchQuery)) {
                        skuCell.innerHTML = sku.replace(new RegExp(searchQuery, 'gi'), match =>
                            `<mark class="bg-yellow-300 dark:bg-yellow-600 dark:text-gray-900">${match}</mark>`
                        );
                    }
                    if (category.includes(searchQuery)) {
                        categoryCell.innerHTML = category.replace(new RegExp(searchQuery, 'gi'), match =>
                            `<mark class="bg-yellow-300 dark:bg-yellow-600 dark:text-gray-900">${match}</mark>`
                        );
                    }
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
@endpush
