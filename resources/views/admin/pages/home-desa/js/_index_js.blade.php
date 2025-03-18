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
                url: "{{ route('admin.home.detail.desa.datatable', $findRegion->slug) }}",
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
                    name: 'village',
                    data: 'village',
                    render: function(d) {
                        return d == null ? 'Tidak Ada' : d;
                    }
                },
                @if ($findRegion->existingApps && $findRegion->existingApps->isNotEmpty())
                    @php $arr_existing_app = $findRegion->existingApps->pluck('code')->toArray();@endphp
                    @if (in_array(\App\Constants\AppConst::CODE_EXISTING_APP['02'], $arr_existing_app))
                        {
                            name: 'lat_long',
                            data: 'lat_long',
                            render: function(d) {
                                return d == null ? 'Tidak Ada' : d;
                            }
                        }, {
                            name: 'map_url',
                            data: 'map_url',
                            render: function(d) {
                                return d == null ? 'Tidak Ada' : d;
                            }
                        },
                    @endif
                @endif {
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
                $('#desa-total').text(totalRecords);

                var totalActive = settings.json.totalActive;
                var totalInactive = settings.json.totalInactive;
                $('#active-total').text(totalActive);
                $('#inactive-total').text(totalInactive);

                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap
                    .Tooltip(tooltipTriggerEl))
            }
        });
    });

    $(".reload").click(function() {
        dataTable.DataTable().ajax.reload();
    });
</script>
