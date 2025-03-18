<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script src="{{ asset('assets/extensions/dragula/dragula.min.js') }}"></script>

{{-- Drag and Drop --}}
<script>
    let dragulaInstance; // Simpan instance Dragula secara global

    const loadDataMapbox = (button) => {
        const url_detail = $(button).data('url_mapbox');

        // Tampilkan spinner
        $("#loading-spinner").removeClass("d-none");

        // Sembunyikan daftar item
        $("#widget-todo-list").addClass("d-none");

        $.ajax({
            url: url_detail,
            type: "GET",
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    const listContainer = $("#widget-todo-list");
                    const buttonSubmit = $('#simpan-rute');

                    // Kosongkan daftar sebelumnya
                    listContainer.empty();

                    if (data.length <= 0) {
                        const listItem = `
                            <p class="text-center mt-3">
                              <b>Data Tidak Ditemukan</b>
                            </p>
                        `;
                        listContainer.append(listItem);
                        buttonSubmit.addClass('d-none')
                    } else {
                        // Tambahkan data ke dalam daftar
                        data.forEach((item, index) => {
                            const listItem = `
                                <li class="widget-todo-item border border-primary m-2" data-id="${item.id}">
                                    <div class="widget-todo-title-wrapper d-flex justify-content-between align-items-center mb-50">
                                        <div class="widget-todo-title-area d-flex align-items-center gap-2">
                                            <i class="bi bi-chevron-expand cursor-move"></i>
                                            <label class="widget-todo-title ms-2">
                                                <b>Titik Ke-${index + 1}</b>: ${item.name} (${item.latitude}, ${item.longitude})
                                            </label>
                                        </div>
                                    </div>
                                </li>
                            `;
                            listContainer.append(listItem);
                        });

                        // Hapus inisialisasi Dragula sebelumnya (jika ada)
                        if (dragulaInstance) {
                            dragulaInstance.destroy();
                        }

                        // Inisialisasi ulang Dragula
                        dragulaInstance = dragula([document.getElementById("widget-todo-list")], {
                            moves: function(e, a, t) {
                                return t.closest(
                                    '.widget-todo-item'
                                ); // Pastikan seluruh elemen parent bisa di-drag
                            },
                            invalid: function(el) {
                                return false; // Tidak ada elemen yang di-exclude
                            },
                        });

                        buttonSubmit.removeClass('d-none')
                    }
                } else {
                    console.log("Error: Data tidak ditemukan");
                }
            },
            error: function(xhr) {
                console.log("An error occurred: " + xhr.status + " " + xhr.statusText);
            },
            complete: function() {
                // Sembunyikan spinner dan tampilkan daftar
                $("#loading-spinner").addClass("d-none");
                $("#widget-todo-list").removeClass("d-none");
            },
        });
    };

    $("#loadDataMapboxButton").on('click', function() {
        console.log("OK");
        loadDataMapbox(this);
    });

    $("#simpan-rute").on("click", function(e) {
        e.preventDefault(); // Mencegah form submit default

        // Kumpulkan urutan item dari widget-todo-list
        const order = $("#widget-todo-list .widget-todo-item").map(function() {
            return $(this).data("id"); // Ambil data-id setiap elemen
        }).get(); // Ubah menjadi array

        // Masukkan data urutan ke dalam input hidden `route_order`
        $("#route_order").val(JSON.stringify(order));

        // Submit form secara manual
        $(this).closest("form").submit();
    });
