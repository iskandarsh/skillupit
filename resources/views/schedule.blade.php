<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            📅 Data Schedule
        </h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- JQUERY -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- SELECT2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <!-- DEVEXTREME -->
    <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.5/css/dx.material.purple.light.css">
    <script src="https://cdn3.devexpress.com/jslib/23.2.5/js/dx.all.js"></script>

    <!-- TOASTR -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-2xl shadow p-5">
            <div id="gridSchedule"></div>
        </div>
    </div>

    {{-- MODAL --}}
    <div id="modalSchedule" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white w-full max-w-md rounded-2xl shadow-xl p-6 space-y-4">

            <h3 id="modalTitle" class="text-lg font-bold text-gray-800"></h3>

            <input type="hidden" id="schedule_id">

            <!-- TITLE -->
            <div>
                <label class="text-sm font-semibold">Judul Schedule</label>
                <input type="text" id="title"
                    class="w-full border rounded-xl p-2 mt-1 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- KELAS -->
            <div>
                <label class="text-sm font-semibold">Kelas</label>
                <select id="kelas_id" class="w-full">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <!-- DATE -->
            <div>
                <label class="text-sm font-semibold">Tanggal</label>
                <input type="date" id="date"
                    class="w-full border rounded-xl p-2 mt-1">
            </div>

            <!-- START TIME -->
            <div>
                <label class="text-sm font-semibold">Start Time</label>
                <input type="time" id="start_time"
                    class="w-full border rounded-xl p-2 mt-1">
            </div>

            <!-- END TIME -->
            <div>
                <label class="text-sm font-semibold">End Time</label>
                <input type="time" id="end_time"
                    class="w-full border rounded-xl p-2 mt-1">
            </div>

            <!-- LINK -->
            <div>
                <label class="text-sm font-semibold">Link</label>
                <input type="url" id="link"
                    placeholder="https://..."
                    class="w-full border rounded-xl p-2 mt-1">
            </div>

            <!-- SESSION DROPDOWN -->
            <div>
                <label class="text-sm font-semibold">Session</label>
                <select id="session_order" class="w-full border rounded-xl p-2 mt-1">
                    <option value="">Pilih Session</option>
                    @for($i = 1; $i <= 10; $i++)
                        <option value="{{ $i }}">Session {{ $i }}</option>
                        @endfor
                </select>
            </div>

            <!-- LAST SESSION -->
            <div class="flex items-center gap-2 pt-1">
                <input type="checkbox" id="is_last_session" value="1"
                    class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                <label for="is_last_session" class="text-sm text-gray-700">
                    Ini last session
                </label>
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
            <div id="loadingOverlay" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-[999]">
                <div class="bg-white px-6 py-4 rounded-xl shadow flex items-center gap-3">
                    <svg class="animate-spin w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                    </svg>
                    <span class="text-sm font-medium text-gray-700">Menyimpan...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL UPLOAD VIDEO -->

    <div id="modalUploadVideo"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60">

        <div class="bg-white w-full max-w-md rounded-3xl p-6">

            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl font-bold">
                    Upload Video Record
                </h2>

                <button onclick="closeUploadVideo()"
                    class="text-red-500 text-xl font-bold">
                    ✕
                </button>
            </div>

            <form id="formUploadVideo">

                <input type="hidden" id="schedule_id">

                <div class="mb-5">
                    <label class="block text-sm font-semibold mb-2">
                        Upload Video
                    </label>

                    <input type="file"
                        id="record_video"
                        name="record_video"
                        accept="video/*"
                        class="w-full border rounded-xl px-4 py-3">
                </div>

                <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 rounded-2xl">
                    Upload Sekarang
                </button>

            </form>
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

        function initSelect2() {
            if ($('#kelas_id').hasClass("select2-hidden-accessible")) {
                $('#kelas_id').select2('destroy');
            }

            $('#kelas_id').select2({
                dropdownParent: $('#modalSchedule'),
                width: '100%',
                theme: 'bootstrap-5',
                placeholder: "Pilih Kelas",
                allowClear: true
            });
        }

        function showLoading() {
            $('#loadingOverlay').removeClass('hidden');
        }

        function hideLoading() {
            $('#loadingOverlay').addClass('hidden');
        }

        const store = new DevExpress.data.CustomStore({
            key: "id",
            load: () => $.getJSON("/schedule")
        });

        const grid = $("#gridSchedule").dxDataGrid({
            dataSource: store,
            keyExpr: "id",
            showBorders: true,
            rowAlternationEnabled: true,

            searchPanel: {
                visible: true,
                width: 250,
                placeholder: "Cari schedule...",
                highlightCaseSensitive: false
            },

            filterRow: {
                visible: true,
                applyFilter: "auto"
            },

            paging: {
                pageSize: 10
            },

            toolbar: {
                items: [{
                    location: "before",
                    widget: "dxButton",
                    options: {
                        text: "Tambah Schedule",
                        icon: "add",
                        type: "default",
                        onClick: () => openCreate()
                    }
                }]
            },

            columns: [{
                    dataField: "title",
                    caption: "Judul"
                },

                {
                    dataField: "date",
                    caption: "Tanggal"
                },

                {
                    caption: "Jam",
                    calculateCellValue: d => {
                        const start = d.start_time ?? "-";
                        const end = d.end_time ?? "-";

                        return `${start} - ${end}`;
                    }
                },

                {
                    caption: "Kelas",
                    calculateCellValue: d => d.kelas ? d.kelas.nama_kelas : "-"
                },

                {
                    caption: "Session",
                    calculateCellValue: d => {
                        if (!d.sessions || !d.sessions.length) return "-";

                        return "Session " + d.sessions[0].session_order;
                    }
                },

                {
                    caption: "Last",
                    calculateCellValue: d => {
                        if (!d.sessions || !d.sessions.length) return "-";

                        return d.sessions[0].is_end ? "YES" : "NO";
                    }
                },

                {
                    caption: "Link Meeting",
                    calculateCellValue: d => d.link ? d.link : "-"
                },

                // =========================
                // VIDEO RECORD COLUMN
                // =========================
                {
                    caption: "Video Record",
                    width: 220,
                    cellTemplate(container, options) {

                        const d = options.data;

                        if (d.record_video) {

                            $("<a>")
                                .text("Lihat Video")
                                .attr("href", d.record_video)
                                .attr("target", "_blank")
                                .addClass("px-3 py-1 bg-green-500 text-white rounded inline-block")
                                .appendTo(container);

                        } else {

                            $("<span>")
                                .text("Belum Upload")
                                .addClass("text-gray-400 italic")
                                .appendTo(container);
                        }
                    }
                },

                {
                    caption: "Aksi",
                    width: 340,
                    cellTemplate(container, options) {

                        const d = options.data;

                        // =========================
                        // OPEN LINK
                        // =========================
                        if (d.link) {

                            $("<a>")
                                .text("Open")
                                .attr("href", d.link)
                                .attr("target", "_blank")
                                .addClass("px-3 py-1 bg-blue-500 text-white rounded mr-2 inline-block")
                                .appendTo(container);
                        }

                        // =========================
                        // UPLOAD VIDEO BUTTON
                        // =========================
                        $("<button>")
                            .text("Upload Record")
                            .addClass("px-3 py-1 bg-purple-600 text-white rounded mr-2")
                            .on("click", () => openUploadVideo(d))
                            .appendTo(container);

                        // =========================
                        // EDIT BUTTON
                        // =========================
                        $("<button>")
                            .text("Edit")
                            .addClass("px-3 py-1 bg-amber-500 text-white rounded mr-2")
                            .on("click", () => openEdit(d))
                            .appendTo(container);

                        // =========================
                        // DELETE BUTTON
                        // =========================
                        $("<button>")
                            .text("Hapus")
                            .addClass("px-3 py-1 bg-red-500 text-white rounded")
                            .on("click", () => destroyData(d.id))
                            .appendTo(container);
                    }
                }
            ]
        }).dxDataGrid("instance");

        function openCreate() {
            mode = "create";
            $('#modalTitle').text('Tambah Schedule');

            $('#schedule_id').val('');
            $('#title').val('');
            $('#date').val('');
            $('#start_time').val('');
            $('#end_time').val('');
            $('#link').val('');
            $('#session_order').val('');
            $('#is_last_session').prop('checked', false);

            $('#modalSchedule').removeClass('hidden');

            setTimeout(() => {
                initSelect2();
                $('#kelas_id').val('').trigger('change');
            }, 100);
        }

        function openEdit(d) {
            mode = "edit";
            $('#modalTitle').text('Edit Schedule');

            $('#schedule_id').val(d.id);
            $('#title').val(d.title);
            $('#date').val(d.date);
            $('#start_time').val(d.start_time ?? '');
            $('#end_time').val(d.end_time ?? '');
            $('#link').val(d.link ?? '');

            const firstSession = d.sessions && d.sessions.length ? d.sessions[0] : null;
            $('#session_order').val(firstSession ? firstSession.session_order : '');
            $('#is_last_session').prop('checked', firstSession ? !!firstSession.is_end : false);

            $('#modalSchedule').removeClass('hidden');

            setTimeout(() => {
                initSelect2();
                $('#kelas_id').val(d.kelas_id).trigger('change');
            }, 100);
        }

        function closeModal() {
            $('#modalSchedule').addClass('hidden');
        }


        function save() {
            const id = $('#schedule_id').val();

            const data = {
                title: $('#title').val(),
                kelas_id: $('#kelas_id').val(),
                date: $('#date').val(),
                start_time: $('#start_time').val(),
                end_time: $('#end_time').val(),
                link: $('#link').val(),
                session_order: $('#session_order').val(),
                is_last_session: $('#is_last_session').is(':checked') ? 1 : 0
            };

            showLoading();

            if (mode === "create") {
                $.post("/schedule", data)
                    .done(() => {
                        toastr.success("Schedule dibuat");
                        closeModal();
                        grid.refresh();
                    })
                    .fail(err => {
                        toastr.error(err.responseJSON?.message || "Gagal menyimpan schedule");
                    })
                    .always(() => {
                        hideLoading();
                    });

            } else {
                $.ajax({
                        url: "/schedule/" + id,
                        method: "PUT",
                        data: data
                    })
                    .done(() => {
                        toastr.success("Schedule diupdate");
                        closeModal();
                        grid.refresh();
                    })
                    .fail(err => {
                        toastr.error(err.responseJSON?.message || "Gagal update schedule");
                    })
                    .always(() => {
                        hideLoading();
                    });
            }
        }

        function destroyData(id) {
            if (!confirm("Hapus schedule ini?")) return;

            $.ajax({
                url: "/schedule/" + id,
                method: "DELETE"
            }).done(() => {
                toastr.success("Schedule dihapus");
                grid.refresh();
            }).fail(() => {
                toastr.error("Gagal menghapus schedule");
            });
        }

        // OPEN MODAL
        function openUploadVideo(data) {

            $("#schedule_id").val(data.id);

            $("#modalUploadVideo")
                .removeClass("hidden")
                .addClass("flex");
        }

        // CLOSE MODAL
        function closeUploadVideo() {

            $("#formUploadVideo")[0].reset();

            $("#modalUploadVideo")
                .removeClass("flex")
                .addClass("hidden");
        }

        // SUBMIT UPLOAD
        // SUBMIT UPLOAD
        $("#formUploadVideo").submit(function(e) {

            e.preventDefault();

            let id = $("#schedule_id").val();

            let formData = new FormData();

            formData.append(
                "record_video",
                $("#record_video")[0].files[0]
            );

            formData.append(
                "_token",
                $('meta[name="csrf-token"]').attr("content")
            );

            $.ajax({
                url: `/schedule/upload-record/${id}`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                beforeSend() {

                    toastr.info("Uploading video...");
                },

                success(res) {

                    toastr.success(res.message);

                    closeUploadVideo();

                    grid.refresh();
                },

                error(xhr) {

                    let msg = 'Upload gagal';

                    if (xhr.responseJSON?.message) {
                        msg = xhr.responseJSON.message;
                    }

                    toastr.error(msg);
                }
            });
        });
    </script>
</x-app-layout>