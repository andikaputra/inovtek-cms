<script>
    $(document).ready(function() {
        $('#select2-produk').select2({
            width: '100%',
            placeholder: '--Pilih Produk--',
            allowClear: true,
        });
    });

    const checkConfirmDelete = (button) => {
        const textConfirm = $(button).data('validation');
        const value = $(button).val();

        if (textConfirm == value) {
            $('#buttonHapusData').attr('disabled', false);
        } else {
            $('#buttonHapusData').attr('disabled', true);
        }
    }
</script>
