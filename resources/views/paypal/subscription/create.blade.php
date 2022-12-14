@extends('layout.app')
@section('content')
<section>
    <div class="container bg-white my-3">
        <div class="alert alert-success alert-checkout" id="alert-div">
        </div>
        <div class="card">
            <div id="paypal-button-container"></div>
        </div>
    </div>
</section>
@endsection
@push('extra-javascript')
<script src="https://www.paypal.com/sdk/js?client-id=AfDO_OUsGVSGHL1BvYd0Q11DZKl_XYhbz0CbGyazqChwCPNsSXdavxxyyYCl1jaXvfBtnkezGN95ojOy&vault=true&intent=subscription">
</script>
<script>
    paypal.Buttons({
        createSubscription: function(data, actions) {
            return actions.subscription.create({
                'plan_id': '{{$product->plan_id}}' // Creates the subscription
            });
        },
        onApprove: function(data, actions) {
            axios.post("{{ route('paypal-subscription-store') }}", {
                    subscriptionId: data.subscriptionID,
                })
                .then((response) => {
                    localStorage.setItem("paymentSucessMessage", "Your subscription is created sucessfully.");
                    var route = "{{ route('user-listing') }}";
                    window.location.replace(route);
                }, (error) => {
                    $('.alert-checkout').text("Something went to wrong, Please try again.").show();
                });
        }
    }).render('#paypal-button-container'); // Renders the PayPal button
</script>
@endpush