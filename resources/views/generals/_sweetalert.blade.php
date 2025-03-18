<script>
    $(document).ready(function() {
        // Check for successful message
        const success = $(".berhasil").data("berhasil");
        if (success) {
            toastr.options.closeButton = true;
            toastr.options.timeOut = 3000;
            toastr.options.extendedTimeOut = 1000;
            toastr.options.progressBar = true;
            toastr.success(success);
        }

        // Check for error message
        const gagal = $(".gagal").data("gagal");
        if (gagal) {
            toastr.options.closeButton = true;
            toastr.options.timeOut = 3000;
            toastr.options.extendedTimeOut = 1000;
            toastr.options.progressBar = true;
            toastr.error(gagal);
        }
    });

    $(document).ready(function() {
        $(document).on('click', '.btn-alert-confirm', function() {
            const id = $(this).data('id');

            swal.fire({
                title: "Apakah Yakin?",
                text: "Data akan dihapus permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus Data",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#delete-' + id).submit(); // Kirimkan form untuk menghapus data
                } else {
                    swal.fire("Batal Dihapus",
                        "{{ trans('response.error.delete', ['data' => 'dipilih', 'error' => 'aksi dibatalkan user']) }}",
                        "success");
                }
            });
        });

        $(document).on('click', '.btn-change-status', function() {
            const id = $(this).data('id');

            swal.fire({
                title: "Apakah Yakin?",
                text: "Data akan diubah statusnya",
                icon: "question",
                showCancelButton: true,
                confirmButtonText: "Ubah Status",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#change-' + id).submit();
                } else {
                    swal.fire("Batal Diubah",
                        "{{ trans('response.error.delete', ['data' => 'dipilih', 'error' => 'aksi dibatalkan user']) }}",
                        "success");
                }
            });
        });
    });


    const alertConfirm = (button) => {
        const id = $(button).data('id');
        swal.fire({
            title: "Apakah Yakin?",
            text: "Data akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Hapus Data",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $('#delete-' + id).submit();
            } else {
                swal.fire("Batal Dihapus",
                    "{{ trans('response.error.delete', ['data' => 'dipilih', 'error' => 'aksi dibatalkan user']) }}",
                    "success");
            }
        });
    }

    const changeStatus = (button) => {
        const id = $(button).data('id');
        swal.fire({
            title: "Apakah Yakin?",
            text: "Data akan diubah statusnya",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Ubah Status",
            cancelButtonText: "Batal",
        }).then((result) => {
            if (result.isConfirmed) {
                $('#change-' + id).submit();
            } else {
                swal.fire("Batal Diubah",
                    "{{ trans('response.error.delete', ['data' => 'dipilih', 'error' => 'aksi dibatalkan user']) }}",
                    "success");
            }
        });
    }
</script>
