<!DOCTYPE html>

<head>
  <link rel="stylesheet" type="text/css" href="{{ asset('/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('/css/bootstrap.min.css') }}" />

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">


  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

  <!-- paypal subscription api intrigation -->
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- Ensures optimal rendering on mobile devices. -->

  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
  <div class="loading loader" style="display: none;">Loading&#8230;</div>
  <nav class="navbar navbar-expand-lg bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">LaraPay</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Authentication
            </a>
            <ul class="dropdown-menu">
              @if(Auth::check())
              <li><a class="nav-link active" aria-current="page" href="{{route('logout')}}">Logout</a></li>
              @else

              @if (Route::currentRouteName() == 'login')
              <li><a class="dropdown-item" href="{{route('register')}}">Register</a></li>
              @else
              <li><a class="dropdown-item" href="{{route('login')}}">Login</a></li>
              @endif
              @endif
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Stripe
            </a>
            <ul class="dropdown-menu">
              @if(Route::currentRouteName() == 'stripe-product-list')
              <li><a class="dropdown-item" href="{{route('stripe-create-product')}}">Create Product</a></li>
              @else
              <li><a class="dropdown-item" href="{{route('stripe-product-list')}}">Product List</a></li>
              @endif
              <li><a class="dropdown-item" href="{{route('checkout-view')}}">Checkout</a></li>
            </ul>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Paypal
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="{{route('paypal-checkout-view')}}">Checkout</a></li>
              @if(Route::currentRouteName() == 'paypal-plan-list')
              <li><a class="dropdown-item" href="{{ route('paypal-product-list') }}">Product List</a></li>
              @else
              <li><a class="dropdown-item" href="{{ route('paypal-plan-list') }}">Subscription Plan List</a></li>
              @endif
              <li><a class="dropdown-item" href="{{ route('paypal-create-prodcut') }}">Create Product</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  @include('flash::message')
  @yield('content')
  <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
  <script src="http://jqueryvalidation.org/files/dist/jquery.validate.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="{{ asset('/js/custom-validation.js') }}" type="text/javascript"></script>
  <script src="http://jqueryvalidation.org/files/dist/additional-methods.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script src="https://www.paypal.com/sdk/js?client-id=AfDO_OUsGVSGHL1BvYd0Q11DZKl_XYhbz0CbGyazqChwCPNsSXdavxxyyYCl1jaXvfBtnkezGN95ojOy&enable-funding=venmo&currency=USD" data-sdk-integration-source="button-factory"></script>


  <script>
    $('#flash-overlay-modal').modal();
  </script>
  <script>
    $('div.alert').not('.alert-important').delay(3000).fadeOut(350);
  </script>
  @stack('extra-javascript')
</body>

</html>