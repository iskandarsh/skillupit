<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800">
            🧑‍🏫 Data Mentor
        </h2>
    </x-slot>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <link rel="stylesheet" href="https://cdn3.devexpress.com/jslib/23.2.5/css/dx.material.purple.light.css">
    <script src="https://cdn3.devexpress.com/jslib/23.2.5/js/dx.all.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-2xl shadow p-5">
            <div id="gridMentor"></div>
        </div>
    </div>

    <div id="modalMentor" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 px-4">
        <div class="bg-white w-full max-w-lg rounded-2xl p-6 space-y-4 max-h-[95vh] overflow-y-auto">
            <h3 id="modalTitle" class="font-bold text-lg"></h3>

            <input type="hidden" id="mentor_id">
            <input type="hidden" id="old_photo">

            <div>
                <label class="block text-sm font-semibold mb-1">Nama Mentor</label>
                <input type="text" id="name" class="w-full border rounded-xl p-2">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Email</label>
                <input type="email" id="email" class="w-full border rounded-xl p-2">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Job Title</label>
                <input type="text" id="job_title" class="w-full border rounded-xl p-2" placeholder="Contoh: Laravel Developer">
            </div>
            <div>
                <label class="block text-sm font-semibold mb-1">Instagram URL</label>
                <input type="url" id="instagram_url" class="w-full border rounded-xl p-2"
                    placeholder="https://instagram.com/username">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">LinkedIn URL</label>
                <input type="url" id="linkedin_url" class="w-full border rounded-xl p-2"
                    placeholder="https://linkedin.com/in/username">
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Bio</label>
                <textarea id="bio" rows="4" class="w-full border rounded-xl p-2" placeholder="Bio mentor..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Foto Mentor</label>
                <input type="file" id="photo" accept="image/*" class="w-full border rounded-xl p-2 bg-white">

                <div id="photo_preview_wrap" class="mt-3 hidden">
                    <img id="photo_preview" src="" class="w-28 h-28 object-cover rounded-xl border">
                </div>

                <div id="current_photo_wrap" class="mt-3 hidden">
                    <p class="text-xs font-semibold text-gray-500 mb-2">Foto Saat Ini</p>
                    <img id="current_photo" src="" class="w-28 h-28 object-cover rounded-xl border">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold mb-1">Kelas (Multi)</label>
                <select id="kelas_ids" multiple class="w-full">
                    @foreach($kelas as $k)
                    <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-xl">Batal</button>
                <button type="button" onclick="save()" class="px-4 py-2 bg-indigo-600 text-white rounded-xl">Simpan</button>
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
        let selectedPhoto = null;

        function initSelect2() {
            if ($('#kelas_ids').hasClass("select2-hidden-accessible")) {
                $('#kelas_ids').select2('destroy');
            }

            $('#kelas_ids').select2({
                dropdownParent: $('#modalMentor'),
                width: '100%',
                theme: 'bootstrap-5',
                placeholder: "Pilih Kelas"
            });
        }

        function resetPhotoPreview() {
            selectedPhoto = null;
            $('#photo').val('');
            $('#photo_preview_wrap').addClass('hidden');
            $('#photo_preview').attr('src', '');
        }

        $('#photo').on('change', function(e) {
            const file = e.target.files[0];
            if (!file) return;

            selectedPhoto = file;

            const reader = new FileReader();
            reader.onload = function(ev) {
                $('#photo_preview').attr('src', ev.target.result);
                $('#photo_preview_wrap').removeClass('hidden');
            };
            reader.readAsDataURL(file);
        });

        const store = new DevExpress.data.CustomStore({
            key: "id",
            load: () => $.getJSON("/mentor")
        });

        const grid = $("#gridMentor").dxDataGrid({
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
                        text: "Tambah Mentor",
                        icon: "add",
                        onClick: openCreate
                    }
                }]
            },
            columns: [{
                    caption: "Foto",
                    width: 90,
                    alignment: "center",
                    cellTemplate: function(container, options) {
                        const photo = options.data.photo;

                        if (photo) {
                            $("<img>")
                                .attr("src", "/storage/" + photo)
                                .addClass("w-12 h-12 rounded-full object-cover border")
                                .appendTo(container);
                        } else {
                            $("<div>")
                                .addClass("w-12 h-12 rounded-full bg-gray-100 flex items-center justify-center text-gray-400")
                                .text("—")
                                .appendTo(container);
                        }
                    }
                },
                {
                    dataField: "name",
                    caption: "Nama"
                },
                {
                    dataField: "email",
                    caption: "Email"
                },
                {
                    dataField: "job_title",
                    caption: "Job Title"
                },
                {
                    dataField: "instagram_url",
                    caption: "Instagram",
                    cellTemplate: function(container, options) {
                        const url = options.data.instagram_url;
                        if (url) {
                            $("<a>")
                                .attr("href", url)
                                .attr("target", "_blank")
                                .addClass("text-pink-500 underline")
                                .text("IG")
                                .appendTo(container);
                        } else {
                            $("<span>").text("-").appendTo(container);
                        }
                    }
                },
                {
                    dataField: "linkedin_url",
                    caption: "LinkedIn",
                    cellTemplate: function(container, options) {
                        const url = options.data.linkedin_url;
                        if (url) {
                            $("<a>")
                                .attr("href", url)
                                .attr("target", "_blank")
                                .addClass("text-blue-600 underline")
                                .text("LinkedIn")
                                .appendTo(container);
                        } else {
                            $("<span>").text("-").appendTo(container);
                        }
                    }
                },
                {
                    dataField: "bio",
                    caption: "Bio"
                },
                {
                    caption: "Kelas",
                    calculateCellValue: d => (d.kelas || []).map(k => k.nama_kelas).join(', ') || '-'
                },
                {
                    caption: "Aksi",
                    width: 180,
                    cellTemplate: function(container, options) {
                        const d = options.data;

                        $("<button>")
                            .text("Edit")
                            .addClass("bg-amber-500 text-white px-2 py-1 mr-2 rounded")
                            .on("click", () => openEdit(d))
                            .appendTo(container);

                        $("<button>")
                            .text("Hapus")
                            .addClass("bg-red-500 text-white px-2 py-1 rounded")
                            .on("click", () => destroy(d.id))
                            .appendTo(container);
                    }
                }
            ]
        }).dxDataGrid("instance");

        function openCreate() {
            mode = "create";
            $('#modalTitle').text('Tambah Mentor');
            $('#mentor_id').val('');
            $('#name').val('');
            $('#email').val('');
            $('#job_title').val('');
            $('#bio').val('');
            $('#old_photo').val('');
            $('#instagram_url').val('');
            $('#linkedin_url').val('');
            $('#current_photo_wrap').addClass('hidden');
            $('#current_photo').attr('src', '');

            resetPhotoPreview();

            $('#modalMentor').removeClass('hidden');
            setTimeout(initSelect2, 100);
            $('#kelas_ids').val([]).trigger('change');
        }

        function openEdit(d) {
            mode = "edit";
            $('#modalTitle').text('Edit Mentor');
            $('#mentor_id').val(d.id);
            $('#name').val(d.name || '');
            $('#email').val(d.email || '');
            $('#job_title').val(d.job_title || '');
            $('#bio').val(d.bio || '');
            $('#old_photo').val(d.photo || '');
            $('#instagram_url').val(d.instagram_url || '');
            $('#linkedin_url').val(d.linkedin_url || '');
            resetPhotoPreview();

            if (d.photo) {
                $('#current_photo').attr('src', '/storage/' + d.photo);
                $('#current_photo_wrap').removeClass('hidden');
            } else {
                $('#current_photo_wrap').addClass('hidden');
                $('#current_photo').attr('src', '');
            }

            $('#modalMentor').removeClass('hidden');

            setTimeout(() => {
                initSelect2();
                $('#kelas_ids').val((d.kelas || []).map(k => k.id)).trigger('change');
            }, 100);
        }

        function closeModal() {
            $('#modalMentor').addClass('hidden');
        }

        function save() {
            const id = $('#mentor_id').val();

            const formData = new FormData();
            formData.append('name', $('#name').val());
            formData.append('email', $('#email').val());
            formData.append('job_title', $('#job_title').val());
            formData.append('bio', $('#bio').val());
            formData.append('instagram_url', $('#instagram_url').val());
            formData.append('linkedin_url', $('#linkedin_url').val());
            const kelasIds = $('#kelas_ids').val() || [];
            kelasIds.forEach(k => formData.append('kelas_ids[]', k));

            if (selectedPhoto) {
                formData.append('photo', selectedPhoto);
            }

            if (mode === "create") {
                $.ajax({
                    url: "/mentor",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false
                }).done(() => {
                    toastr.success("Berhasil tambah mentor");
                    closeModal();
                    grid.refresh();
                }).fail(err => {
                    toastr.error(err.responseJSON?.message || 'Gagal menyimpan');
                });
            } else {
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "/mentor/" + id,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false
                }).done(() => {
                    toastr.success("Berhasil update mentor");
                    closeModal();
                    grid.refresh();
                }).fail(err => {
                    toastr.error(err.responseJSON?.message || 'Gagal update');
                });
            }
        }

        function destroy(id) {
            if (!confirm("Yakin hapus?")) return;

            $.ajax({
                url: "/mentor/" + id,
                method: "DELETE"
            }).done(() => {
                toastr.success("Berhasil hapus");
                grid.refresh();
            });
        }
    </script>
</x-app-layout>