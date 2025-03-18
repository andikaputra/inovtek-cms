<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ config('app.name', 'Laravel') }} - @yield('title')</title>
    @include('errors.layouts._css')
</head>

<body>
    <div id="error">
        @yield('content')
    </div>
    @include('errors.layouts._js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const logoutLink = document.getElementById("logout-link");
            logoutLink.addEventListener("click", function(event) {
                event.preventDefault();
                const logoutForm = document.getElementById("logout-form");
                logoutForm.submit();
            });
        });
    </script>
</body>


</html>
