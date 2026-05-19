<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold">
            ✨ Kelas Pelatihan 🚀
        </h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.5/css/dx.material.purple.light.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn3.devexpress.com/jslib/23.2.5/js/dx.all.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-2xl shadow p-5">
            <div id="gridKelas"></div>
        </div>
    </div>

    {{-- MODAL CREATE --}}
    <div id="modalCreateKelas" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl max-h-[95vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800">Tambah Kelas</h3>
                <button type="button" class="text-gray-500 hover:text-gray-700 text-2xl" onclick="closeCreateModal()">&times;</button>
            </div>

            <form id="formCreateKelas" class="px-6 py-5 space-y-4">
                <div>
                    <label class="block text-sm font-semibold mb-1">Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="create_nama_kelas"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Masukkan nama kelas">
                    <div class="text-red-500 text-sm mt-1 error-text" id="error_create_nama_kelas"></div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="create_deskripsi" rows="4"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Masukkan deskripsi"></textarea>
                    <div class="text-red-500 text-sm mt-1 error-text" id="error_create_deskripsi"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Harga</label>
                        <input type="text" name="harga" id="create_harga"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            inputmode="numeric"
                            placeholder="Rp 0">
                        <div class="text-red-500 text-sm mt-1 error-text" id="error_create_harga"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Harga Coret</label>
                        <input type="text" name="harga_coret" id="create_harga_coret"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            inputmode="numeric"
                            placeholder="Rp 0">
                        <div class="text-red-500 text-sm mt-1 error-text" id="error_create_harga_coret"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Periode</label>
                    <input type="date" name="periode" id="create_periode"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <div class="text-red-500 text-sm mt-1 error-text" id="error_create_periode"></div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Kategori</label>
                    <select name="kategori" id="create_kategori"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="IT">IT</option>
                        <option value="Academic">Academic</option>
                    </select>
                    <div class="text-red-500 text-sm mt-1 error-text" id="error_create_kategori"></div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Thumbnail</label>
                    <input type="file" accept="image/*" id="create_thumbnail"
                        class="w-full rounded-xl border border-gray-300 p-2 bg-white">
                    <div id="create_preview_wrap" class="mt-3 hidden">
                        <img id="create_preview" src="" class="w-28 h-28 object-cover rounded-xl border">
                    </div>
                    <div class="text-red-500 text-sm mt-1 error-text" id="error_create_thumbnail"></div>
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="create_is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="create_is_active" class="text-sm font-semibold text-gray-700">Aktif</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeCreateModal()"
                        class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 font-semibold">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="modalEditKelas" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white w-full max-w-2xl rounded-2xl shadow-2xl max-h-[95vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <h3 class="text-lg font-bold text-gray-800">Edit Kelas</h3>
                <button type="button" class="text-gray-500 hover:text-gray-700 text-2xl" onclick="closeEditModal()">&times;</button>
            </div>

            <form id="formEditKelas" class="px-6 py-5 space-y-4">
                <input type="hidden" id="edit_id">
                <input type="hidden" id="edit_old_thumbnail">

                <div>
                    <label class="block text-sm font-semibold mb-1">Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="edit_nama_kelas"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <div class="text-red-500 text-sm mt-1 error-text" id="error_edit_nama_kelas"></div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="4"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                    <div class="text-red-500 text-sm mt-1 error-text" id="error_edit_deskripsi"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold mb-1">Harga</label>
                        <input type="text" name="harga" id="edit_harga"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            inputmode="numeric"
                            placeholder="Rp 0">
                        <div class="text-red-500 text-sm mt-1 error-text" id="error_edit_harga"></div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold mb-1">Harga Coret</label>
                        <input type="text" name="harga_coret" id="edit_harga_coret"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                            inputmode="numeric"
                            placeholder="Rp 0">
                        <div class="text-red-500 text-sm mt-1 error-text" id="error_edit_harga_coret"></div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1">Periode</label>
                    <input type="date" name="periode" id="edit_periode"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <div class="text-red-500 text-sm mt-1 error-text" id="error_edit_periode"></div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Kategori</label>
                    <select name="kategori" id="edit_kategori"
                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Kategori --</option>
                        <option value="IT">IT</option>
                        <option value="Academic">Academic</option>
                    </select>
                    <div class="text-red-500 text-sm mt-1 error-text" id="error_edit_kategori"></div>
                </div>
                <div>
                    <label class="block text-sm font-semibold mb-1">Thumbnail Baru</label>
                    <input type="file" accept="image/*" id="edit_thumbnail"
                        class="w-full rounded-xl border border-gray-300 p-2 bg-white">
                    <div id="edit_preview_wrap" class="mt-3 hidden">
                        <img id="edit_preview" src="" class="w-28 h-28 object-cover rounded-xl border">
                    </div>
                    <div class="text-red-500 text-sm mt-1 error-text" id="error_edit_thumbnail"></div>
                </div>

                <div>
                    <p class="text-sm font-semibold mb-2">Thumbnail Lama</p>
                    <img id="edit_current_thumbnail" src="" class="w-28 h-28 object-cover rounded-xl border hidden">
                </div>

                <div class="flex items-center gap-3">
                    <input type="checkbox" id="edit_is_active" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="edit_is_active" class="text-sm font-semibold text-gray-700">Aktif</label>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2 rounded-xl bg-amber-600 text-white hover:bg-amber-700 font-semibold">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL MODUL --}}
    <div id="modalModulKelas" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
        <div class="bg-white w-full max-w-5xl rounded-2xl shadow-2xl max-h-[95vh] overflow-y-auto">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Modul Kelas</h3>
                    <p class="text-sm text-gray-500">
                        Kelas: <span id="modul_kelas_title" class="font-semibold text-gray-700"></span>
                    </p>
                </div>
                <button type="button" class="text-gray-500 hover:text-gray-700 text-2xl" onclick="closeModulModal()">&times;</button>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 p-6">
                <div class="lg:col-span-1">
                    <div class="border rounded-2xl p-4">
                        <h4 class="font-bold text-gray-800 mb-3">Upload Modul</h4>

                        <form id="formUploadModul" class="space-y-4">
                            <input type="hidden" id="modul_kelas_id">

                            <div>
                                <label class="block text-sm font-semibold mb-1">File Modul (Bisa lebih dari 1)</label>
                                <input type="file" id="modul_files" multiple
                                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,.png,.jpg,.jpeg"
                                    class="w-full rounded-xl border border-gray-300 p-2 bg-white">
                                <div class="text-red-500 text-sm mt-1 error-text" id="error_modul_files"></div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold mb-2">Daftar File Dipilih</label>
                                <div id="modul_file_list" class="space-y-2"></div>
                            </div>

                            <div class="flex justify-end gap-3 pt-2 border-t">
                                <button type="button" onclick="closeModulModal()"
                                    class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">
                                    Tutup
                                </button>
                                <button type="submit"
                                    class="px-5 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 font-semibold">
                                    Upload
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="lg:col-span-2">
                    <div class="border rounded-2xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-gray-800">Tabel Modul</h4>
                            <button type="button" class="px-3 py-2 rounded-xl bg-indigo-600 text-white text-sm hover:bg-indigo-700"
                                onclick="refreshModulGrid()">
                                Refresh
                            </button>
                        </div>

                        <div id="gridModul" style="min-height: 420px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(function() {
            const token = $('meta[name="csrf-token"]').attr('content');

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': token
                }
            });

            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: "toast-top-right",
                timeOut: "2500"
            };

            let createSelectedFile = null;
            let editSelectedFile = null;
            let modulSelectedFiles = [];
            let gridInstance = null;
            let gridModulInstance = null;

            function formatRupiah(value) {
                if (value === null || value === undefined || value === '') return '';
                const number = String(value).replace(/[^\d]/g, '');
                if (!number) return '';
                return new Intl.NumberFormat('id-ID').format(number);
            }

            function stripRupiah(value) {
                if (value === null || value === undefined) return '';
                return String(value).replace(/[^\d]/g, '');
            }

            function setMoneyValue(selector, value) {
                $(selector).val(value !== null && value !== undefined && value !== '' ? formatRupiah(value) : '');
            }

            function clearErrors(prefix) {
                $(`[id^="error_${prefix}_"]`).text('');
            }

            function resetCreateForm() {
                $('#formCreateKelas')[0].reset();
                createSelectedFile = null;
                $('#create_preview_wrap').addClass('hidden');
                $('#create_preview').attr('src', '');
                setMoneyValue('#create_harga', '');
                setMoneyValue('#create_harga_coret', '');
                clearErrors('create');
            }

            function resetEditForm() {
                $('#formEditKelas')[0].reset();
                editSelectedFile = null;
                $('#edit_preview_wrap').addClass('hidden');
                $('#edit_preview').attr('src', '');
                $('#edit_current_thumbnail').addClass('hidden').attr('src', '');
                setMoneyValue('#edit_harga', '');
                setMoneyValue('#edit_harga_coret', '');
                clearErrors('edit');
            }

            function renderModulFileList() {
                const wrap = $('#modul_file_list');
                wrap.empty();

                if (!modulSelectedFiles.length) {
                    wrap.append(`
                        <div class="text-sm text-gray-400">
                            Belum ada file dipilih.
                        </div>
                    `);
                    return;
                }

                modulSelectedFiles.forEach((file, index) => {
                    const sizeKb = (file.size / 1024).toFixed(1);
                    const item = $(`
                        <div class="flex items-center justify-between gap-3 rounded-xl border px-3 py-2 bg-gray-50">
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-gray-700 truncate">${file.name}</p>
                                <p class="text-xs text-gray-500">${sizeKb} KB</p>
                            </div>
                            <button type="button" class="text-red-500 hover:text-red-700 text-sm font-semibold">
                                Hapus
                            </button>
                        </div>
                    `);

                    item.find('button').on('click', function() {
                        modulSelectedFiles.splice(index, 1);
                        renderModulFileList();

                        const dt = new DataTransfer();
                        modulSelectedFiles.forEach(f => dt.items.add(f));
                        $('#modul_files')[0].files = dt.files;
                    });

                    wrap.append(item);
                });
            }

            function resetModulForm() {
                $('#formUploadModul')[0].reset();
                modulSelectedFiles = [];
                $('#error_modul_files').text('');
                $('#modul_file_list').html('<div class="text-sm text-gray-400">Belum ada file dipilih.</div>');
            }

            function closeCreateModal() {
                $('#modalCreateKelas').addClass('hidden');
                resetCreateForm();
            }

            function closeEditModal() {
                $('#modalEditKelas').addClass('hidden');
                resetEditForm();
            }

            function closeModulModal() {
                $('#modalModulKelas').addClass('hidden');
                resetModulForm();
                $('#modul_kelas_title').text('');
                $('#modul_kelas_id').val('');
            }

            function formatDateToInput(value) {
                if (!value) return '';
                let date = new Date(value);
                if (isNaN(date.getTime())) return '';
                let yyyy = date.getFullYear();
                let mm = String(date.getMonth() + 1).padStart(2, '0');
                let dd = String(date.getDate()).padStart(2, '0');
                return `${yyyy}-${mm}-${dd}`;
            }

            function renderError(xhr, prefix) {
                if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                    const errors = xhr.responseJSON.errors;
                    Object.keys(errors).forEach(field => {
                        $(`#error_${prefix}_${field}`).text(errors[field][0]);
                    });
                    toastr.error('Validasi gagal. Cek kembali form.');
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Terjadi kesalahan.');
                }
            }

            function createModulStore(kelasId) {
                return new DevExpress.data.CustomStore({
                    key: "id",
                    load: function() {
                        return $.getJSON(`/kelas/${kelasId}/modul/data`);
                    },
                    remove: function(key) {
                        return $.ajax({
                            url: "/modul/" + key,
                            method: "DELETE",
                            success: function(res) {
                                toastr.success(res.message || 'Modul berhasil dihapus.');
                            },
                            error: function() {
                                toastr.error('Gagal menghapus modul.');
                            }
                        });
                    }
                });
            }

            function refreshModulGrid() {
                if (gridModulInstance) {
                    gridModulInstance.refresh();
                }
            }

            window.closeCreateModal = closeCreateModal;
            window.closeEditModal = closeEditModal;
            window.closeModulModal = closeModulModal;
            window.refreshModulGrid = refreshModulGrid;

            $('#create_harga, #create_harga_coret, #edit_harga, #edit_harga_coret').on('input', function() {
                const raw = stripRupiah($(this).val());
                $(this).val(raw ? formatRupiah(raw) : '');
            });

            $('#create_thumbnail').on('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                createSelectedFile = file;
                const reader = new FileReader();
                reader.onload = function(ev) {
                    $('#create_preview').attr('src', ev.target.result);
                    $('#create_preview_wrap').removeClass('hidden');
                };
                reader.readAsDataURL(file);
            });

            $('#edit_thumbnail').on('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                editSelectedFile = file;
                const reader = new FileReader();
                reader.onload = function(ev) {
                    $('#edit_preview').attr('src', ev.target.result);
                    $('#edit_preview_wrap').removeClass('hidden');
                };
                reader.readAsDataURL(file);
            });

            $('#modul_files').on('change', function(e) {
                modulSelectedFiles = Array.from(e.target.files || []);
                renderModulFileList();
            });

            $('#formCreateKelas').on('submit', function(e) {
                e.preventDefault();
                clearErrors('create');

                const formData = new FormData();
                formData.append('nama_kelas', $('#create_nama_kelas').val() || '');
                formData.append('deskripsi', $('#create_deskripsi').val() || '');
                formData.append('harga', stripRupiah($('#create_harga').val()) || 0);
                formData.append('harga_coret', stripRupiah($('#create_harga_coret').val()) || 0);
                formData.append('periode', $('#create_periode').val() || '');
                formData.append('is_active', $('#create_is_active').is(':checked') ? 1 : 0);
                formData.append('kategori', $('#create_kategori').val() || '');
                if (createSelectedFile) {
                    formData.append('thumbnail', createSelectedFile);
                }

                $.ajax({
                    url: "/kelas",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        closeCreateModal();
                        gridInstance.refresh();
                        toastr.success(res.message || 'Data berhasil disimpan.');
                    },
                    error: function(xhr) {
                        renderError(xhr, 'create');
                    }
                });
            });

            $('#formEditKelas').on('submit', function(e) {
                e.preventDefault();
                clearErrors('edit');

                const id = $('#edit_id').val();
                const formData = new FormData();

                formData.append('nama_kelas', $('#edit_nama_kelas').val() || '');
                formData.append('deskripsi', $('#edit_deskripsi').val() || '');
                formData.append('harga', stripRupiah($('#edit_harga').val()) || 0);
                formData.append('harga_coret', stripRupiah($('#edit_harga_coret').val()) || 0);
                formData.append('periode', $('#edit_periode').val() || '');
                formData.append('kategori', $('#edit_kategori').val() || '');
                formData.append('is_active', $('#edit_is_active').is(':checked') ? 1 : 0);
                formData.append('_method', 'PUT');

                if (editSelectedFile) {
                    formData.append('thumbnail', editSelectedFile);
                }

                $.ajax({
                    url: "/kelas/" + id,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        closeEditModal();
                        gridInstance.refresh();
                        toastr.success(res.message || 'Data berhasil diupdate.');
                    },
                    error: function(xhr) {
                        renderError(xhr, 'edit');
                    }
                });
            });

            $('#formUploadModul').on('submit', function(e) {
                e.preventDefault();
                $('#error_modul_files').text('');

                const kelasId = $('#modul_kelas_id').val();
                if (!kelasId) {
                    toastr.error('Kelas belum dipilih.');
                    return;
                }

                if (!modulSelectedFiles.length) {
                    $('#error_modul_files').text('Pilih minimal 1 file modul.');
                    return;
                }

                const formData = new FormData();
                modulSelectedFiles.forEach(file => {
                    formData.append('files[]', file);
                });
                formData.append('kelas_id', kelasId);

                $.ajax({
                    url: `/kelas/${kelasId}/modul`,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        toastr.success(res.message || 'Modul berhasil diupload.');
                        resetModulForm();
                        refreshModulGrid();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;
                            if (errors.files && errors.files[0]) {
                                $('#error_modul_files').text(errors.files[0]);
                            } else {
                                $('#error_modul_files').text('Validasi gagal.');
                            }
                        } else {
                            toastr.error(xhr.responseJSON?.message || 'Gagal upload modul.');
                        }
                    }
                });
            });

            const store = new DevExpress.data.CustomStore({
                key: "id",
                load: function() {
                    return $.getJSON("/kelas/data");
                },
                remove: function(key) {
                    return $.ajax({
                        url: "/kelas/" + key,
                        method: "DELETE",
                        success: function(res) {
                            toastr.success(res.message || 'Data berhasil dihapus.');
                        },
                        error: function() {
                            toastr.error('Gagal menghapus data.');
                        }
                    });
                }
            });

            gridInstance = $("#gridKelas").dxDataGrid({
                dataSource: store,
                keyExpr: "id",
                showBorders: true,
                rowAlternationEnabled: true,
                columnAutoWidth: true,
                wordWrapEnabled: true,
                searchPanel: {
                    visible: true,
                    placeholder: "🔍 Cari kelas..."
                },
                paging: {
                    pageSize: 10
                },
                pager: {
                    visible: true,
                    showPageSizeSelector: true,
                    allowedPageSizes: [10, 25, 50]
                },
                toolbar: {
                    items: [{
                            location: "before",
                            widget: "dxButton",
                            options: {
                                text: "Tambah Kelas",
                                icon: "add",
                                type: "default",
                                onClick: function() {
                                    resetCreateForm();
                                    $('#modalCreateKelas').removeClass('hidden');
                                }
                            }
                        },
                        "searchPanel"
                    ]
                },
                columns: [{
                        dataField: "thumbnail",
                        caption: "Cover",
                        width: 90,
                        alignment: "center",
                        cellTemplate: function(container, options) {
                            if (options.data.thumbnail) {
                                $("<img>")
                                    .attr("src", "/" + options.data.thumbnail)
                                    .css({
                                        width: "60px",
                                        height: "60px",
                                        objectFit: "cover",
                                        borderRadius: "8px"
                                    })
                                    .appendTo(container);
                            } else {
                                $("<span>")
                                    .text("-")
                                    .addClass("text-gray-400")
                                    .appendTo(container);
                            }
                        }
                    },
                    {
                        dataField: "nama_kelas",
                        caption: "Nama Kelas"
                    },
                    {
                        dataField: "deskripsi",
                        caption: "Deskripsi"
                    },
                    {
                        dataField: "periode",
                        caption: "Periode",
                        dataType: "date",
                        format: "dd MMM yyyy"
                    },
                    {
                        dataField: "harga",
                        caption: "Harga",
                        customizeText: function(e) {
                            return e.value ? 'Rp ' + formatRupiah(e.value) : '-';
                        }
                    },
                    {
                        dataField: "kategori",
                        caption: "Kategori",
                        alignment: "center",
                        cellTemplate: function(container, options) {
                            const val = options.data.kategori;

                            let color = "#64748b";
                            if (val === "IT") color = "#2563eb";
                            if (val === "Academic") color = "#7c3aed";

                            $("<span>")
                                .text(val || "-")
                                .css({
                                    padding: "5px 10px",
                                    background: color,
                                    color: "white",
                                    borderRadius: "20px",
                                    fontSize: "11px",
                                    fontWeight: "bold"
                                })
                                .appendTo(container);
                        }
                    },
                    {
                        dataField: "harga_coret",
                        caption: "Harga Coret",
                        customizeText: function(e) {
                            return e.value ? 'Rp ' + formatRupiah(e.value) : '-';
                        }
                    },
                    {
                        dataField: "is_active",
                        caption: "Status",
                        alignment: "center",
                        cellTemplate: function(container, options) {
                            const active = options.data.is_active == 1 || options.data.is_active === true;
                            $("<span>")
                                .text(active ? "AKTIF" : "NONAKTIF")
                                .css({
                                    padding: "5px 10px",
                                    background: active ? "#22c55e" : "#64748b",
                                    color: "white",
                                    borderRadius: "20px",
                                    fontSize: "11px",
                                    fontWeight: "bold"
                                })
                                .appendTo(container);
                        }
                    },
                    {
                        caption: "Aksi",
                        width: 250,
                        alignment: "center",
                        cellTemplate: function(container, options) {
                            const data = options.data;

                            const btnEdit = $("<button>")
                                .addClass("px-3 py-1.5 rounded-lg bg-amber-500 text-white text-sm mr-2 hover:bg-amber-600")
                                .text("Edit")
                                .on("click", function() {
                                    resetEditForm();
                                    $('#edit_id').val(data.id);
                                    $('#edit_nama_kelas').val(data.nama_kelas || '');
                                    $('#edit_deskripsi').val(data.deskripsi || '');
                                    setMoneyValue('#edit_harga', data.harga || '');
                                    setMoneyValue('#edit_harga_coret', data.harga_coret || '');
                                    $('#edit_periode').val(formatDateToInput(data.periode));
                                    $('#edit_is_active').prop('checked', data.is_active == 1 || data.is_active === true);
                                    $('#edit_kategori').val(data.kategori || '');
                                    $('#edit_old_thumbnail').val(data.thumbnail || '');

                                    if (data.thumbnail) {
                                        $('#edit_current_thumbnail')
                                            .attr('src', '/' + data.thumbnail)
                                            .removeClass('hidden');
                                    } else {
                                        $('#edit_current_thumbnail').addClass('hidden').attr('src', '');
                                    }

                                    $('#modalEditKelas').removeClass('hidden');
                                });

                            const btnModul = $("<button>")
                                .addClass("px-3 py-1.5 rounded-lg bg-indigo-600 text-white text-sm mr-2 hover:bg-indigo-700")
                                .text("Modul")
                                .on("click", function() {
                                    const kelasId = data.id;
                                    $('#modul_kelas_id').val(kelasId);
                                    $('#modul_kelas_title').text(data.nama_kelas || '-');
                                    $('#modalModulKelas').removeClass('hidden');

                                    resetModulForm();

                                    if (!gridModulInstance) {
                                        gridModulInstance = $("#gridModul").dxDataGrid({
                                            dataSource: createModulStore(kelasId),
                                            keyExpr: "id",
                                            showBorders: true,
                                            rowAlternationEnabled: true,
                                            columnAutoWidth: true,
                                            wordWrapEnabled: true,
                                            paging: {
                                                pageSize: 10
                                            },
                                            pager: {
                                                visible: true,
                                                showPageSizeSelector: true,
                                                allowedPageSizes: [10, 25, 50]
                                            },
                                            searchPanel: {
                                                visible: true,
                                                placeholder: "🔍 Cari modul..."
                                            },
                                            columns: [{
                                                    dataField: "original_name",
                                                    caption: "Nama File"
                                                },
                                                {
                                                    dataField: "file_path",
                                                    caption: "File",
                                                    cellTemplate: function(container, options) {
                                                        if (options.data.file_path) {
                                                            $("<a>")
                                                                .attr("href", "/" + options.data.file_path)
                                                                .attr("target", "_blank")
                                                                .addClass("text-indigo-600 underline")
                                                                .text("Lihat File")
                                                                .appendTo(container);
                                                        } else {
                                                            $("<span>").text("-").appendTo(container);
                                                        }
                                                    }
                                                },
                                                {
                                                    dataField: "file_size",
                                                    caption: "Ukuran",
                                                    customizeText: function(e) {
                                                        if (!e.value) return '-';
                                                        const kb = (parseInt(e.value) / 1024).toFixed(1);
                                                        return kb + ' KB';
                                                    }
                                                },
                                                {
                                                    dataField: "created_at",
                                                    caption: "Dibuat",
                                                    dataType: "datetime",
                                                    format: "dd MMM yyyy HH:mm"
                                                },
                                                {
                                                    caption: "Aksi",
                                                    width: 120,
                                                    alignment: "center",
                                                    cellTemplate: function(container, options) {
                                                        const btnDelete = $("<button>")
                                                            .addClass("px-3 py-1.5 rounded-lg bg-red-500 text-white text-sm hover:bg-red-600")
                                                            .text("Hapus")
                                                            .on("click", function() {
                                                                if (confirm("Yakin ingin menghapus modul ini?")) {
                                                                    $.ajax({
                                                                        url: "/modul/" + options.data.id,
                                                                        method: "DELETE",
                                                                        success: function(res) {
                                                                            toastr.success(res.message || 'Modul berhasil dihapus.');
                                                                            gridModulInstance.refresh();
                                                                        },
                                                                        error: function() {
                                                                            toastr.error('Gagal menghapus modul.');
                                                                        }
                                                                    });
                                                                }
                                                            });

                                                        $("<div>").append(btnDelete).appendTo(container);
                                                    }
                                                }
                                            ],
                                            onContentReady: function() {
                                                gridModulInstance.updateDimensions();
                                            }
                                        }).dxDataGrid("instance");
                                    } else {
                                        gridModulInstance.option('dataSource', createModulStore(kelasId));
                                        gridModulInstance.refresh();
                                        setTimeout(function() {
                                            gridModulInstance.updateDimensions();
                                        }, 100);
                                    }

                                    setTimeout(function() {
                                        gridModulInstance.updateDimensions();
                                    }, 150);
                                });

                            const btnDelete = $("<button>")
                                .addClass("px-3 py-1.5 rounded-lg bg-red-500 text-white text-sm hover:bg-red-600")
                                .text("Hapus")
                                .on("click", function() {
                                    if (confirm("Yakin ingin menghapus data ini?")) {
                                        $.ajax({
                                            url: "/kelas/" + data.id,
                                            method: "DELETE",
                                            success: function(res) {
                                                gridInstance.refresh();
                                                toastr.success(res.message || 'Data berhasil dihapus.');
                                            },
                                            error: function() {
                                                toastr.error('Gagal menghapus data.');
                                            }
                                        });
                                    }
                                });

                            $("<div>").append(btnEdit, btnModul, btnDelete).appendTo(container);
                        }
                    }
                ]
            }).dxDataGrid("instance");
        });
    </script>
</x-app-layout>