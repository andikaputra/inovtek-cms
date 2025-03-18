<script>
    var dataTable = $('#dataTable');
    $(document).ready(function() {
        dataTable.DataTable({
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
                url: "{{ route('admin.home.detail.blog.datatable', $findRegion->slug) }}",
                dataSrc: function(json) {
                    json.totalRecords = json.recordsTotal;
                    return json.data;
                }
            },
            columns: [{
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
                    name: 'title',
                    data: 'title',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'sub_title',
                    data: 'sub_title',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'content',
                    data: 'content',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'tag',
                    data: 'tag',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'is_general_blog',
                    data: 'is_general_blog',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                {
                    name: 'is_active',
                    data: 'is_active',
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
                $('#blog-total').text(totalRecords);

                var totalActive = settings.json.totalActive;
                var totalInactive = settings.json.totalInactive;
                $('#active-total').text(totalActive);
                $('#inactive-total').text(totalInactive);

                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap
                    .Tooltip(tooltipTriggerEl));
            }
        });
    });

    $(".reload").click(function() {
        dataTable.DataTable().ajax.reload();
    });
</script>
