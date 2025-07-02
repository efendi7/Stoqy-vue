{{-- resources/views/products/_create_product_modal.blade.php --}}

<div id="addProductModal"
     class="fixed inset-0 bg-gray-900 bg-opacity-75 items-center justify-center z-50 hidden p-4">
    {{-- MODAL CONTENT --}}
    <div class="relative bg-white bg-opacity-90 dark:bg-gray-800 dark:bg-opacity-90 rounded-2xl shadow-xl w-full max-w-sm sm:max-w-md animate-modalAppear flex flex-col max-h-[90vh]">
        {{-- HEADER --}}
        <div class="flex items-center justify-between p-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Tambah Produk Baru</h2>
            <button id="closeModalButton" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-2xl font-bold">&times;</button>
        </div>

        {{-- SCROLLABLE CONTENT --}}
        <div class="flex-1 overflow-y-auto px-6 py-4 custom-scrollbar">
            <div id="modalErrors" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden dark:bg-red-900 dark:bg-opacity-50 dark:border-red-700 dark:text-red-300">
                <ul class="list-disc pl-5"></ul>
            </div>

            <form id="addProductForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="name_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Nama Produk</label>
                    <input type="text" name="name" id="name_modal"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-400"
                        value="{{ old('name') }}" required>
                </div>

                <div>
                    <label for="sku_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">SKU</label>
                    <input type="text" name="sku" id="sku_modal"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-400"
                        value="{{ old('sku') }}" required>
                </div>

                <div>
                    <label for="category_id_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Kategori</label>
                    <select name="category_id" id="category_id_modal"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        required>
                        <option value="">Pilih Kategori</option>
                        {{-- Options will be loaded via AJAX --}}
                    </select>
                </div>

                <div>
                    <label for="supplier_id_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Supplier</label>
                    <select name="supplier_id" id="supplier_id_modal"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400">
                        <option value="">Pilih Supplier</option>
                        {{-- Options will be loaded via AJAX --}}
                    </select>
                </div>

                <div>
                    <label for="purchase_price_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Harga Beli</label>
                    <input type="number" name="purchase_price" id="purchase_price_modal"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        value="{{ old('purchase_price') }}" step="100" min="0" required>
                </div>

                <div>
                    <label for="sale_price_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Harga Jual</label>
                    <input type="number" name="sale_price" id="sale_price_modal"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        value="{{ old('sale_price') }}" step="100" min="0" required>
                </div>

                <div>
                    <label for="initial_stock_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Inisial Stok</label>
                    <input type="number" name="initial_stock" id="initial_stock_modal"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        value="0" min="0" readonly required>
                </div>

                <div>
                    <label for="stock_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Stok realtime</label>
                    <input type="number" name="stock" id="stock_modal"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        value="{{ old('stock') }}" min="0" required>
                </div>

                <div>
                    <label for="minimum_stock_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Minimum Stok</label>
                    <input type="number" name="minimum_stock" id="minimum_stock_modal"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:focus:ring-blue-400"
                        value="{{ old('minimum_stock', 5) }}" min="0" required>
                </div>

                <div>
                    <label for="image_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Gambar Produk</label>
                    <input type="file" name="image" id="image_modal" class="w-full mt-1 p-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:text-gray-100">
                    <p id="image-error-modal" class="text-red-500 text-sm mt-1 hidden"></p>
                </div>

                {{-- Spacer untuk memberikan ruang agar konten tidak tertutup tombol --}}
                <div class="h-16"></div>
            </form>
        </div>

        {{-- STICKY FOOTER BUTTONS --}}
        <div class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-6 rounded-b-2xl">
            <div class="flex gap-3">
                <button type="button" id="cancelModalButton"
                    class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition-all transform hover:scale-105 dark:bg-gray-600 dark:hover:bg-gray-700">
                    Batal
                </button>
                <button type="submit" form="addProductForm"
                    class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openModalBtn = document.getElementById('openAddProductModal');
    const modal = document.getElementById('addProductModal');
    const cancelButton = document.getElementById('cancelModalButton');
    const closeButton = document.getElementById('closeModalButton');
    const form = document.getElementById('addProductForm');
    const submitButton = document.querySelector('button[type="submit"][form="addProductForm"]');
    const categorySelect = document.getElementById('category_id_modal'); // Get category select
    const supplierSelect = document.getElementById('supplier_id_modal'); // Get supplier select

    // Debug logging
    console.log('Modal initialization:', {
        modal: !!modal,
        form: !!form,
        submitButton: !!submitButton,
        openModalBtn: !!openModalBtn
    });

    if (!modal || !form) {
        console.error('Required modal elements not found');
        return;
    }

    // Function to show error messages
    function showErrors(errors) {
        const errorDiv = document.getElementById('modalErrors');
        const errorList = errorDiv ? errorDiv.querySelector('ul') : null;

        if (errorDiv && errorList) {
            errorDiv.classList.remove('hidden');
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
        }
    }

    // Function to clear errors
    function clearErrors() {
        const errorDiv = document.getElementById('modalErrors');
        if (errorDiv) {
            errorDiv.classList.add('hidden');
            const errorList = errorDiv.querySelector('ul');
            if (errorList) {
                errorList.innerHTML = '';
            }
        }
    }

    // Function to close modal
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';

        // Reset form
        form.reset();
        clearErrors();

        // Re-enable submit button
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Simpan';
        }
    }

    // Function to load categories and suppliers
    async function loadDropdowns() {
        try {
            const [categoriesResponse, suppliersResponse] = await Promise.all([
                fetch('{{ route('api.categories') }}'),
                fetch('{{ route('api.suppliers') }}')
            ]);

            // Check if responses are OK before parsing JSON
            if (!categoriesResponse.ok) throw new Error(`HTTP error! status: ${categoriesResponse.status} for categories`);
            if (!suppliersResponse.ok) throw new Error(`HTTP error! status: ${suppliersResponse.status} for suppliers`);

            const categories = await categoriesResponse.json();
            const suppliers = await suppliersResponse.json();

            // Populate categories dropdown
            categorySelect.innerHTML = '<option value="">Pilih Kategori</option>';
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });

            // Populate suppliers dropdown
            supplierSelect.innerHTML = '<option value="">Pilih Supplier</option>';
            suppliers.forEach(supplier => {
                const option = document.createElement('option');
                option.value = supplier.id;
                option.textContent = supplier.name;
                supplierSelect.appendChild(option);
            });

        } catch (error) {
            console.error('Failed to load categories or suppliers:', error);
            showErrors(`Gagal memuat daftar kategori atau supplier: ${error.message || 'Error jaringan'}`);
        }
    }

    // Event listener for opening modal
    if (openModalBtn) {
        openModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            clearErrors();
            loadDropdowns(); // Load dropdowns when opening the modal
        });
    }

    // Event listeners for closing modal
    if (cancelButton) {
        cancelButton.addEventListener('click', closeModal);
    }

    if (closeButton) {
        closeButton.addEventListener('click', closeModal);
    }

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // Form submission handling
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        console.log('Form submission started');
        clearErrors();

        // Disable submit button
        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';
        }

        // Create FormData object
        // FormData automatically handles 'enctype="multipart/form-data"'
        const formData = new FormData(form);

        // Debug: Log form data (for text fields only, files won't show content)
        console.log('Form data to send:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }

        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                           document.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            console.error('CSRF token not found');
            showErrors('CSRF token tidak ditemukan. Mohon refresh halaman.');
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan';
            }
            return;
        }

        fetch(form.action, {
            method: 'POST',
            body: formData, // FormData handles the Content-Type header for multipart/form-data
            headers: {
                'X-Requested-With': 'XMLHttpRequest', // Important for Laravel to recognize AJAX
                'Accept': 'application/json', // Tell server we prefer JSON response
                'X-CSRF-TOKEN': csrfToken // Pass CSRF token
            }
        })
        .then(response => {
            console.log('Raw response:', response);
            // Check for non-OK response first
            if (!response.ok) {
                // If it's a validation error (422), or other server error (500), parse JSON to get error details
                return response.json().then(errorData => {
                    throw {
                        status: response.status,
                        message: errorData.message || 'Terjadi kesalahan.',
                        errors: errorData.errors || {}, // Validation errors
                        data: errorData
                    };
                }).catch(err => {
                    // Handle cases where response is not valid JSON (e.g., 500 error page HTML)
                    console.error("Failed to parse error response as JSON:", err);
                    throw {
                        status: response.status,
                        message: `Terjadi kesalahan pada server (${response.status}). Cek konsol untuk detail.`,
                        errors: {}
                    };
                });
            }
            // If response is OK, parse JSON
            return response.json();
        })
        .then(data => {
            console.log('Success response data:', data);

            // Close modal
            closeModal();

            // Show success message
            // You might have a global function for this, or implement it here
            const alertDiv = document.createElement('div');
            alertDiv.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
            alertDiv.textContent = data.message || 'Produk berhasil ditambahkan!';
            document.body.appendChild(alertDiv);

            setTimeout(() => {
                if (alertDiv.parentNode) {
                    alertDiv.parentNode.removeChild(alertDiv);
                }
            }, 3000);

            // Reload page or update table dynamically
            setTimeout(() => {
                window.location.reload(); // Simple reload for now
            }, 1500);
        })
        .catch(error => {
            console.error('AJAX submission error:', error);

            // Re-enable submit button
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan';
            }

            // Show error messages
            if (error.errors && Object.keys(error.errors).length > 0) {
                showErrors(error.errors); // Laravel validation errors
            } else if (error.message) {
                showErrors(error.message); // General error message
            } else {
                showErrors('Terjadi kesalahan tidak dikenal saat menyimpan data.');
            }
        });
    });

    // Image file validation
    const imageInput = document.getElementById('image_modal');
    const imageError = document.getElementById('image-error-modal');

    if (imageInput && imageError) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            imageError.classList.add('hidden');

            if (file) {
                // Check file size (max 2MB)
                if (file.size > 2 * 1024 * 1024) {
                    imageError.textContent = 'Ukuran file maksimal 2MB';
                    imageError.classList.remove('hidden');
                    this.value = ''; // Clear the input
                    return;
                }

                // Check file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    imageError.textContent = 'Format file harus JPG, JPEG, PNG, atau GIF';
                    imageError.classList.remove('hidden');
                    this.value = ''; // Clear the input
                    return;
                }

                console.log('Image validation passed:', {
                    name: file.name,
                    size: file.size,
                    type: file.type
                });
            }
        });
    }

    // Set initial_stock to match stock_modal on input change
    const stockModalInput = document.getElementById('stock_modal');
    const initialStockModalInput = document.getElementById('initial_stock_modal');

    if (stockModalInput && initialStockModalInput) {
        stockModalInput.addEventListener('input', function() {
            initialStockModalInput.value = this.value;
        });
    }
});
</script>