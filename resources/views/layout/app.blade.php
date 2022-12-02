<!DOCTYPE html>

<head>
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}" />
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
  <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
  <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script src="{{ asset('/js/custom-validation.js') }}" type="text/javascript"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://www.paypal.com/sdk/js?client-id=AfDO_OUsGVSGHL1BvYd0Q11DZKl_XYhbz0CbGyazqChwCPNsSXdavxxyyYCl1jaXvfBtnkezGN95ojOy&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script>

  <!-- paypal subscription api intrigation -->
  <script src="https://www.paypal.com/sdk/js?client-id=AfDO_OUsGVSGHL1BvYd0Q11DZKl_XYhbz0CbGyazqChwCPNsSXdavxxyyYCl1jaXvfBtnkezGN95ojOy&vault=true&intent=subscription">
  </script>

  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->

  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
  <div class="loading loader" style="display: none;">Loading&#8230;</div>
  <div class="header">
    <div class="header-right">
      <a class="active" href="{{ route('register') }}">Register</a>
      <a href="{{ route('user-listing') }}">User Listing</a>
      <a href="{{ route('checkout-view') }}">Stripe</a>
      <a href="{{ route('paypal-checkout-view') }}">Paypal</a>
      @if(Auth::check())
        <a href="{{ route('logout') }}">Logout</a>
      @endif
    </div>
  </div>
  @yield('content')
  <script>
    $('.alert').delay(3000).fadeOut(350);
  </script>
  @stack('extra-javascript')
</body>

</html>