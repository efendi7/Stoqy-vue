<div id="addCategoryModal"
     class="fixed inset-0 bg-gray-900 bg-opacity-75 items-center justify-center z-50 hidden p-4">
    {{-- MODAL CONTENT --}}
    <div class="relative bg-white bg-opacity-90 dark:bg-gray-800 dark:bg-opacity-90 rounded-2xl shadow-xl w-full max-w-sm sm:max-w-md animate-modalAppear flex flex-col max-h-[90vh]">
        
        {{-- HEADER --}}
        <div class="flex items-center justify-between p-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Tambah Kategori Baru</h2>
            <button id="closeCategoryModalButton" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-2xl font-bold">&times;</button>
        </div>

        {{-- SCROLLABLE CONTENT --}}
        <div class="flex-1 overflow-y-auto px-6 py-4 custom-scrollbar">
            
            {{-- Container untuk pesan error dari AJAX --}}
            <div id="modalCategoryErrors" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden dark:bg-red-900 dark:bg-opacity-50 dark:border-red-700 dark:text-red-300">
                <ul class="list-disc pl-5"></ul>
            </div>

            <form id="addCategoryForm" action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                
                {{-- Nama Kategori --}}
                <div>
                    <label for="name_category_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Nama Kategori</label>
                    <input type="text" name="name" id="name_category_modal"
                           class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-400"
                           placeholder="contoh: Makanan Pokok" required>
                </div>

                {{-- Deskripsi Kategori --}}
                <div>
                    <label for="description_category_modal" class="block text-sm font-medium text-gray-800 dark:text-gray-200">Deskripsi</label>
                    <textarea name="description" id="description_category_modal" rows="4"
                              class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100 dark:placeholder-gray-400 dark:focus:ring-blue-400"
                              placeholder="Deskripsi singkat mengenai kategori..."></textarea>
                </div>
            </form>
        </div>

        {{-- STICKY FOOTER BUTTONS --}}
        <div class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-6 rounded-b-2xl">
            <div class="flex gap-3">
                <button type="button" id="cancelCategoryModalButton"
                        class="flex-1 bg-gray-500 text-white py-2 px-4 rounded-lg hover:bg-gray-600 transition-all transform hover:scale-105 dark:bg-gray-600 dark:hover:bg-gray-700">
                    Batal
                </button>
                <button type="submit" form="addCategoryForm"
                        class="flex-1 bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105 dark:bg-blue-600 dark:hover:bg-blue-700">
                    Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- SCRIPT KHUSUS UNTUK MODAL INI --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Pastikan script ini hanya berjalan jika elemen modal ada di halaman
    const modal = document.getElementById('addCategoryModal');
    if (!modal) return;

    const openModalBtn = document.getElementById('openAddCategoryModal');
    const cancelButton = document.getElementById('cancelCategoryModalButton');
    const closeButton = document.getElementById('closeCategoryModalButton');
    const form = document.getElementById('addCategoryForm');
    const submitButton = document.querySelector('button[type="submit"][form="addCategoryForm"]');

    // Fungsi untuk menampilkan error validasi
    function showErrors(errors) {
        const errorDiv = document.getElementById('modalCategoryErrors');
        const errorList = errorDiv.querySelector('ul');
        errorDiv.classList.remove('hidden');
        errorList.innerHTML = '';
        Object.values(errors).forEach(messages => {
            if (Array.isArray(messages)) {
                messages.forEach(message => {
                    const li = document.createElement('li');
                    li.textContent = message;
                    errorList.appendChild(li);
                });
            }
        });
    }

    // Fungsi untuk membersihkan error
    function clearErrors() {
        const errorDiv = document.getElementById('modalCategoryErrors');
        if (errorDiv) {
            errorDiv.classList.add('hidden');
            const errorList = errorDiv.querySelector('ul');
            if (errorList) {
                errorList.innerHTML = '';
            }
        }
    }

    // Fungsi untuk menutup modal
    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
        form.reset();
        clearErrors();
        if (submitButton) {
            submitButton.disabled = false;
            submitButton.textContent = 'Simpan';
        }
    }

    // Event listener untuk membuka modal
    if (openModalBtn) {
        openModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
            clearErrors();
        });
    }

    // Event listeners untuk menutup modal
    if (cancelButton) cancelButton.addEventListener('click', closeModal);
    if (closeButton) closeButton.addEventListener('click', closeModal);
    modal.addEventListener('click', (e) => (e.target === modal) && closeModal());
    document.addEventListener('keydown', (e) => (e.key === 'Escape' && !modal.classList.contains('hidden')) && closeModal());

    // Event listener untuk submit form via AJAX
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        clearErrors();

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Menyimpan...';
        }

        const formData = new FormData(form);
        const csrfToken = document.querySelector('input[name="_token"]').value;

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => Promise.reject(errorData));
            }
            return response.json();
        })
        .then(data => {
            closeModal();
            // Tampilkan notifikasi sukses dan reload halaman
            alert(data.message || 'Kategori berhasil ditambahkan!');
            window.location.reload();
        })
        .catch(error => {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = 'Simpan';
            }
            if (error.errors) {
                showErrors(error.errors);
            } else {
                alert(error.message || 'Terjadi kesalahan tidak dikenal.');
            }
        });
    });
});
</script>