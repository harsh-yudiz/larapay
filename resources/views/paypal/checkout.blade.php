@extends('layout.app')
@section('content')
<section>
  <div class="h-100">
    <div class="container mt-5">
      <div class="row bg-light py-4">
        <div class="alert alert-success alert-checkout" id="alert-div">
        </div>
        <div class="col-md-3"></div>
        <div class="form-group ">
          <input type="number" class="form-control" id="paypalamount" name="amount" min="1" max="99999">
        </div>
        <div id="smart-button-container">
          <div style="text-align: center;">
            <div id="paypal-button-container"></div>
          </div>
        </div>
        <div class="col-md-6">
        </div>
      </div>
    </div>
</section>
@endsection
@push('extra-javascript')
<script>
  function initPayPalButton() {
    paypal.Buttons({
      style: {
        shape: 'pill',
        color: 'silver',
        layout: 'horizontal',
        label: 'paypal',

      },

      createOrder: function(data, actions) {
        return actions.order.create({
          purchase_units: [{
            "amount": {
              "currency_code": "USD",
              "value": $('#paypalamount').val(),
            }
          }]
        });
      },

      onApprove: function(data, actions) {
        return actions.order.capture().then(function(orderData) {
          axios.post("{{ route('paypal-order-capture') }}", {
              payment_capture_id: orderData['purchase_units'][0]['payments']['captures'][0]['id'],
              amount: orderData['purchase_units'][0]['amount']['value']
            })
            .then((response) => {
              localStorage.setItem("paymentSucessMessage", "Your payment is created sucessfully.");
              var route = "{{ route('user-payment-list') }}";
              window.location.replace(route);
            }, (error) => {
              $('.alert-checkout').text("Something went to wrong, Please try again.").show();
            });
        });
      },

      onError: function(err) {
        console.log(err);
      }
    }).render('#paypal-button-container');
  }
  initPayPalButton();
</script>
@endpush