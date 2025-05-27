@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="header-blue-bg p-3 mb-4 rounded">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h2 class="text-warning mb-0">Tambah Produk Baru beserta Kategorinya</h2>
                <button type="button" id="resetAllProductsBtn" class="btn btn-warning text-dark">
                    <i class="fas fa-redo-alt"></i> Reset Semua Produk
                </button>
            </div>
            <p class="text-white mb-0">Formulir ini memungkinkan Anda menambahkan hingga 5 produk. Setiap produk dapat
                memiliki hingga 3
                kategori, dan setiap kategori dapat memiliki satu gambar (JPG, JPEG, PNG). Syarat (Max) untuk Submit jumlah
                ukuran gambar 2MB</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="dynamicProductForm" method="POST" action="{{ route('products.store.dynamic') }}"
            enctype="multipart/form-data">
            @csrf

            <div id="product-list-container">
                @if(old('products'))
                    @foreach(old('products') as $productKey => $oldProduct)
                        <div class="product-entry mb-4 border p-3 rounded shadow-sm" data-product-index="{{ $loop->index }}">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h4 class="product-title mb-0 text-primary">
                                    <strong>Produk <span class="product-number">{{ $loop->iteration }}</span></strong>
                                </h4>
                                <button type="button" class="btn btn-danger btn-sm delete-product-btn">✕ Hapus Produk Ini</button>
                            </div>
                            <div class="form-group">
                                <label>Nama Produk:</label>
                                <input type="text" name="products[{{ $loop->index }}][name]" class="form-control product-name"
                                    value="{{ $oldProduct['name'] ?? '' }}" required placeholder="Masukkan nama produk">
                            </div>

                            <h5 class="mt-3">Kategori untuk Produk Ini:</h5>
                            <div class="categories-container ml-3">
                                @if(isset($oldProduct['categories']))
                                    @foreach($oldProduct['categories'] as $categoryKey => $oldCategory)
                                        <div class="category-entry mb-3 border p-3 rounded bg-light"
                                            data-category-index="{{ $loop->index }}">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="category-title mb-0">Kategori <span
                                                        class="category-number-display">{{ $loop->iteration }}</span></h6>
                                                <button type="button" class="btn btn-danger btn-xs delete-category-btn">✕ Hapus
                                                    Kategori</button>
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Kategori:</label>
                                                <input type="text" name="products[{{ $productKey }}][categories][{{ $loop->index }}][name]"
                                                    class="form-control category-name" value="{{ $oldCategory['name'] ?? '' }}" required
                                                    placeholder="Masukkan nama kategori">
                                            </div>
                                            <div class="form-group">
                                                <label>Gambar Kategori (Format: JPG, JPEG, PNG):</label>
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <span class="input-group-text"><i class="fas fa-image"></i></span>
                                                    </div>
                                                    <div class="custom-file">
                                                        <input type="file"
                                                            name="products[{{ $productKey }}][categories][{{ $loop->index }}][image]"
                                                            class="custom-file-input category-image-upload" accept=".jpg,.jpeg,.png">
                                                        <label class="custom-file-label">Pilih gambar...</label>
                                                    </div>
                                                </div>

                                                <img class="image-preview mt-2 rounded clickable-image" src="#" alt="Preview Gambar"
                                                    style="max-width: 100px; max-height: 100px; display: none; border: 1px solid #ddd;">
                                                <button type="button" class="btn btn-sm delete-image-btn mt-1"
                                                    style="display:none; background-color: #ffc107; border-color: #ffc107; color: #212529;">
                                                    <i class="fas fa-trash-alt"></i> Hapus Gambar
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" class="btn btn-secondary btn-sm add-category-btn mt-2 ml-3">+ Tambah
                                Kategori</button>
                            <div class="category-notification alert alert-info mt-1 ml-3" style="display: none;">
                                Anda Sudah Mencapai Maksimum Input Kategori (3 Kategori) untuk produk ini.
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <hr>
            <div class="d-flex justify-content-start gap-2 mb-3">
                <button type="button" id="addProductBtn" class="btn btn-primary col-md-3">
                    + Tambah Produk
                </button>

                <div id="productNotification" class="alert alert-info mt-2" style="display: none;">
                    Anda Sudah Mencapai Maksimum Input Produk (5 Produk).
                </div>

                <button type="submit" class="btn btn-success col-md-3">
                    <i class="fas fa-save"></i> Simpan Semua Produk
                </button>
            </div>
        </form>

        <div id="summaryTableContainer" class="table-responsive">
            <table class="table table-bordered table-striped mt-3">
                <thead class="thead-dark">
                    <tr>
                        <th colspan="4" id="recapTableTitle" class="table-title-default">Rekap Produk yang Akan Disimpan
                        </th>
                    </tr>
                    <tr>
                        <th>No.</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Gambar Kategori</th>
                    </tr>
                </thead>
                <tbody id="productSummaryBody">
                    <tr>
                        <td colspan="4" class="text-center text-muted">Belum ada produk ditambahkan.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <template id="product-entry-template">
            <div class="product-entry mb-4 border p-3 rounded shadow-sm" data-product-index="">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h4 class="product-title mb-0 text-primary">
                        <strong>Produk <span class="product-number"></span></strong>
                    </h4>
                    <button type="button" class="btn btn-danger btn-sm delete-product-btn">✕ Hapus Produk Ini</button>
                </div>
                <div class="form-group">
                    <label>Nama Produk:</label>
                    <input type="text" name="products[PRODUCT_INDEX_PLACEHOLDER][name]" class="form-control product-name"
                        required placeholder="Masukkan nama produk">
                </div>

                <h5 class="mt-3">Kategori untuk Produk Ini:</h5>
                <div class="categories-container ml-3">
                </div>
                <button type="button" class="btn btn-secondary btn-sm add-category-btn mt-2 ml-3">+ Tambah Kategori</button>
                <div class="category-notification alert alert-info mt-1 ml-3" style="display: none;">
                    Anda Sudah Mencapai Maksimum Input Kategori (3 Kategori) untuk produk ini.
                </div>
            </div>
        </template>

        <template id="category-entry-template">
            <div class="category-entry mb-3 border p-3 rounded bg-light" data-category-index="">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h6 class="category-title mb-0">Kategori <span class="category-number-display"></span></h6>
                    <button type="button" class="btn btn-danger btn-xs delete-category-btn">✕ Hapus Kategori</button>
                </div>
                <div class="form-group">
                    <label>Nama Kategori:</label>
                    <input type="text"
                        name="products[PRODUCT_INDEX_PLACEHOLDER][categories][CATEGORY_INDEX_PLACEHOLDER][name]"
                        class="form-control category-name" required placeholder="Masukkan nama kategori">
                </div>
                <div class="form-group">
                    <label>Gambar Kategori (Format: JPG, JPEG, PNG):</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                        </div>
                        <div class="custom-file">
                            <input type="file"
                                name="products[PRODUCT_INDEX_PLACEHOLDER][categories][CATEGORY_INDEX_PLACEHOLDER][image]"
                                class="custom-file-input category-image-upload" accept=".jpg,.jpeg,.png">
                            <label class="custom-file-label">Pilih gambar...</label>
                        </div>
                    </div>
                    <img class="image-preview mt-2 rounded clickable-image" src="#" alt="Preview Gambar"
                        style="max-width: 100px; max-height: 100px; display: none; border: 1px solid #ddd;">
                    <button type="button" class="btn btn-sm delete-image-btn mt-1"
                        style="display:none; background-color: #ffc107; border-color: #ffc107; color: #212529;">
                        <i class="fas fa-trash-alt"></i> Hapus Gambar
                    </button>
                </div>
            </div>
        </template>

        <div class="modal fade" tabindex="-1" role="dialog" id="deleteConfirmationModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Penghapusan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p id="deleteConfirmationMessage"></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" id="cancelDeleteBtn" data-dismiss="modal"
                            style="background-color: #808080; color: white;">Batalkan</button>
                        <button type="button" class="btn" id="confirmDeleteBtn"
                            style="background-color: #D22B2B; color: white;">Hapus</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="imageModalLabel">Pratinjau Gambar</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="" id="fullSizeImage" class="img-fluid" alt="Gambar Besar">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" role="dialog" id="resetAllConfirmationModal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Reset Formulir</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Apakah Anda yakin ingin memulai semuanya dari awal tambah produk?</strong></p>
                        <p>Semua tampilan dan inputan produk yang sudah Anda masukkan saat ini akan hilang.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" data-dismiss="modal"
                            style="background-color: #808080; color: white;">Batalkan</button>
                        <button type="button" class="btn btn-danger" id="confirmResetAllBtn">Ya, Saya Yakin</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .header-blue-bg {
            background-color: #007bff;
            color: white;
        }

        .product-entry {
            background-color: #f8f9fae0;
        }

        .category-entry {
            background-color: #e9ecefcc;
        }

        .btn-xs {
            padding: .2rem .4rem;
            font-size: .875rem;
            line-height: 1;
            border-radius: .2rem;
        }

        .custom-file-label::after {
            content: "Cari";
        }

        .delete-image-btn i {
            margin-right: 5px;
        }

        #summaryTableContainer {
            margin-top: 2rem;
        }

        #productSummaryBody img {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }

        .clickable-image {
            cursor: pointer;
        }

        #productSummaryBody td {
            vertical-align: middle;
            text-align: center;
        }

        #summaryTableContainer thead th {
            text-align: center;
            vertical-align: middle;
        }

        .table-title-default {
            background-color: #6c757d;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 1rem;
            font-size: 1.25rem;
            border-bottom: 2px solid #5a6268;
        }

        .table-title-active {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 1rem;
            font-size: 1.25rem;
            border-bottom: 2px solid #1e7e34;
        }

        .gap-2 {
            gap: 0.5rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const productListContainer = document.getElementById('product-list-container');
            const addProductBtn = document.getElementById('addProductBtn'); // ID tombol Tambah Produk
            const resetAllProductsBtn = document.getElementById('resetAllProductsBtn');
            const productNotification = document.getElementById('productNotification');
            const productEntryTemplate = document.getElementById('product-entry-template');
            const categoryEntryTemplate = document.getElementById('category-entry-template');
            const productSummaryBody = document.getElementById('productSummaryBody');
            const recapTableTitle = document.getElementById('recapTableTitle'); // Ambil elemen judul tabel

            const deleteConfirmationModal = $('#deleteConfirmationModal');
            const deleteConfirmationMessage = document.getElementById('deleteConfirmationMessage');
            const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');

            const imageModal = $('#imageModal'); // Modal gambar
            const fullSizeImage = document.getElementById('fullSizeImage'); // Elemen gambar di modal

            const resetAllConfirmationModal = $('#resetAllConfirmationModal'); // Modal reset semua
            const confirmResetAllBtn = document.getElementById('confirmResetAllBtn'); // Tombol konfirmasi reset semua


            let itemToDelete = null;
            let deleteActionCallback = null;

            const MAX_PRODUCTS = 5;
            const MAX_CATEGORIES_PER_PRODUCT = 3;

            function updateAllIndicesAndLimits() {
                const productEntries = productListContainer.querySelectorAll('.product-entry');
                productEntries.forEach((entry, productIdx) => {
                    entry.querySelector('.product-number').textContent = productIdx + 1;
                    entry.dataset.productIndex = productIdx;

                    // Update product name input name
                    const productNameInput = entry.querySelector('.product-name');
                    if (productNameInput) {
                        productNameInput.name = `products[${productIdx}][name]`;
                    }

                    // Update categories within this product
                    const categoriesContainer = entry.querySelector('.categories-container');
                    const categoryEntries = categoriesContainer.querySelectorAll('.category-entry');
                    categoryEntries.forEach((catEntry, catIdx) => {
                        catEntry.dataset.categoryIndex = catIdx;
                        catEntry.querySelector('.category-number-display').textContent = catIdx + 1;

                        const catNameInput = catEntry.querySelector('.category-name');
                        if (catNameInput) {
                            catNameInput.name = `products[${productIdx}][categories][${catIdx}][name]`;
                        }
                        const catImageInput = catEntry.querySelector('.category-image-upload');
                        if (catImageInput) {
                            catImageInput.name = `products[${productIdx}][categories][${catIdx}][image]`;
                        }
                    });
                    checkCategoryLimit(entry);
                });
                checkProductLimit();
                updateSummaryTable(); // Perbarui tabel rekap setelah setiap perubahan struktur form
            }


            function checkProductLimit() {
                const productCount = productListContainer.querySelectorAll('.product-entry').length;
                if (productCount >= MAX_PRODUCTS) {
                    addProductBtn.style.display = 'none'; // Sembunyikan tombol jika sudah maksimum
                    productNotification.style.display = 'block';
                } else {
                    addProductBtn.style.display = 'inline-block'; // Tampilkan kembali tombol
                    productNotification.style.display = 'none';
                }
            }

            function checkCategoryLimit(productEntry) {
                const categoriesContainer = productEntry.querySelector('.categories-container');
                const categoryCount = categoriesContainer.querySelectorAll('.category-entry').length;
                const addCategoryBtn = productEntry.querySelector('.add-category-btn');
                const categoryNotification = productEntry.querySelector('.category-notification');

                if (categoryCount >= MAX_CATEGORIES_PER_PRODUCT) {
                    addCategoryBtn.style.display = 'none';
                    categoryNotification.style.display = 'block';
                } else {
                    addCategoryBtn.style.display = 'inline-block';
                    categoryNotification.style.display = 'none';
                }
            }

            function updateSummaryTable() {
                productSummaryBody.innerHTML = ''; // Kosongkan tabel rekap

                const productEntries = productListContainer.querySelectorAll('.product-entry');

                if (productEntries.length === 0) {
                    productSummaryBody.innerHTML = `
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Belum ada produk ditambahkan.</td>
                                        </tr>
                                    `;
                    // Jika tidak ada produk, ubah warna judul tabel ke default (abu-abu)
                    recapTableTitle.classList.remove('table-title-active');
                    recapTableTitle.classList.add('table-title-default');
                    return;
                }

                // Jika ada produk, ubah warna judul tabel ke aktif (hijau)
                recapTableTitle.classList.remove('table-title-default');
                recapTableTitle.classList.add('table-title-active');

                productEntries.forEach((productEntry, productIdx) => {
                    const productName = productEntry.querySelector('.product-name').value || '(Nama Produk Belum Diisi)';
                    const categoriesContainer = productEntry.querySelector('.categories-container');
                    const categoryEntries = categoriesContainer.querySelectorAll('.category-entry');

                    if (categoryEntries.length === 0) {
                        const row = productSummaryBody.insertRow();
                        row.innerHTML = `
                                            <td>${productIdx + 1}</td>
                                            <td><strong>${productName}</strong></td>
                                            <td colspan="2" class="text-muted">Belum ada kategori ditambahkan.</td>
                                        `;
                    } else {
                        categoryEntries.forEach((categoryEntry, categoryIdx) => {
                            const categoryName = categoryEntry.querySelector('.category-name').value || '(Nama Kategori Belum Diisi)';
                            const imagePreview = categoryEntry.querySelector('.image-preview');
                            const imagePreviewSrc = imagePreview.src;
                            const imageDisplay = imagePreviewSrc && imagePreview.style.display !== 'none' ?
                                `<img src="${imagePreviewSrc}" alt="Gambar Kategori" class="clickable-image">` : 'Tidak Ada Gambar';

                            const row = productSummaryBody.insertRow();
                            if (categoryIdx === 0) {
                                row.innerHTML = `
                                                    <td rowspan="${categoryEntries.length}">${productIdx + 1}</td>
                                                    <td rowspan="${categoryEntries.length}"><strong>${productName}</strong></td>
                                                    <td>${categoryName}</td>
                                                    <td>${imageDisplay}</td>
                                                `;
                            } else {
                                row.innerHTML = `
                                                    <td>${categoryName}</td>
                                                    <td>${imageDisplay}</td>
                                                `;
                            }
                        });
                    }
                });
            }


            // Menggunakan ID baru untuk tombol "Tambah Produk"
            addProductBtn.addEventListener('click', function () {
                if (productListContainer.querySelectorAll('.product-entry').length < MAX_PRODUCTS) {
                    const newProductFragment = productEntryTemplate.content.cloneNode(true);
                    productListContainer.appendChild(newProductFragment);
                    updateAllIndicesAndLimits();
                }
            });

            productListContainer.addEventListener('click', function (e) {
                // Delete Product
                if (e.target.classList.contains('delete-product-btn')) {
                    itemToDelete = e.target.closest('.product-entry');
                    deleteConfirmationMessage.textContent = "Apakah Anda Yakin untuk Menghapus Produk Ini beserta semua kategorinya?";
                    deleteActionCallback = () => {
                        itemToDelete.remove();
                        updateAllIndicesAndLimits();
                    };
                    deleteConfirmationModal.modal('show');
                }

                // Add Category
                if (e.target.classList.contains('add-category-btn')) {
                    const productEntry = e.target.closest('.product-entry');
                    const categoriesContainer = productEntry.querySelector('.categories-container');

                    if (categoriesContainer.querySelectorAll('.category-entry').length < MAX_CATEGORIES_PER_PRODUCT) {
                        const newCategoryFragment = categoryEntryTemplate.content.cloneNode(true);
                        categoriesContainer.appendChild(newCategoryFragment);
                        updateAllIndicesAndLimits();
                    }
                }

                // Delete Category
                if (e.target.classList.contains('delete-category-btn')) {
                    itemToDelete = e.target.closest('.category-entry');
                    deleteConfirmationMessage.textContent = "Apakah Anda Yakin untuk Menghapus Kategori Ini?";
                    deleteActionCallback = () => {
                        itemToDelete.remove();
                        updateAllIndicesAndLimits();
                    };
                    deleteConfirmationModal.modal('show');
                }

                // Delete Image
                if (e.target.classList.contains('delete-image-btn') || e.target.closest('.delete-image-btn')) {
                    const actualButton = e.target.classList.contains('delete-image-btn') ? e.target : e.target.closest('.delete-image-btn');
                    itemToDelete = actualButton;
                    deleteConfirmationMessage.textContent = "Apakah Anda Yakin untuk Menghapus Gambar?";
                    deleteActionCallback = () => {
                        const categoryEntry = itemToDelete.closest('.category-entry');
                        const fileInput = categoryEntry.querySelector('.category-image-upload');
                        const imagePreview = categoryEntry.querySelector('.image-preview');
                        const fileInputLabel = categoryEntry.querySelector('.custom-file-label');

                        fileInput.value = ''; // Reset file input
                        if (fileInputLabel) fileInputLabel.textContent = 'Pilih gambar...';
                        imagePreview.style.display = 'none';
                        imagePreview.src = '#';
                        itemToDelete.style.display = 'none'; // Hide delete image button
                        updateSummaryTable();
                    };
                    deleteConfirmationModal.modal('show');
                }

                if (e.target.classList.contains('clickable-image') && e.target.src !== window.location.href + '#') {
                    fullSizeImage.src = e.target.src;
                    imageModal.modal('show');
                }
            });

            // Konfirmasi Penghapusan Umum (untuk produk, kategori, gambar)
            confirmDeleteBtn.addEventListener('click', function () {
                if (deleteActionCallback) {
                    deleteActionCallback();
                }
                deleteConfirmationModal.modal('hide');
                itemToDelete = null;
                deleteActionCallback = null;
            });

            // Tombol Reset Semua Produk
            resetAllProductsBtn.addEventListener('click', function () {
                resetAllConfirmationModal.modal('show');
            });

            // Konfirmasi Reset Semua Produk
            confirmResetAllBtn.addEventListener('click', function () {
                productListContainer.innerHTML = ''; // Hapus semua entri produk
                updateAllIndicesAndLimits(); // Perbarui indeks dan tabel rekap
                resetAllConfirmationModal.modal('hide');
            });

            productListContainer.addEventListener('input', function (e) {
                if (e.target.classList.contains('product-name') || e.target.classList.contains('category-name')) {
                    updateSummaryTable();
                }
            });

            productListContainer.addEventListener('change', function (e) {
                if (e.target.classList.contains('category-image-upload')) {
                    const file = e.target.files[0];
                    const categoryEntry = e.target.closest('.category-entry');
                    const imagePreview = categoryEntry.querySelector('.image-preview');
                    const deleteImageBtn = categoryEntry.querySelector('.delete-image-btn');
                    const fileInputLabel = categoryEntry.querySelector('.custom-file-label');

                    if (file) {
                        const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                        if (!validTypes.includes(file.type)) {
                            alert('Format file tidak didukung. Harap unggah JPG, JPEG, atau PNG.');
                            e.target.value = '';
                            if (fileInputLabel) fileInputLabel.textContent = 'Pilih gambar...';
                            imagePreview.style.display = 'none';
                            deleteImageBtn.style.display = 'none';
                            updateSummaryTable(); // Update table even if invalid to reflect no image
                            return;
                        }

                        // Periksa ukuran file (2MB = 2 * 1024 * 1024 bytes)
                        const MAX_FILE_SIZE = 2 * 1024 * 1024;
                        if (file.size > MAX_FILE_SIZE) {
                            alert('Ukuran gambar terlalu besar. Maksimum 2MB.');
                            e.target.value = ''; // Clear the input
                            if (fileInputLabel) fileInputLabel.textContent = 'Pilih gambar...';
                            imagePreview.style.display = 'none';
                            deleteImageBtn.style.display = 'none';
                            updateSummaryTable(); // Update table even if too large
                            return;
                        }

                        if (fileInputLabel) fileInputLabel.textContent = file.name;

                        const reader = new FileReader();
                        reader.onload = function (event) {
                            imagePreview.src = event.target.result;
                            imagePreview.style.display = 'block';
                            deleteImageBtn.style.display = 'inline-block';
                            updateSummaryTable(); // Update table after successful image upload
                        }
                        reader.readAsDataURL(file);
                    } else {
                        if (fileInputLabel) fileInputLabel.textContent = 'Pilih gambar...';
                        imagePreview.style.display = 'none';
                        imagePreview.src = '#';
                        deleteImageBtn.style.display = 'none';
                        updateSummaryTable(); // Update table if no file selected
                    }
                }
            });

            productSummaryBody.addEventListener('click', function (e) {
                if (e.target.classList.contains('clickable-image') && e.target.src !== window.location.href + '#') {
                    fullSizeImage.src = e.target.src;
                    imageModal.modal('show');
                }
            });

            updateAllIndicesAndLimits();
        });
    </script>
@endpush