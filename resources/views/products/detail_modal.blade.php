{{-- resources/views/products/detail_modal.blade.php --}}

<div id="detailProductModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 items-center justify-center z-50 hidden p-4">
    {{-- MODAL CONTENT --}}
    <div
        class="relative bg-white bg-opacity-90 dark:bg-gray-800 dark:bg-opacity-90 rounded-2xl shadow-xl w-full max-w-sm sm:max-w-md animate-modalAppear flex flex-col max-h-[90vh]">
        {{-- HEADER --}}
        <div class="flex items-center justify-between p-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Detail Produk</h2>
            <button id="closeDetailModalButton"
                class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-2xl font-bold">&times;</button>
        </div>

        {{-- SCROLLABLE CONTENT --}}
        <div class="flex-1 overflow-y-auto px-6 py-4 custom-scrollbar">
            <div id="detailModalErrors"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden dark:bg-red-900 dark:bg-opacity-50 dark:border-red-700 dark:text-red-300">
                <ul class="list-disc pl-5"></ul>
            </div>

            <form id="detailProductForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                {{-- Method spoofing for PUT request --}}
                @method('PUT')

                <div>
                    <label for="name_detail" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Nama
                        Produk</label>
                    <input type="text" name="name" id="name_detail"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-400"
                        readonly required>
                </div>

                <div>
                    <label for="sku_detail"
                        class="block text-sm font-medium text-gray-800 dark:text-gray-200">SKU</label>
                    <input type="text" name="sku" id="sku_detail"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-400"
                        readonly required>
                </div>

                <div>
                    <label for="category_id_detail"
                        class="block text-sm font-medium text-gray-800 dark:text-gray-200">Kategori</label>
                    <select name="category_id" id="category_id_detail"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        disabled required>
                        <option value="">Pilih Kategori</option>
                        {{-- Options will be loaded via AJAX --}}
                    </select>
                </div>

                <div>
                    <label for="supplier_id_detail"
                        class="block text-sm font-medium text-gray-800 dark:text-gray-200">Supplier</label>
                    <select name="supplier_id" id="supplier_id_detail"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        disabled>
                        <option value="">Pilih Supplier</option>
                        {{-- Options will be loaded via AJAX --}}
                    </select>
                </div>

                <div>
                    <label for="purchase_price_detail"
                        class="block text-sm font-medium text-gray-800 dark:text-gray-200">Harga Beli</label>
                    <input type="number" name="purchase_price" id="purchase_price_detail"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        step="100" min="0" readonly required>
                </div>

                <div>
                    <label for="sale_price_detail"
                        class="block text-sm font-medium text-gray-800 dark:text-gray-200">Harga Jual</label>
                    <input type="number" name="sale_price" id="sale_price_detail"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        step="100" min="0" readonly required>
                </div>

                <div>
                    <label for="initial_stock_detail"
                        class="block text-sm font-medium text-gray-800 dark:text-gray-200">Inisial Stok</label>
                    <input type="number" name="initial_stock" id="initial_stock_detail"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        min="0" readonly required>
                </div>

                <div>
                    <label for="stock_detail" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Stok
                        Realtime</label>
                    <input type="number" name="stock" id="stock_detail"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        min="0" readonly required>
                </div>

                <div>
                    <label for="minimum_stock_detail"
                        class="block text-sm font-medium text-gray-800 dark:text-gray-200">Minimum Stok</label>
                    <input type="number" name="minimum_stock" id="minimum_stock_detail"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        min="0" readonly required>
                </div>

                <div>
                    <label for="image_detail" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Gambar
                        Produk</label>
                    <div id="current_image_detail" class="mt-2 mb-2"></div>
                    <input type="file" name="image" id="image_detail"
                        class="w-full mt-1 p-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:text-gray-100 hidden">
                    <p id="image-error-detail" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                {{-- Spacer untuk memberikan ruang agar konten tidak tertutup tombol --}}
                <div class="h-16"></div>
            </form>
        </div>

        {{-- STICKY FOOTER BUTTONS --}}
        <div
            class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-6 rounded-b-2xl">
            <div class="flex justify-center space-x-4">
                @if (auth()->user()->role === 'admin')
                    <button type="button" id="editDetailButton"
                        class="bg-yellow-500 text-white py-2 px-6 rounded-lg hover:bg-yellow-600 transition-all transform hover:scale-105 dark:bg-yellow-600 dark:hover:bg-yellow-700">
                        Edit
                    </button>
                @endif
                <button type="button" id="closeDetailButton"
                    class="bg-gray-500 text-white py-2 px-6 rounded-lg hover:bg-gray-600 transition-all transform hover:scale-105 dark:bg-gray-600 dark:hover:bg-gray-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const detailModal = document.getElementById('detailProductModal');
        const detailForm = document.getElementById('detailProductForm');
        const editButton = document.getElementById('editDetailButton');
        const closeDetailButton = document.getElementById('closeDetailButton');
        const closeDetailModalButton = document.getElementById('closeDetailModalButton');

        // Semua input dan select dalam form
        const formInputs = detailForm.querySelectorAll('input:not([type="file"]), select');
        const imageInput = document.getElementById('image_detail');
        const categorySelectDetail = document.getElementById('category_id_detail'); // Get category select
        const supplierSelectDetail = document.getElementById('supplier_id_detail'); // Get supplier select

        let isEditMode = false;
        let originalData = {};
        let currentProductId = null;

        // Fungsi untuk menampilkan pesan error
        function showErrors(errors) {
            const errorDiv = document.getElementById('detailModalErrors');
            const errorList = errorDiv.querySelector('ul');
            errorList.innerHTML = '';

            if (typeof errors === 'object' && errors !== null) {
                // If Laravel validation errors (object with arrays of messages)
                if (!Array.isArray(errors) && !errors.hasOwnProperty('message') && Object.keys(errors).length > 0) {
                     Object.values(errors).forEach(messages => {
                        if (Array.isArray(messages)) {
                            messages.forEach(message => {
                                const li = document.createElement('li');
                                li.textContent = message;
                                errorList.appendChild(li);
                            });
                        }
                    });
                } else if (errors.message) { // Generic error message (e.g., from catch block)
                    const li = document.createElement('li');
                    li.textContent = errors.message;
                    errorList.appendChild(li);
                } else { // Fallback for unexpected object structure
                    const li = document.createElement('li');
                    li.textContent = 'Terjadi kesalahan tidak dikenal.';
                    errorList.appendChild(li);
                }
            } else if (typeof errors === 'string') { // Single string error message
                const li = document.createElement('li');
                li.textContent = errors;
                errorList.appendChild(li);
            }

            errorDiv.classList.remove('hidden');
        }

        // Fungsi untuk menyembunyikan pesan error
        function hideErrors() {
            const errorDiv = document.getElementById('detailModalErrors');
            errorDiv.classList.add('hidden');
        }

        // Fungsi untuk memuat kategori dan supplier untuk modal detail
        async function loadDetailDropdowns(selectedCategoryId, selectedSupplierId) {
            try {
                const [categoriesResponse, suppliersResponse] = await Promise.all([
                    fetch('{{ route('api.categories') }}'),
                    fetch('{{ route('api.suppliers') }}')
                ]);

                if (!categoriesResponse.ok) throw new Error(`HTTP error! status: ${categoriesResponse.status} for categories`);
                if (!suppliersResponse.ok) throw new Error(`HTTP error! status: ${suppliersResponse.status} for suppliers`);

                const categories = await categoriesResponse.json();
                const suppliers = await suppliersResponse.json();

                // Populate categories dropdown
                categorySelectDetail.innerHTML = '<option value="">Pilih Kategori</option>';
                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    if (category.id == selectedCategoryId) { // Check for equality using == or ===
                        option.selected = true;
                    }
                    categorySelectDetail.appendChild(option);
                });

                // Populate suppliers dropdown
                supplierSelectDetail.innerHTML = '<option value="">Pilih Supplier</option>';
                suppliers.forEach(supplier => {
                    const option = document.createElement('option');
                    option.value = supplier.id;
                    option.textContent = supplier.name;
                    if (supplier.id == selectedSupplierId) { // Check for equality using == or ===
                        option.selected = true;
                    }
                    supplierSelectDetail.appendChild(option);
                });

            } catch (error) {
                console.error('Failed to load categories or suppliers for detail modal:', error);
                showErrors(`Gagal memuat daftar kategori atau supplier: ${error.message || 'Error jaringan'}`);
            }
        }

        // Fungsi untuk toggle mode edit
        function toggleEditMode() {
            if (!editButton) return;

            isEditMode = !isEditMode;
            hideErrors(); // Clear errors when toggling mode

            if (isEditMode) {
                // Masuk mode edit
                // Simpan data asli sebelum diubah
                originalData = {};
                formInputs.forEach(input => {
                    // For selects, store the currently selected value before enabling
                    if (input.tagName === 'SELECT') {
                         originalData[input.name] = input.value;
                    } else {
                        originalData[input.name] = input.value;
                    }
                });

                // Enable semua input
                formInputs.forEach(input => {
                    input.removeAttribute('readonly');
                    input.removeAttribute('disabled');
                });

                // Show file input
                imageInput.classList.remove('hidden');

                // Load dropdowns when entering edit mode, preserving current selections
                const currentCategoryId = categorySelectDetail.value;
                const currentSupplierId = supplierSelectDetail.value;
                loadDetailDropdowns(currentCategoryId, currentSupplierId);

                // Update tombol
                editButton.textContent = 'Batal';
                editButton.className =
                    'bg-red-500 text-white py-2 px-6 rounded-lg hover:bg-red-600 transition-all transform hover:scale-105 dark:bg-red-600 dark:hover:bg-red-700';

                closeDetailButton.textContent = 'Update';
                closeDetailButton.className =
                    'bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105 dark:bg-blue-600 dark:hover:bg-blue-700';

                // Add event listener for stock to update initial stock in edit mode
                const stockDetailInput = document.getElementById('stock_detail');
                const initialStockDetailInput = document.getElementById('initial_stock_detail');
                if (stockDetailInput && initialStockDetailInput) {
                    stockDetailInput.addEventListener('input', function() {
                        // Only update if not already explicitly set (e.g., from originalData)
                        // Or simply always sync it in edit mode
                        initialStockDetailInput.value = this.value;
                    });
                }


            } else {
                // Keluar mode edit (batal)
                // Restore data asli
                formInputs.forEach(input => {
                    if (originalData[input.name] !== undefined) {
                        input.value = originalData[input.name];
                    }
                });

                // Reset file input
                imageInput.value = '';
                // Hide current image error if any
                document.getElementById('image-error-detail').classList.add('hidden');

                exitEditMode();
            }
        }

        // Fungsi untuk keluar dari mode edit
        function exitEditMode() {
            isEditMode = false;

            // Disable semua input
            formInputs.forEach(input => {
                if (input.tagName === 'SELECT') {
                    input.setAttribute('disabled', 'disabled');
                } else {
                    input.setAttribute('readonly', 'readonly');
                }
            });

            // Hide file input
            imageInput.classList.add('hidden');

            // Reset tombol
            if (editButton) {
                editButton.textContent = 'Edit';
                editButton.className =
                    'bg-yellow-500 text-white py-2 px-6 rounded-lg hover:bg-yellow-600 transition-all transform hover:scale-105 dark:bg-yellow-600 dark:hover:bg-yellow-700';
            }

            closeDetailButton.textContent = 'Tutup';
            closeDetailButton.className =
                'bg-gray-500 text-white py-2 px-6 rounded-lg hover:bg-gray-600 transition-all transform hover:scale-105 dark:bg-gray-600 dark:hover:bg-gray-700';

            // Remove event listener for stock if it was added in edit mode to prevent duplicates
            const stockDetailInput = document.getElementById('stock_detail');
            const initialStockDetailInput = document.getElementById('initial_stock_detail');
            if (stockDetailInput && initialStockDetailInput) {
                stockDetailInput.removeEventListener('input', function() {
                    initialStockDetailInput.value = this.value;
                });
            }
        }

        // Fungsi untuk menutup modal
        function closeDetailModal() {
            detailModal.classList.add('hidden');
            document.body.style.overflow = 'auto'; // Restore body scroll
            exitEditMode(); // Always exit edit mode on close
            detailForm.reset();
            // Reset error messages
            hideErrors();

            // Clear image preview
            document.getElementById('current_image_detail').innerHTML = '';
        }

        // Fungsi untuk submit form dengan AJAX
       function submitForm() {
            console.log('Current Product ID for update:', currentProductId);
            if (!currentProductId) {
                console.error('Error: Product ID is missing for update.');
                showErrors('Gagal memperbarui produk: ID produk tidak ditemukan.');
                const submitBtn = closeDetailButton;
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update';
                return;
            }

            const submitBtn = closeDetailButton;

            // Disable submit button and show loading state
            submitBtn.disabled = true;
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Memperbarui...';

            // Hide previous errors
            hideErrors();

            const formData = new FormData(detailForm);

            // Laravel's @method('PUT') relies on a hidden input.
            // FormData will automatically include this, so no need to add '_method' manually.
            // If you were not using @method('PUT'), you would add it like this:
            // formData.append('_method', 'PUT');

            // Debug: Log form data
            console.log('Form data for update:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }

            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                               document.querySelector('input[name="_token"]')?.value;

            if (!csrfToken) {
                console.error('CSRF token not found');
                showErrors('CSRF token tidak ditemukan. Mohon refresh halaman.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                }
                return;
            }

            // Send AJAX request
            fetch(`/products/${currentProductId}`, {
                method: 'POST', // Always POST with method spoofing
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => {
                console.log('Update Response status:', response.status);
                // Check for non-OK response first
                if (!response.ok) {
                    return response.json().then(errorData => {
                        throw {
                            status: response.status,
                            message: errorData.message || 'Terjadi kesalahan.',
                            errors: errorData.errors || {},
                            data: errorData
                        };
                    }).catch(err => {
                        console.error("Failed to parse error response as JSON:", err);
                        throw {
                            status: response.status,
                            message: `Terjadi kesalahan pada server (${response.status}). Cek konsol untuk detail.`,
                            errors: {}
                        };
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Update Success response:', data);

                closeDetailModal(); // Close modal immediately

                // Show success message (using your existing showFlashMessage or creating a new one)
                // You may want to define showFlashMessage globally or pass it.
                // For now, let's include a local version if it's not global.
                // Assuming showFlashMessage exists globally or is defined below this script.
                if (typeof showFlashMessage === 'function') {
                    showFlashMessage('success', data.message || 'Produk berhasil diperbarui!');
                } else {
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
                    alertDiv.innerHTML = `
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>${data.message || 'Produk berhasil diperbarui!'}</span>
                        </div>
                    `;
                    document.body.appendChild(alertDiv);
                    setTimeout(() => {
                        if (alertDiv.parentNode) {
                            alertDiv.parentNode.removeChild(alertDiv);
                        }
                    }, 3000);
                }

                // Reload page after short delay
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Update Form submission error:', error);

                submitBtn.disabled = false;
                submitBtn.textContent = originalText;

                if (error.errors && Object.keys(error.errors).length > 0) {
                    showErrors(error.errors);
                } else if (error.message) {
                    showErrors(error.message);
                } else {
                    showErrors('Terjadi kesalahan tidak dikenal saat memperbarui produk.');
                }
            });
        }


        // Event listeners
        if (editButton) {
            editButton.addEventListener('click', toggleEditMode);
        }

        closeDetailButton.addEventListener('click', function() {
            if (isEditMode) {
                // Submit form if in edit mode
                submitForm();
            } else {
                // Close modal if in view mode
                closeDetailModal();
            }
        });

        closeDetailModalButton.addEventListener('click', closeDetailModal);

        // Tutup modal ketika klik di luar modal
        detailModal.addEventListener('click', function(e) {
            if (e.target === detailModal) {
                closeDetailModal();
            }
        });

        // Tutup modal dengan tombol ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !detailModal.classList.contains('hidden')) {
                closeDetailModal();
            }
        });

        // Image validation (remains the same)
        imageInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            const errorMessage = document.getElementById('image-error-detail');
            errorMessage.classList.add('hidden');

            if (file) {
                const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (!allowedTypes.includes(file.type)) {
                    errorMessage.textContent = "Format file tidak didukung! (Hanya JPG, PNG, GIF)";
                    errorMessage.classList.remove('hidden');
                    event.target.value = '';
                } else if (file.size > maxSize) {
                    errorMessage.textContent = "Ukuran file terlalu besar! Maksimal 2MB.";
                    errorMessage.classList.remove('hidden');
                    event.target.value = '';
                }
            }
        });

        // Fungsi global untuk membuka modal dengan data produk
        window.openDetailModal = function(product) {
            currentProductId = product.id;

            // Fill form data
            document.getElementById('name_detail').value = product.name || '';
            document.getElementById('sku_detail').value = product.sku || '';
            document.getElementById('purchase_price_detail').value = product.purchase_price || '';
            document.getElementById('sale_price_detail').value = product.sale_price || '';
            document.getElementById('initial_stock_detail').value = product.initial_stock || 0;
            document.getElementById('stock_detail').value = product.stock || '';
            document.getElementById('minimum_stock_detail').value = product.minimum_stock || '';

            // Display current image if exists
            const imageContainer = document.getElementById('current_image_detail');
            if (product.image) {
                imageContainer.innerHTML =
                    `<img src="/storage/${product.image}" alt="Product Image" class="w-32 h-32 object-cover rounded-lg">`;
            } else {
                imageContainer.innerHTML = '<p class="text-gray-500 text-sm">Tidak ada gambar</p>';
            }

            // Load dropdowns first, then set the selected value
            // This ensures all options are available if editing.
            loadDetailDropdowns(product.category_id, product.supplier_id)
                .then(() => {
                    // After dropdowns are populated, set the selected value
                    // This is crucial if categories/suppliers were not initially loaded
                    categorySelectDetail.value = product.category_id || '';
                    supplierSelectDetail.value = product.supplier_id || '';
                })
                .catch(error => {
                    console.error("Error setting dropdown values after loading:", error);
                    // Handle error if dropdowns couldn't be loaded
                });

            // Reset to view mode initially
            exitEditMode();

            // Show modal
            detailModal.classList.remove('hidden');
            detailModal.classList.add('flex');
            document.body.style.overflow = 'hidden'; // Prevent body scroll when modal is open
        };
    });

    // Make showFlashMessage accessible globally if it's not already
    // This is a common pattern if you want to reuse this function across different scripts
    if (typeof window.showFlashMessage === 'undefined') {
        window.showFlashMessage = function(type, message) {
            const body = document.body; // Or a more specific container if you have one
            const isSuccess = type === 'success';

            const flashDiv = document.createElement('div');
            flashDiv.className = `fixed top-4 right-4 ${isSuccess ? 'bg-green-500' : 'bg-red-500'} text-white px-6 py-3 rounded-lg shadow-lg z-50 flex items-center space-x-2 animate-slideIn`;

            flashDiv.innerHTML = `
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    ${isSuccess
                        ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>'
                        : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>'
                    }
                </svg>
                <span>${message}</span>
                <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200 ml-auto">&times;</button>
            `;

            body.appendChild(flashDiv);

            setTimeout(() => {
                flashDiv.classList.remove('animate-slideIn');
                flashDiv.classList.add('animate-fadeOut'); // Add fade out animation
                flashDiv.addEventListener('animationend', () => flashDiv.remove()); // Remove after animation
            }, 3000);
        };
    }
</script>