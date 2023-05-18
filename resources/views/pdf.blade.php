<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>PRINT</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<style>
    .table td, .table th {
      padding: 0; 
      border-top: none;
    }
    .listTable tr td{
        padding: 6px 6px 6px 6px !important;
    }
    @page {
        size: A4;
        margin: 1cm;
    }
</style>
<body style="font-family: serif !important;margin-bottom: -20px;height: auto;">
    <div class="container-fluid">
        <div class="col-md-12" style="text-align: center">
            {{-- <div class="col-md-2">
            <img src="/image/TZ_logo.png" alt="" srcset="">
            </div>
            <div class="col-md-8"> --}}
                {{-- <img src="" alt="" srcset=""> --}}
            <h2 style="margin-bottom: 0;font-size:20px;">VEHICLE TEST REPORT</h2>
            {{-- </div> --}}
        </div>
        <hr style="border-top: 4px solid blue;">
        {{-- <div class="col-md-12" style="display: flex;margin-bottom: -21px;padding: 0;"> --}}
            <table style="width:100%">
                <tbody>
                    <tr>
                        <th style="    padding-left: 19px;">
                            <p style="margin-bottom: 0;font-weight: bold;">Customer Details</p>
                            <table class="table borderless">
                                @php $customer = json_decode($data['customer_details']); @endphp
                                 <tr>
                                    <td>Name</td>
                                    <td>: {{$customer->name}}</td>
                                </tr>
                                <tr>
                                    <td>Mobile</td>
                                    <td>: {{$customer->mobile}}</td>
                                </tr>
                                <tr>
                                    <td>Address</td>
                                    <td>: {{$customer->address}}</td>
                                </tr>
                                <tr>
                                    <td>City</td>
                                    <td>: {{$customer->city}}</td>
                                </tr>
                            </table>
                        </th>
                        <th>
                            <p style="margin-bottom: 0;font-weight: bold;">Vehicle Details</p>
                            <table class="table borderless">
                                @php $vehicle = json_decode($data['vehicle_details']); @endphp
                                <tr>
                                    <td>Name</td>
                                    <td>: {{$vehicle->name}}</td>
                                </tr>
                                <tr>
                                    <td>Year</td>
                                    <td>: {{$vehicle->year}}</td>
                                </tr>
                                <tr>
                                    <td>Modal</td>
                                    <td>: {{$vehicle->modal}}</td>
                                </tr>
                                <tr>
                                    <td>VIN</td>
                                    <td>: {{$vehicle->vin}}</td>
                                </tr>
                            </table>
                        </th>
                        <th>
                            <table class="table borderless">
                                <tr>
                                    <td>Date : 23-10-2021</td>
                                </tr>
                                <tr>
                                    <td>
                                        <img src="{{ public_path('image/delx.png')}}" alt="" srcset="" style="width: 15%;">
                                    </td>
                                </tr>
                            </table>
                        </th>
                    </tr>
                </tbody>
            </table>
        {{-- </div> --}}
        <div class="" style="display: flex;justify-content: center;margin-bottom: 10px;">
            <div class="" style="display: flex;text-align: center;background: blue; color: white;">
                <h4 style="font-size : 14px;margin: 4px 0;;background: blue;" >TEST RESULT</h4>
            </div> 
        </div>

        <table>
            <thead>
                <tr>
                    
                </tr>
            </thead>
        </table>

        <table style="width: 100%">
            <thead>
                <tr>
                    <th style="padding-left: 15px;">
                        <h6 style="margin-top: 0.5rem; text-align: center;border: 1px solid green;padding: 5px 0 3px 0;font-size: 9px;"><img src="{{ public_path('image/1.png')}}" alt="" srcset="" style="width: 10px;margin-top: 0px;"> CHECKED AND OK AT THIS TIME</h3>
                    </th>
                    <th style="padding: 0 11px;">
                        <h6 style="margin-top: 0.5rem; text-align: center;border: 1px solid yellow;padding: 5px 0 3px 0;font-size: 9px;"><img src="{{ public_path('image/2.png')}}" alt="" srcset="" style="width: 10px;margin-top: 0px;"> MAY REQUIRE FUTURE ATTENTION</h3>
                    </th>
                    <th style="padding-right: 15px;">
                        <h6 style="margin-top: 0.5rem; text-align: center;border: 1px solid red;padding: 5px 0 3px 0;font-size: 9px;"> <img src="{{ public_path('image/3.png')}}" alt="" srcset="" style="width: 10px;margin-top:0px;"> REQUIRES IMMEDIATE ATTENTION</h3>
                    </th>
                </tr>
            </thead>
        </table>
        <br>

        <table style="width : 100%">
            <thead>
                <tr>
                    <th style="padding-left: 15px;">
                        <table class="table borderless">
                            <tr>
                                <td>Dealer Name</td>
                                <td>: {{$data['dealer_name']}}</td>
                            </tr>
                            <tr>
                                <td>Address</td>
                                <td>: {{$data['address1']}},{{$data['city']}}</td>
                            </tr>
                            <tr>
                                <td>Mobile</td>
                                <td>: {{$data['mobile']}}</td>
                            </tr>
                        </table>
                    </th>
                    <th style="">
                        <table class="table borderless">
                            <tr>
                                <td style="">Comments :</td>
                                <td>{{$data['dealer_comments']}}</td>
                            </tr>
                            <tr>
                                <td>Delivary Date :</td>
                                <td>{{$data['delivery_date']}}</td>
                            </tr>
                            <tr>
                                <td>Signature :</td>
                                <td><br><br></td>
                            </tr>
                        </table>
                    </th>
                    {{-- <th style="">
                        <table class="table borderless">
                            <tr>
                                
                            </tr>
                            <tr style="text-align: center">
                                <td> 
                                    (Sign)
                                </td>
                            </tr>
                        </table>
                    </th> --}}
                </tr>
            </thead>
        </table>
        
        <hr style="border-top: 4px solid blue;">
        <p style="font-size: 12px; text-align: center;"><b>NOTE : </b>Aenean pretium, ipsum et porttitor auctor, metus ipsum iaculis nisi, a bibendum lectus libero vitae urna. Sed id leo eu dolor luctus congue sed eget ipsum. Nunc nec luctus libero. Etiam quis dolor elit.</p>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</html>