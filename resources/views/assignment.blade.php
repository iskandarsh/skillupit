<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            📝 Data Assignment
        </h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- JQUERY --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    {{-- SELECT2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    {{-- DEVEXTREME --}}
    <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.5/css/dx.material.purple.light.css">
    <script src="https://cdn3.devexpress.com/jslib/23.2.5/js/dx.all.js"></script>

    {{-- TOASTR --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div class="bg-white rounded-2xl shadow p-5">
            <div id="gridAssignment"></div>
        </div>

    </div>

    {{-- MODAL ASSIGNMENT --}}
    <div id="modalAssignment"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">

        <div class="bg-white w-full max-w-2xl rounded-2xl p-6 space-y-4">

            <h3 id="modalTitle" class="font-bold text-lg"></h3>

            <input type="hidden" id="assignment_id">

            <div>
                <label class="block text-sm font-semibold mb-1">
                    Session
                </label>

                <select id="class_session_id"
                    class="w-full border rounded-xl p-2">

                    <option value="">
                        Pilih Session
                    </option>

                    @foreach($sessions as $s)
                    <option value="{{ $s->id }}">
                        {{ $s->schedule->kelas->nama_kelas ?? '-' }}
                        -
                        {{ $s->title }}
                    </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">
                    Title
                </label>

                <input type="text"
                    id="title"
                    class="w-full border rounded-xl p-2">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">
                    Description
                </label>

                <textarea id="description"
                    rows="5"
                    class="w-full border rounded-xl p-2"></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">
                    Deadline
                </label>

                <input type="datetime-local"
                    id="deadline"
                    class="w-full border rounded-xl p-2">
            </div>

            <div class="flex justify-end gap-2 pt-3">

                <button onclick="closeModal()"
                    class="px-4 py-2 border rounded-xl">
                    Batal
                </button>

                <button onclick="save()"
                    class="px-4 py-2 bg-indigo-600 text-white rounded-xl">
                    Simpan
                </button>

            </div>

        </div>
    </div>

    {{-- MODAL SUBMISSION --}}
    <div id="modalSubmission"
        class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4 overflow-y-auto">

        <div class="bg-white w-full max-w-6xl rounded-2xl p-6">

            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold">
                    📚 Submission Assignment
                </h3>

                <button onclick="closeSubmission()"
                    class="text-gray-500 hover:text-red-500 text-xl">
                    ✕
                </button>
            </div>

            <div id="submissionContent"></div>

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
            timeOut: 2000,
            closeButton: true,
            progressBar: true
        };

        let mode = 'create';

        $('#class_session_id').select2({
            dropdownParent: $('#modalAssignment'),
            width: '100%'
        });

        const store = new DevExpress.data.CustomStore({
            key: "id",
            load: () => $.getJSON('/assignments')
        });

        const grid = $("#gridAssignment").dxDataGrid({

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
                    widget: "dxButton",
                    options: {
                        text: "Tambah Assignment",
                        icon: "add",
                        onClick: openCreate
                    }
                }]
            },

            columns: [{
                    dataField: "title",
                    caption: "Title"
                },
                {
                    dataField: "description",
                    caption: "Description"
                },
                {
                    caption: "Session",
                    calculateCellValue: d => d.session?.title || '-'
                },
                {
                    caption: "Kelas",
                    calculateCellValue: d =>
                        d.session?.schedule?.kelas?.nama_kelas || '-'
                },
                {
                    dataField: "deadline",
                    caption: "Deadline"
                },
                {
                    caption: "Submitted",
                    width: 120,
                    alignment: "center",
                    cellTemplate: function(container, options) {

                        const submitted = options.data.submitted || 0;

                        const badge = submitted > 0 ?
                            `
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">
                                ${submitted} Submitted
                            </span>
                        ` :
                            `
                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">
                                0 Submitted
                            </span>
                        `;

                        $(badge).appendTo(container);
                    }
                },
                {
                    caption: "Aksi",
                    width: 280,

                    cellTemplate: function(container, options) {

                        const d = options.data;

                        $("<button>")
                            .text("Edit")
                            .addClass("bg-amber-500 text-white px-3 py-1 mr-2 rounded-lg text-sm")
                            .on("click", () => openEdit(d))
                            .appendTo(container);

                        $("<button>")
                            .text("Submission")
                            .addClass("bg-indigo-600 text-white px-3 py-1 mr-2 rounded-lg text-sm")
                            .on("click", () => openSubmission(d.id))
                            .appendTo(container);

                        $("<button>")
                            .text("Hapus")
                            .addClass("bg-red-500 text-white px-3 py-1 rounded-lg text-sm")
                            .on("click", () => destroy(d.id))
                            .appendTo(container);
                    }
                }
            ]

        }).dxDataGrid("instance");

        function openCreate() {

            mode = 'create';

            $('#modalTitle').text('Tambah Assignment');

            $('#assignment_id').val('');

            $('#class_session_id')
                .val('')
                .trigger('change');

            $('#title').val('');
            $('#description').val('');
            $('#deadline').val('');

            $('#modalAssignment').removeClass('hidden');

            toastr.info('Mode tambah assignment');
        }

        function openEdit(d) {

            mode = 'edit';

            $('#modalTitle').text('Edit Assignment');

            $('#assignment_id').val(d.id);

            $('#class_session_id')
                .val(d.class_session_id)
                .trigger('change');

            $('#title').val(d.title || '');
            $('#description').val(d.description || '');

            if (d.deadline) {

                $('#deadline').val(
                    d.deadline.replace(' ', 'T')
                );
            }

            $('#modalAssignment').removeClass('hidden');

            toastr.info('Mode edit assignment');
        }

        function closeModal() {

            $('#modalAssignment').addClass('hidden');

            toastr.warning('Modal ditutup');
        }

        function save() {

            const id = $('#assignment_id').val();

            const data = {
                class_session_id: $('#class_session_id').val(),
                title: $('#title').val(),
                description: $('#description').val(),
                deadline: $('#deadline').val(),
            };

            if (!data.class_session_id ||
                !data.title ||
                !data.deadline) {

                toastr.error('Harap lengkapi data');

                return;
            }

            if (mode === 'create') {

                $.post('/assignments', data)

                    .done(() => {

                        toastr.success('Berhasil tambah assignment');

                        closeModal();

                        grid.refresh();
                    })

                    .fail(err => {

                        toastr.error(
                            err.responseJSON?.message ||
                            'Gagal menyimpan'
                        );
                    });

            } else {

                data._method = 'PUT';

                $.post('/assignments/' + id, data)

                    .done(() => {

                        toastr.success('Berhasil update assignment');

                        closeModal();

                        grid.refresh();
                    })

                    .fail(err => {

                        toastr.error(
                            err.responseJSON?.message ||
                            'Gagal update'
                        );
                    });
            }
        }

        function destroy(id) {

            if (!confirm('Yakin hapus assignment?')) {
                return;
            }

            $.ajax({
                    url: '/assignments/' + id,
                    method: 'DELETE'
                })

                .done(() => {

                    toastr.success('Berhasil hapus assignment');

                    grid.refresh();
                })

                .fail(() => {

                    toastr.error('Gagal hapus assignment');
                });
        }

        function openSubmission(id) {

            $('#modalSubmission').removeClass('hidden');

            $('#submissionContent').html(`
                <div class="flex justify-center py-10">
                    <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600"></div>
                </div>
            `);

            toastr.info('Memuat data submission...');

            $.get('/assignments/' + id + '/submissions')

                .done(res => {

                    let html = '';

                    if (res.length === 0) {

                        html = `
                            <div class="text-center py-10 text-gray-500">
                                Belum ada submission
                            </div>
                        `;

                    } else {

                        html += `
                            <div class="overflow-auto">
                                <table class="w-full text-sm border">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="p-3 border">Nama</th>
                                            <th class="p-3 border">Title</th>
                                            <th class="p-3 border">Description</th>
                                            <th class="p-3 border">File</th>
                                            <th class="p-3 border">Nilai</th>
                                            <th class="p-3 border">Feedback</th>
                                            <th class="p-3 border">Status</th>
                                            <th class="p-3 border">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                        res.forEach(item => {

                            html += `
                                <tr>

                                    <td class="p-3 border">
                                        ${item.user?.name ?? '-'}
                                    </td>

                                    <td class="p-3 border">
                                        ${item.title ?? '-'}
                                    </td>

                                    <td class="p-3 border">
                                        ${item.description ?? '-'}
                                    </td>

                                    <td class="p-3 border text-center">
                                        <a href="/storage/${item.file}"
                                            target="_blank"
                                            class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs">
                                            Download
                                        </a>
                                    </td>

                                    <td class="p-3 border">
                                        <input type="number"
                                            id="score_${item.id}"
                                            value="${item.score ?? ''}"
                                            class="w-24 border rounded-lg px-2 py-1">
                                    </td>

                                    <td class="p-3 border">
                                        <textarea
                                            id="feedback_${item.id}"
                                            class="w-full border rounded-lg px-2 py-1"
                                            rows="2">${item.feedback ?? ''}</textarea>
                                    </td>

                                    <td class="p-3 border">
                                        <select
                                            id="status_${item.id}"
                                            class="border rounded-lg px-2 py-1">

                                            <option value="submitted"
                                                ${item.status == 'submitted' ? 'selected' : ''}>
                                                Submitted
                                            </option>

                                            <option value="reviewed"
                                                ${item.status == 'reviewed' ? 'selected' : ''}>
                                                Reviewed
                                            </option>

                                            <option value="revision"
                                                ${item.status == 'revision' ? 'selected' : ''}>
                                                Revision
                                            </option>

                                        </select>
                                    </td>

                                    <td class="p-3 border text-center">

                                        <button
                                            onclick="saveReview(${item.id})"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs">
                                            Simpan
                                        </button>

                                    </td>

                                </tr>
                            `;
                        });

                        html += `
                                    </tbody>
                                </table>
                            </div>
                        `;
                    }

                    $('#submissionContent').html(html);

                    toastr.success('Data submission berhasil dimuat');
                })

                .fail(() => {

                    $('#submissionContent').html(`
                        <div class="text-center py-10 text-red-500">
                            Gagal load submission
                        </div>
                    `);

                    toastr.error('Gagal mengambil submission');
                });
        }

        function saveReview(id) {

            const data = {
                score: $('#score_' + id).val(),
                feedback: $('#feedback_' + id).val(),
                status: $('#status_' + id).val(),
            };

            $.ajax({
                    url: '/submission/' + id + '/review',
                    method: 'PUT',
                    data: data
                })

                .done(() => {

                    toastr.success('Berhasil simpan review');
                })

                .fail(() => {

                    toastr.error('Gagal simpan review');
                });
        }

        function closeSubmission() {

            $('#modalSubmission').addClass('hidden');

            toastr.warning('Modal submission ditutup');
        }
    </script>
</x-app-layout>