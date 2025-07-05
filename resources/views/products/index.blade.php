{{-- products/index.blade.php --}}

@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100 dark:from-gray-950 dark:to-gray-900">
        {{-- Header Section --}}
        <div class="border-b border-gray-200/60 dark:border-gray-800/60 bg-white/80 dark:bg-gray-900/80 backdrop-blur-sm">
            <div class="container mx-auto px-6 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Products</h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage your product inventory</p>
                    </div>

                    @if (auth()->user()->role === 'admin')
                        <div class="flex items-center space-x-3">
                            <a href="{{ route('products.import-export.index') }}"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10">
                                    </path>
                                </svg>
                                Import/Export
                            </a>
                            <button id="openAddProductModal" type="button"
                                class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all dark:bg-blue-700 dark:hover:bg-blue-800">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Product
                            </button>
                        </div>
                    @endif
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

            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/20 dark:border-red-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
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

            {{-- Search and Filter (Versi Ringkas) --}}
            <div class="mb-6">
                <form method="GET" action="{{ route('products.index') }}"
                    class="flex flex-col md:flex-row items-center gap-3 w-full">

                    {{-- Kolom Pencarian --}}
                    <div class="relative w-full md:flex-grow">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search" name="search" value="{{ request('search') }}"
                            placeholder="Cari produk berdasarkan nama, SKU..." aria-label="Search products"
                            class="block w-full h-11 pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    </div>

                    {{-- Filter Status --}}
                    <select name="status" id="status" aria-label="Status Filter"
                        class="block w-full md:w-auto h-11 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="Tersedia" {{ request('status') == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Warning" {{ request('status') == 'Warning' ? 'selected' : '' }}>Stok Rendah</option>
                        <option value="Habis" {{ request('status') == 'Habis' ? 'selected' : '' }}>Habis</option>
                    </select>

                    {{-- Tombol Cari --}}
                    <button type="submit"
                        class="inline-flex items-center justify-center w-full md:w-auto px-5 h-11 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-lg hover:bg-blue-700 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all">
                        Cari
                    </button>
                </form>
            </div>
            {{-- Products Table --}}
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Product</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    SKU</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Category</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Supplier</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Price</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Stock</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @if ($products->isEmpty())
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                                                </path>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">No products found</p>
                                        </div>
                                    </td>
                                </tr>
                            @else
                                @foreach ($products as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 flex-shrink-0">
                                                    @if ($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}"
                                                            alt="{{ $product->name }}"
                                                            class="h-10 w-10 rounded-lg object-cover">
                                                    @else
                                                        <div
                                                            class="h-10 w-10 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                            <svg class="h-5 w-5 text-gray-400" fill="none"
                                                                stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                                </path>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                        {{ $product->name ?? 'N/A' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $product->sku ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $product->category->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                {{ $product->supplier->name ?? 'N/A' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Buy:
                                                    {{ number_format($product->purchase_price, 0, ',', '.') }}</div>
                                                <div class="font-medium">Sell:
                                                    {{ number_format($product->sale_price, 0, ',', '.') }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-white">
                                                <div class="font-medium">{{ $product->stock ?? 0 }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">Min:
                                                    {{ $product->minimum_stock ?? 0 }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                if ($product->stock == 0) {
                                                    $status = 'Out of Stock';
                                                    $statusClass =
                                                        'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400';
                                                } elseif ($product->stock < $product->minimum_stock) {
                                                    $status = 'Low Stock';
                                                    $statusClass =
                                                        'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400';
                                                } else {
                                                    $status = 'Available';
                                                    $statusClass =
                                                        'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400';
                                                }
                                            @endphp
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                                {{ $status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            @if (auth()->user()->role === 'warehouse_manager')
                                                <button onclick="openDetailModal({{ json_encode($product) }})"
                                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                    View Details
                                                </button>
                                            @elseif(auth()->user()->role === 'admin')
                                                <div class="flex items-center space-x-3">
                                                    <button onclick="openDetailModal({{ json_encode($product) }})"
                                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                                        View
                                                    </button>
                                                    <form action="{{ route('products.destroy', $product->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure you want to delete this product?')"
                                                        class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            @elseif(auth()->user()->role === 'warehouse_staff')
                                                <span class="text-gray-400 text-sm">No actions available</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>


        </div>
    </div>
    {{-- Wrapper Sticky BARU yang Menggabungkan Pagination dan Footer --}}
    <div class="sticky bottom-0 z-10">
        {{-- Bagian Pagination --}}
        @if ($products->hasPages())
            <div class="bg-gray-900/80 backdrop-blur-sm border-t border-gray-700 py-3">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{ $products->appends(request()->input())->links('vendor.pagination.custom') }}
                </div>
            </div>
        @endif

        {{-- Bagian Footer --}}
        @include('partials.footer')
    </div>
    {{-- Modals --}}
    @include('products.create_modal')
    @include('products.detail_modal')



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
