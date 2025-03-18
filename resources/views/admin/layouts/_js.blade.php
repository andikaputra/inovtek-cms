<!-- Jquery -->
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>

<!-- Internal Js -->
<script src="{{ asset('assets/js/initTheme.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>

<!-- Datatable -->
<script src="https://cdn.datatables.net/v/bs5/dt-1.12.1/datatables.min.js"></script>

{{-- Select 2 --}}
<script src="{{ asset('assets/extensions/select2/js/select2.min.js') }}"></script>

<!-- Sweetalert -->
<script src="{{ asset('assets/extensions/sweetalert2/sweetalert2.min.js') }}"></script>

{{-- Toast --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@include('generals._sweetalert')

@yield('customJs')

<script>
    $(document).ready(function() {
        $("#notification-link").on("click", function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('admin.home.readNotification') }}",
                type: "GET",
                success: function(response) {
                    console.log(response.notifications);
                },
                error: function(xhr) {
                    console.error("An error occurred: " + xhr.status + " " + xhr
                        .statusText);
                }
            });
        });
    });


    $(document).ready(function() {
        $("#logout-link").on("click", function(event) {
            event.preventDefault();
            $("#logout-form").submit();
        });
    });
</script>
