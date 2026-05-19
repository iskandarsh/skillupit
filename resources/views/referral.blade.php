<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            🎯 Data Referal
        </h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- ✅ JQUERY (WAJIB PALING ATAS) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- ✅ SELECT2 (FIX VERSION) -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- ✅ DEVEXTREME -->
    <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.5/css/dx.material.purple.light.css">
    <script src="https://cdn3.devexpress.com/jslib/23.2.5/js/dx.all.js"></script>

    <!-- ✅ TOASTR -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-2xl shadow p-5">
            <div id="gridReferal"></div>
        </div>
    </div>

    {{-- ✅ MODAL --}}
    <div id="modalReferal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 space-y-4">

            <h3 id="modalTitle" class="text-lg font-bold text-gray-800"></h3>

            <input type="hidden" id="ref_id">

            <!-- KODE -->
            <div>
                <label class="text-sm font-semibold">Kode Referal</label>
                <input type="text" id="kode"
                    class="w-full border rounded-xl p-2 mt-1 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- USER -->
            <div>
                <label class="text-sm font-semibold">User</label>
                <select id="user_id" class="w-full">
                    <option value="">ALL USER</option>
                    @foreach($users as $u)
                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- KELAS -->
            <div>
                <label class="text-sm font-semibold">Kelas</label>
                <select id="kelas_id" class="w-full">
                    <option value="">ALL KELAS</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- DISC -->
            <div>
                <label class="text-sm font-semibold">Diskon (%)</label>
                <input type="number" id="disc"
                    class="w-full border rounded-xl p-2 mt-1"
                    placeholder="0 - 100"
                    min="0"
                    max="100">
            </div>

            <div class="flex justify-end gap-2 pt-3">
                <button onclick="closeModal()"
                    class="px-4 py-2 rounded-xl border text-gray-600 hover:bg-gray-100">
                    Batal
                </button>

                <button onclick="save()"
                    class="px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700">
                    Simpan
                </button>
            </div>

        </div>
    </div>

    <script>
        const token = $('meta[name="csrf-token"]').attr('content');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': token
            }
        });

        toastr.options = {
            positionClass: "toast-top-right",
            timeOut: 2000
        };

        let mode = "create";

        // ✅ SELECT2 INIT
        function initSelect2() {
            $('#user_id').select2({
                dropdownParent: $('#modalReferal'),
                width: '100%',
                placeholder: "Pilih User",
                allowClear: true
            });

            $('#kelas_id').select2({
                dropdownParent: $('#modalReferal'),
                width: '100%',
                placeholder: "Pilih Kelas",
                allowClear: true
            });
        }

        // ✅ DATAGRID FIX (CustomStore biar ga warning)
        const store = new DevExpress.data.CustomStore({
            key: "id",
            load: () => $.getJSON("/referral")
        });

        const grid = $("#gridReferal").dxDataGrid({
            dataSource: store,
            keyExpr: "id",
            showBorders: true,
            rowAlternationEnabled: true,
            searchPanel: {
                visible: true
            },
            paging: {
                pageSize: 10
            },

            toolbar: {
                items: [{
                    location: "before",
                    widget: "dxButton",
                    options: {
                        text: "Tambah Referal",
                        icon: "add",
                        type: "default",
                        onClick: () => openCreate()
                    }
                }]
            },

            columns: [{
                    dataField: "kode",
                    caption: "Kode"
                },
                {
                    caption: "User",
                    calculateCellValue: d => d.user ? d.user.name : "ALL USER"
                },
                {
                    caption: "Kelas",
                    calculateCellValue: d => d.kelas ? d.kelas.nama_kelas : "ALL KELAS"
                },
                {
                    dataField: "disc",
                    caption: "Diskon",
                    customizeText: e => e.value ? e.value + " %" : "-"
                },
                {
                    caption: "Aksi",
                    width: 180,
                    cellTemplate: function(container, options) {
                        const d = options.data;

                        $("<button>")
                            .text("Edit")
                            .addClass("px-3 py-1 bg-amber-500 text-white rounded mr-2")
                            .on("click", () => openEdit(d))
                            .appendTo(container);

                        $("<button>")
                            .text("Hapus")
                            .addClass("px-3 py-1 bg-red-500 text-white rounded")
                            .on("click", () => destroy(d.id))
                            .appendTo(container);
                    }
                }
            ]
        }).dxDataGrid("instance");

        function openCreate() {
            mode = "create";
            $('#modalTitle').text('Tambah Referal');
            $('#ref_id').val('');
            $('#kode').val('');
            $('#disc').val('');

            $('#modalReferal').removeClass('hidden');
            setTimeout(initSelect2, 100);

            $('#user_id').val('').trigger('change');
            $('#kelas_id').val('').trigger('change');
        }

        function openEdit(d) {
            mode = "edit";
            $('#modalTitle').text('Edit Referal');

            $('#ref_id').val(d.id);
            $('#kode').val(d.kode);
            $('#disc').val(d.disc ?? '');

            $('#modalReferal').removeClass('hidden');

            setTimeout(() => {
                initSelect2();
                $('#user_id').val(d.user_id).trigger('change');
                $('#kelas_id').val(d.kelas_id).trigger('change');
            }, 100);
        }

        function closeModal() {
            $('#modalReferal').addClass('hidden');
        }

        function save() {
            const id = $('#ref_id').val();

            const data = {
                kode: $('#kode').val(),
                user_id: $('#user_id').val(),
                kelas_id: $('#kelas_id').val(),
                disc: $('#disc').val()
            };

            if (mode === "create") {
                $.post("/referral", data)
                    .done(() => {
                        toastr.success("Berhasil ditambahkan");
                        closeModal();
                        grid.refresh();
                    })
                    .fail(err => toastr.error(err.responseJSON?.message));
            } else {
                $.ajax({
                    url: "/referral/" + id,
                    method: "PUT",
                    data: data
                }).done(() => {
                    toastr.success("Berhasil diupdate");
                    closeModal();
                    grid.refresh();
                });
            }
        }

        function destroy(id) {
            if (!confirm("Yakin hapus data ini?")) return;

            $.ajax({
                url: "/referral/" + id,
                method: "DELETE"
            }).done(() => {
                toastr.success("Berhasil dihapus");
                grid.refresh();
            });
        }
    </script>
</x-app-layout>