</script>

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
                url: "{{ route('admin.home.detail.desa.segmentasi-mapbox.jalur.datatable', ['id_provinsi' => $findRegion->slug, 'id_desa' => $findDetailRegion->slug, 'id_mapbox' => $findDetailRegionMapbox->id]) }}",
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
                    name: 'DT_RowIndex'
                },
                {
                    data: "action",
                    orderable: false,
                    searchable: false,
                    name: 'action'
                },
                {
                    name: 'name',
                    data: 'name',
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
                }
            ],
            columnDefs: [{
                targets: [1, 2, 3, 4],
                className: 'dt-center'
            }],
            drawCallback: function(settings) {
                var totalRecords = settings.json.totalRecords;
                $('#desa-total').text(totalRecords);

                var totalActive = settings.json.totalActive;
                var totalInactive = settings.json.totalInactive;
                $('#active-total').text(totalActive);
                $('#inactive-total').text(totalInactive);

                const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
                const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap
                    .Tooltip(tooltipTriggerEl));
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
                        <li><b>Latitude & Longitude:</b> ${data.lat_long || 'Tidak Ada'}</li>
                    </ul>
                </div>
            </div>`;
        }
    });



    $(".reload").click(function() {
        dataTable.DataTable().ajax.reload();
    });

    $(document).ready(function() {
        // Mengambil data dari PHP ke JavaScript
        let points = @json($arr_map);

        // Inisialisasi peta dengan koordinat awal untuk titik tambahan
        var map = L.map('map').setView([-8.450518, 115.200919],
            7); // Sesuaikan koordinat awal sesuai titik tambahan

        // Tambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Tambahkan marker untuk titik tambahan dengan warna putih menggunakan circle marker
        var additionalPointLat = parseFloat("{{ $findDetailRegion->latitude }}");
        var additionalPointLng = parseFloat("{{ $findDetailRegion->longitude }}");
        var additionalPointName = "Desa {{ $findDetailRegion->village }}";

        // Menambahkan marker dengan circleMarker untuk titik tambahan
        var additionalMarker = L.circleMarker([additionalPointLat, additionalPointLng], {
            color: 'blue',
            fillColor: 'blue',
            fillOpacity: 1,
            radius: 8
        }).addTo(map);
        additionalMarker.bindPopup(`<b>${additionalPointName}</b>`).openPopup();

        // Array untuk menyimpan koordinat setiap titik dari data arr_map
        var coordinates = [];

        // Fungsi untuk menambahkan marker dengan popup
        const addMarker = (lat, lng, name) => {
            var marker = L.circleMarker([lat, lng], {
                color: 'orange',
                fillColor: 'orange',
                fillOpacity: 1,
                radius: 8
            }).addTo(map);
            marker.bindPopup(`<b>${name}</b>`)
            coordinates.push([lat, lng]); // Simpan koordinat ke dalam array
        }

        // Tambahkan marker untuk titik tambahan dengan warna putih menggunakan circle marker
        var additionalPointMapboxLat = parseFloat("{{ $findDetailRegionMapbox->latitude }}");
        var additionalPointMapboxLng = parseFloat("{{ $findDetailRegionMapbox->longitude }}");
        var additionalPointMapboxName = "{{ $findDetailRegionMapbox->name }}";
        var additionalPointMapboxMarker = L.marker([additionalPointMapboxLat, additionalPointMapboxLng]).addTo(
            map);
        additionalPointMapboxMarker.bindPopup(`<b>${additionalPointMapboxName}</b>`);
        coordinates.push([additionalPointMapboxLat,
            additionalPointMapboxLng
        ]); // Simpan koordinat ke dalam array

        // Loop melalui titik-titik pada arr_map dan tambahkan marker
        points.forEach(function(point) {
            addMarker(parseFloat(point.latitude), parseFloat(point.longitude), point.name);
        });

        // Buat polyline untuk menghubungkan semua titik dari arr_map, jika ada data
        if (coordinates.length > 0) {
            var polyline = L.polyline(coordinates, {
                color: 'blue'
            }).addTo(map);

            // Sesuaikan tampilan peta agar mencakup semua titik dalam polyline
            map.fitBounds(polyline.getBounds(), {
                padding: [50, 50], // Tambahkan padding untuk menghindari zoom berlebihan
                maxZoom: 15 // Batasi tingkat zoom maksimal
            });
        } else {
            // Fokuskan tampilan peta pada titik tambahan jika arr_map kosong
            map.setView([additionalPointLat, additionalPointLng],
                10); // Sesuaikan zoom level jika hanya satu titik
        }
    });
</script>
