  @extends('layout.admin_layout')
  @section('content')
  <div class="container">
      <div class="page-inner">
          <div class="page-header">
              <h3 class="fw-bold mb-3">Category</h3>
              <ul class="breadcrumbs mb-3">
                  <li class="nav-home">
                      <a href="#">
                          <i class="icon-home"></i>
                      </a>
                  </li>
                  <li class="separator">
                      <i class="icon-arrow-right"></i>
                  </li>
                  <li class="nav-item">
                      <a href="#">Dashboard</a>
                  </li>
                  <li class="separator">
                      <i class="icon-arrow-right"></i>
                  </li>
                  <li class="nav-item">
                      <a href="#">Manage</a>
                  </li>
              </ul>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="card">
                      <div class="card-header">
                          <div class="card-title">Add New Category</div>
                      </div>

                      <form id="payment-form" action="{{ route('payment.success') }}" method="POST" style="display:none;">
                          @csrf
                          <input type="hidden" name="payment_id" id="payment_id">
                      </form>

                      <button id="rzp-button1" class="btn btn-sm btn-primary m-3 p-3 ">Pay</button>
                  </div>
              </div>
          </div>
      </div>
  </div>



  <script src="https://checkout.razorpay.com/v1/checkout.js"></script>


  <script>
      var options = {
          "key": "rzp_test_R7raQKFj1qN71z",
          "amount": "50000",
          "currency": "INR",
          "name": "fhuhdjncnxjklncjklnl",
          "description": "Test Transaction",
          "image": "https://example.com/your_logo",
          "handler": function(response) {


            //   alert(response.razorpay_payment_id);

            //   console.log(response.razorpay_payment_id);
              
            //   console.log("pay success");

                document.getElementById('payment_id').value = response.razorpay_payment_id;
                document.getElementById('payment-form').submit(); 
          },
          "prefill": {
              "name": "Gaurav Kumar",
              "email": "gaurav.kumar@example.com",
              "contact": "+919876543210"
          },
          "notes": {
              "address": "Razorpay Corporate Office"
          },
          "theme": {
              "color": "#33cc3bff"
          }
      };
      var rzp1 = new Razorpay(options);
      rzp1.on('payment.failed', function(response) {
          alert(response.error.code);
          alert(response.error.description);
          alert(response.error.source);
          alert(response.error.step);
          alert(response.error.reason);
          alert(response.error.metadata.order_id);
          alert(response.error.metadata.payment_id);
      });
      document.getElementById('rzp-button1').onclick = function(e) {
          rzp1.open();
          e.preventDefault();
      }
  </script>
  @endsection