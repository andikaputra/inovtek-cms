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
                url: "{{ route('admin.short-link.datatable') }}",
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
                    data: "action",
                    orderable: false,
                    searchable: false,
                    name: 'action',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'original_url',
                    data: 'original_url',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'short_url',
                    data: 'short_url',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'status',
                    data: 'status',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'updated_at',
                    data: 'updated_at',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
            ],
            columnDefs: [],
            drawCallback: function(settings) {
                var totalRecords = settings.json.totalRecords;
                $('#short-link-total').text(totalRecords);

                var totalActive = settings.json.totalActive;
                var totalInactive = settings.json.totalInactive;
                $('#active-total').text(totalActive);
                $('#inactive-total').text(totalInactive);

                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap
                    .Tooltip(tooltipTriggerEl))
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
                        <li><b>Total Klik:</b> ${data.click_count || 0 }</li>
                        <li><b>QR Code:</b> ${data.qr_code || 'Tidak Ada'}</li>
                    </ul>
                </div>
            </div>`;
        }
    });

    $(".reload").click(function() {
        dataTable.DataTable().ajax.reload();
    });
</script>
