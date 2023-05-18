<!DOCTYPE html>
<html>
<head>
    <title>Tamilzorous Info Tech</title>
</head>
<body>
    Hi {{ $name }},
    <br>
    <br>
    <table>
        <tbody>
            <tr>
                <td>Product Name(VCI)  </td>
                <td>: {{$vci->name}} </td>
            </tr>
            <tr>
                <td>Plan  </td>
                <td>: {{$plan->name}} </td>
            </tr>
            <tr>
                <td>Amount  </td>
                <td>: {{$plan->amount}} </td>
            </tr>
            <tr>
                <td>Duration  </td>
                @if($plan->duration < 10)
                <td>: {{$plan->duration}} Month </td>
                @else
                <td>: {{$plan->duration/12}} Year</td>
                @endif
               
            </tr>
            <tr>
                <td>Mode of Payment </td>
                <td>: online </td>
            </tr>
            <button class="btn btn-primary btn-sm">
                <a href="{{url('/')}}/payment?user_id=@php echo $id @endphp&subscription_id={{$subscription->id}}&amount={{$plan->amount}}&plan={{$plan->name}}&currency=INR&name={{$name}}&email={{$email}}&mobile={{$mobile_no}}">PAY</a>
            </button>

        </tbody>
    </table>
    <p>
        Regards,
    </p>
    <h3>Tamilzorous Info Tech</h3>
</body>
</html>