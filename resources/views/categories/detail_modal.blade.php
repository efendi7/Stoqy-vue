{{-- MODAL DETAIL KATEGORI --}}
<div id="detailCategoryModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 items-center justify-center z-50 hidden p-4">
    {{-- KONTEN MODAL --}}
    <div
        class="relative bg-white bg-opacity-90 dark:bg-gray-800 dark:bg-opacity-90 rounded-2xl shadow-xl w-full max-w-sm sm:max-w-md animate-modalAppear flex flex-col max-h-[90vh]">

        {{-- HEADER --}}
        <div class="flex items-center justify-between p-6 pb-4 border-b border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Detail Kategori</h2>
            <button id="closeDetailCategoryModalButton"
                class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-2xl font-bold">&times;</button>
        </div>

        {{-- KONTEN SCROLLABLE --}}
        <div class="flex-1 overflow-y-auto px-6 py-4 custom-scrollbar">
            <div id="detailCategoryModalErrors"
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 hidden dark:bg-red-900 dark:bg-opacity-50 dark:border-red-700 dark:text-red-300">
                <ul class="list-disc pl-5"></ul>
            </div>

            <form id="detailCategoryForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Nama Kategori --}}
                <div>
                    <label for="name_category_detail"
                        class="block text-sm font-medium text-gray-800 dark:text-gray-200">Nama Kategori</label>
                    <input type="text" name="name" id="name_category_detail"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                        readonly required>
                </div>

                {{-- Deskripsi Kategori --}}
                <div>
                    <label for="description_category_detail"
                        class="block text-sm font-medium text-gray-800 dark:text-gray-200">Deskripsi</label>
                    <textarea name="description" id="description_category_detail" rows="4"
                        class="w-full mt-1 p-2 rounded-lg bg-opacity-50 border border-gray-300 text-gray-800 placeholder-gray-400 focus:ring-2 focus:ring-blue-500 transition-all dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100"
                        readonly></textarea>
                </div>

            </form>
        </div>

        {{-- TOMBOL FOOTER STICKY --}}
        <div
            class="sticky bottom-0 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 p-6 rounded-b-2xl">
            <div class="flex justify-center space-x-4">
                {{-- Tombol Edit hanya muncul untuk admin --}}
                @if (auth()->user()->role === 'admin')
                    <button type="button" id="editCategoryButton"
                        class="bg-yellow-500 text-white py-2 px-6 rounded-lg hover:bg-yellow-600 transition-all transform hover:scale-105 dark:bg-yellow-600 dark:hover:bg-yellow-700">
                        Edit
                    </button>
                @endif
                <button type="button" id="closeCategoryDetailButton"
                    class="bg-gray-500 text-white py-2 px-6 rounded-lg hover:bg-gray-600 transition-all transform hover:scale-105 dark:bg-gray-600 dark:hover:bg-gray-700">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        const detailModal = document.getElementById('detailCategoryModal');
        const detailForm = document.getElementById('detailCategoryForm');
        const editButton = document.getElementById('editCategoryButton');
        const closeDetailButton = document.getElementById('closeCategoryDetailButton'); // Tombol "Tutup"/"Update" di footer
        const closeDetailModalButton = document.getElementById('closeDetailCategoryModalButton'); // Tombol 'x' di header

        const detailInputs = detailForm.querySelectorAll('input, textarea');
        let isEditMode = false;
        let currentCategoryId = null;

        // Fungsi untuk toggle mode edit
        function toggleEditMode() {
            if (!editButton) return;

            isEditMode = !isEditMode;
            const errorDiv = document.getElementById('detailCategoryModalErrors');
            errorDiv.classList.add('hidden'); // Sembunyikan error saat ganti mode

            if (isEditMode) {
                // Masuk mode edit
                detailInputs.forEach(input => input.removeAttribute('readonly'));

                editButton.textContent = 'Batal';
                editButton.className =
                    'bg-red-500 text-white py-2 px-6 rounded-lg hover:bg-red-600 transition-all transform hover:scale-105';

                closeDetailButton.textContent = 'Update';
                closeDetailButton.className =
                    'bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105';
            } else {
                // Keluar dari mode edit (membatalkan)
                exitEditMode();
            }
        }

        // Fungsi untuk reset ke mode view
        function exitEditMode() {
            isEditMode = false;
            detailInputs.forEach(input => input.setAttribute('readonly', 'readonly'));

            if (editButton) {
                editButton.textContent = 'Edit';
                editButton.className =
                    'bg-yellow-500 text-white py-2 px-6 rounded-lg hover:bg-yellow-600 transition-all transform hover:scale-105';
            }
            closeDetailButton.textContent = 'Tutup';
            closeDetailButton.className =
                'bg-gray-500 text-white py-2 px-6 rounded-lg hover:bg-gray-600 transition-all transform hover:scale-105';
        }

        // Fungsi untuk menutup modal detail
        function closeDetailModal() {
            detailModal.classList.add('hidden');
            detailModal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            exitEditMode();
        }

        // Event listeners untuk tombol-tombol modal detail
        if (editButton) editButton.addEventListener('click', toggleEditMode);
        if (closeDetailButton) {
            closeDetailButton.addEventListener('click', function() {
                if (isEditMode) {
                    // Jika dalam mode edit, tombol ini berfungsi sebagai "Update"
                    submitUpdateForm();
                } else {
                    // Jika dalam mode view, tombol ini berfungsi sebagai "Tutup"
                    closeDetailModal();
                }
            });
        }
        if (closeDetailModalButton) closeDetailModalButton.addEventListener('click', closeDetailModal);
        detailModal.addEventListener('click', e => e.target === detailModal && closeDetailModal());


        // Fungsi untuk submit update form
        function submitUpdateForm() {
            const formData = new FormData(detailForm);
            const submitBtn = closeDetailButton; // Tombol 'Update'
            const originalText = submitBtn.textContent;
            const errorDiv = document.getElementById('detailCategoryModalErrors');

            submitBtn.disabled = true;
            submitBtn.textContent = 'Memperbarui...';
            errorDiv.classList.add('hidden');

            fetch(`/categories/${currentCategoryId}`, {
                    method: 'POST', // Method spoofing with POST
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
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
                    if (status === 200) {
                        window.location.reload();
                    } else if (status === 422) {
                        const errorList = errorDiv.querySelector('ul');
                        errorList.innerHTML = '';
                        Object.values(body.errors).forEach(messages => {
                            messages.forEach(message => {
                                const li = document.createElement('li');
                                li.textContent = message;
                                errorList.appendChild(li);
                            });
                        });
                        errorDiv.classList.remove('hidden');
                    } else {
                        alert(body.message || 'Terjadi kesalahan.');
                    }
                })
                .catch(err => alert('Gagal terhubung ke server.'))
                .finally(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalText;
                });
        }


        // Fungsi GLOBAL untuk membuka modal detail dari tombol 'View'
        window.openDetailCategoryModal = function(category) {
            currentCategoryId = category.id;

            // Isi form dengan data dari kategori
            document.getElementById('name_category_detail').value = category.name || '';
            document.getElementById('description_category_detail').value = category.description || '';

            // Pastikan selalu dalam mode view saat pertama kali dibuka
            exitEditMode();

            // Tampilkan modal
            detailModal.classList.remove('hidden');
            detailModal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
    </script>
@endpush
