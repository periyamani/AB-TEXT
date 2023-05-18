<script src="{{asset('assets/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/js/bootstrap.min.js')}}"></script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    $( document ).ready(function() {
        function GetURLParameter(sParam)
        {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');
            for (var i = 0; i < sURLVariables.length; i++) 
            {
                var sParameterName = sURLVariables[i].split('=');
                if (sParameterName[0] == sParam) 
                {
                    return sParameterName[1];
                }
            }
        }
        var user_id = GetURLParameter('user_id') ;
        var subscription_id = GetURLParameter('subscription_id') ;
        var amount = GetURLParameter('amount') ;
        var plan = GetURLParameter('plan') ;
        var name = GetURLParameter('name') ;
        var email = GetURLParameter('email') ;
        var mobile = GetURLParameter('mobile') ;
        var currency = GetURLParameter('currency') ;
        for(var i=1;i<10;i++){
            plan = plan.replace('%20',' ');
            email = email.replace('%20',' ');
            mobile = mobile.replace('%20',' ');
            name = name.replace('%20',' ');
        }
        console.log(amount);
        payment(subscription_id,name,mobile,email,plan,amount,currency,user_id)
        
    });
    function payment(id,name,mobile,email,plan,amount,currency,user_id){
        if(name){
            // var amount = 1;
        var random_no = (Math.random()*90000) + 10000
        var total_amount = amount * 100;
        var options = {
            "key": "rzp_test_UViP0JYG0M2kWq",
            "amount": total_amount,
            "currency": currency,
            "name": "Tamilzorous Info Tech",
            "description": plan ,
            "image": "../images/smalllogo.png",
            // "order_id": '',
            "handler": function (response){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                paymentAdd(response.razorpay_payment_id,id,user_id)
            },
            "prefill": {
                "name": name,
                "email": email,
                "contact": mobile
            },
            "notes": {
                "address": "test test"
            },
            "theme": {
                "color": "#024884"
            }
        };
        var rzp1 = new Razorpay(options);
        rzp1.open();
        }else{
            toastr['warning']('Login first after view the profiles');
        }
    }

    function paymentAdd(razorpay_payment_id,id,user_id){

        $.ajax({
                    type:'POST',
                    url:'/api/payment',
                    data:{razorpay_payment_id:razorpay_payment_id,user_id:user_id,id:id},
                    success:function(data){
                       window.location.href = '/thankyou?razorpay_payment_id='+razorpay_payment_id
                    }
                });
    }
</script>

