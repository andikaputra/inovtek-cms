<script src="{{ asset('assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
<script>
    var dataTable = $('#dataTable');
    $(document).ready(function() {
        var table = dataTable.DataTable({
            processing: true,
            serverSide: true,
            lengthMenu: [
                [15, 25, 50, 100, -1],
                [15, 25, 50, 100, "All"]
            ],
            language: {
                Search: "Cari: ",
            },
            ajax: {
                url: "{{ route('admin.home.detail.kuis.registrant.datatable', ['id_provinsi' => $findRegion->slug, 'id' => $findKuis->id]) }}",
                data: function(d) {
                    d.date_range = $('#filter-date-range').val();
                    d.village_id = $('#select2-region').val();
                    d.search_general = $('#filter-search').val();
                },
                dataSrc: function(json) {
                    json.totalRecords = json.recordsTotal;
                    return json.data;
                }
            },
            columns: [{
                    data: null,
                    className: 'dt-center',
                    orderable: false,
                    searchable: false,
                    defaultContent: '<i class="bi bi-plus-circle toggle-icon"></i>',
                    width: "5%"
                },
                {
                    data: "DT_RowIndex",
                    orderable: false,
                    searchable: false,
                    name: 'DT_RowIndex',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'name',
                    data: 'name',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'email',
                    data: 'email',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'phone_no',
                    data: 'phone_no',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'village',
                    data: 'village',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'created_at',
                    data: 'created_at',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
            ],
            columnDefs: [],
            drawCallback: function(settings) {
                var totalRecords = settings.json.totalRecords;
                $('#pendaftaran-total').text(totalRecords);
            }
        });

        // Fungsi untuk menambahkan baris collapse tambahan
        $('#dataTable tbody').on('click', 'td .toggle-icon', function(e) {
            e.preventDefault();
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var icon = $(this);

            if (row.child.isShown()) {
                // Tutup baris collapse jika sudah terbuka
                row.child.hide();
                tr.removeClass('shown');
                icon.removeClass('bi-dash-circle').addClass('bi-plus-circle');
            } else {
                // Buka baris collapse dan tampilkan informasi tambahan
                row.child(format(row.data())).show();
                tr.addClass('shown');
                icon.removeClass('bi-plus-circle').addClass('bi-dash-circle');
            }
        });

        // Fungsi untuk format data collapse
        function format(data) {
            return `
            <div class="row">
                <div class="col-md-12">
                    <ul>
                        <li><b>Jenis Kelamin:</b> ${data.sex_type || 'Tidak Ada'}</li>
                        <li><b>Umur:</b> ${data.age || 'Tidak Ada'}</li>
                        <li><b>Pekerjaan:</b> ${data.work || 'Tidak Ada'}</li>
                        <li><b>Kode Kuis:</b> ${data.quiz_code || 'Tidak Ada'}</li>
                    </ul>
                </div>
            </div>`;
        }
    });

    $("#filterRegistrasi").on('click', function() {
        filterRegistrasi(this);
    });

    $("#resetFilterRegistrasi").on('click', function() {
        resetFilterRegistrasi(this);
    });

    const filterRegistrasi = () => {
        $('#resetFilterRegistrasi').removeClass('d-none');
        $("#export-date-range").val($('#filter-date-range').val());
        $("#export-village-id").val($('#select2-region').val());
        $("#export-search").val($('#filter-search').val());
        dataTable.DataTable().ajax.reload();
    }

    const resetFilterRegistrasi = () => {
        $('#filter-date-range').val(null);
        $('#select2-region').val(null);
        $('#filter-search').val(null);

        $("#export-date-range").val(null);
        $("#export-village-id").val(null);
        $("#export-search").val(null);

        $('#resetFilterRegistrasi').addClass('d-none');
        dataTable.DataTable().ajax.reload();
    }

    $(".reload").click(function() {
        dataTable.DataTable().ajax.reload();
    });

    flatpickr('.flatpickr-range', {
        dateFormat: "Y/m/d",
        mode: 'range'
    })

    $(document).ready(function() {
        $('#select2-region').select2({
            width: '100%',
            placeholder: '--Pilih Daftar Wilayah--',
            allowClear: true,
        });
    });
</script>
