@include('generals._lfm_js')
<script>
    $(document).ready(function() {
        $('#select2-region').select2({
            width: '100%',
            placeholder: '--Pilih Daftar Wilayah--',
            allowClear: true,
        });
    });
</script>
