<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    $(document).ready(function() {
        let loadLatitude = "{{ $findDetailRegionMapbox->latitude }}";
        let loadLongitude = "{{ $findDetailRegionMapbox->longitude }}";

        // Inisialisasi peta dengan koordinat awal
        var map = L.map('map').setView([loadLatitude, loadLongitude],
            13); // Ganti koordinat sesuai lokasi default

        // Tambahkan tile layer dari OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap'
        }).addTo(map);

        // Marker untuk menunjukkan lokasi yang dipilih
        var marker;

        // Fungsi untuk update marker dan input latitude-longitude
        function updateMarker(lat, lng) {
            // Jika marker sudah ada, hapus marker lama
            if (marker) {
                map.removeLayer(marker);
            }

            // Tambahkan marker baru pada lokasi yang ditentukan
            marker = L.marker([lat, lng]).addTo(map);

            // Update input latitude dan longitude
            $('#lat_long').val(`${lat}, ${lng}`);
        }

        // Event listener untuk klik pada peta
        map.on('click', function(e) {
            var lat = e.latlng.lat;
            var lng = e.latlng.lng;

            // Perbarui marker dan input
            updateMarker(lat, lng);
        });

        // Event listener untuk input manual di latitude dan longitude
        $('#lat_long').on('input', function() {
            var latLng = $(this).val().split(',');

            // Periksa apakah formatnya valid
            if (latLng.length === 2) {
                var lat = parseFloat($.trim(latLng[0]));
                var lng = parseFloat($.trim(latLng[1]));

                // Cek apakah lat dan lng adalah angka yang valid
                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <=
                    180) {
                    // Pindahkan peta dan perbarui marker sesuai input
                    map.setView([lat, lng], 13);
                    updateMarker(lat, lng);
                    $('#lat_long').removeClass(
                        'is-invalid'); // Menghapus tanda invalid jika input valid
                } else {
                    $('#lat_long').addClass('is-invalid'); // Menambahkan tanda invalid jika tidak valid
                }
            } else {
                $('#lat_long').addClass(
                    'is-invalid'); // Menambahkan tanda invalid jika format tidak sesuai
            }
        });

        // Set Default Load
        updateMarker(loadLatitude, loadLongitude);
    });
</script>
