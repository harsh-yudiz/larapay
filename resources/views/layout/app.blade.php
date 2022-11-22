<!DOCTYPE html>

<head>
    <link rel="stylesheet" type="text/css" href="{{ asset('/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}" />
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
    <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="{{ asset('/js/custom-validation.js') }}" type="text/javascript"></script>
</head>

<body>
    <div class="loading loader" style="display: none;">Loading&#8230;</div>
    @yield('content')
    <script>
        $('.alert').delay(3000).fadeOut(350);
    </script>
    @stack('extra-javascript')
</body>

</html>