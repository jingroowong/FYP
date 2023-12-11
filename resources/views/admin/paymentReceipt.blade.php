<html>

<head>
    <meta charset="UTF-8">
    <title>Payment Receipt</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
   
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">

    <style>
    .propertyPhoto img {
        width: 400px;
        height: 200px;
    }
    </style>
</head>

<body>
@extends('layouts.adminApp')
    @section('content')
    <div class="ml-5 mt-2 container">
        @csrf
        @if($successMessage !=null)
        <div class="alert alert-success">
            <p>{{ $successMessage }}</p>
        </div><br />
        @endif
        <a href="{{ url()->previous() }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back</a>


        <div class="border rounded-5" id="receipt">

            <section class="w-100 p-4 justify-content-center">
                <div class="card">
                    <div class="card-body">
                        <div class="container mb-5 mt-3">
                            <div class="row d-flex align-items-baseline">
                                <div class="col-xl-9">

                                    <p style="color: #7e8d9f;font-size: 20px;">Payment Receipt &gt;&gt; <strong> Receipt
                                            ID:
                                            #{{$propertyRental->propertyRentalID}}</strong></p>

                                    <p class="text-muted fw-bold">
                                        Rental effective start on:
                                        {{
                                            \Carbon\Carbon::parse($propertyRental->effectiveDate)->format('Y-m-d') }}
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <ul class="list-unstyled">
                                        <li class="text-muted">To: <span
                                                style="color:#8f8061 ;">{{$propertyRental->tenant->tenantName}}</span>
                                        </li>
                                        <li class="text-muted">{{$propertyRental->property->propertyName}}</li>
                                        <li class="text-muted">{{$propertyRental->property->propertyAddress}}</li>
                                        <li class="text-muted"><i class="fas fa-phone"></i>
                                            {{$propertyRental->tenant->tenantPhone}}</li>
                                    </ul>

                                </div>

                                <div class="col-md-6">
                                    <div class="text-center">
                                        <img class="logo" src="{{Storage::url('property-photos/rentSpaceLogo.png')}}"
                                            alt="RentSpace Logo">
                                        <p class="pt-2">RentSpace</p>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <p class="text-muted">Payment : </p>
                                    <ul class="list-unstyled">
                                        <li class="text-muted"><i class="fas fa-circle" style="color:#8f8061 ;"></i>
                                            <span class="fw-bold">Property
                                                ID:</span>#{{$propertyRental->property->propertyID}}
                                        </li>
                                        <li class="text-muted"><i class="fas fa-circle" style="color:#8f8061 ;"></i>
                                            <span class="fw-bold">Payment Date:
                                            </span>{{$propertyRental->payment->paymentDate}}
                                        </li>

                                        <li class="text-muted"><i class="fas fa-circle" style="color:#8f8061;"></i>
                                            <span class="me-1 fw-bold">Status:</span><span
                                                class="badge bg-success text-white fw-bold">
                                                Paid</span>
                                        </li>


                                    </ul>

                                </div>
                            </div>
                            <p class="text-muted">Property : </p>
                            <div class="row my-2 mx-1">

                                <div class="col-md-5 mb-4 mb-md-0">
                                    <div class="propertyPhoto" data-ripple-color="light">
                                        <img src="{{ Storage::url( $propertyRental->property->propertyPhotos[0]->propertyPath) }}"
                                            alt="Property Photo" class="rounded">

                                    </div>
                                </div>
                                <div class="col-md-5 mb-4 mb-md-0">
                                    <p class="fw-bold"> Name : {{$propertyRental->property->propertyName}}</p>
                                    <p class="mb-1">
                                        <span
                                            class="text-muted me-2">Type:</span><span>{{$propertyRental->property->propertyType}}</span>
                                    </p>
                                    <p class="mb-1">
                                        <span class="text-muted me-2">Built Year
                                        </span><span>{{$propertyRental->property->buildYear}}</span>
                                    </p>
                                    <p class="mb-1">
                                        <span class="text-muted me-2">Furnishing Type
                                        </span><span>{{$propertyRental->property->furnishingType}}</span>
                                    </p>
                                </div>

                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-xl-6">
                                    <ul class="list-unstyled">
                                        <table>
                                            <tr>
                                                <td>
                                                    <li class="text-muted ms-3"><span class="me-4">Advanced
                                                            Rental : </span></li>
                                                </td>
                                                <td>RM {{$propertyRental->property->rentalAmount}}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <li class="text-muted ms-3 mt-2"><span class=" me-4">Security
                                                            Deposit (3 month) : </span></li>
                                                </td>
                                                <td>RM {{$propertyRental->property->depositAmount}}</td>
                                            </tr>

                                    </ul>
                                    <tr>
                                        <td>
                                            <p class="fw-bold float-start ms-3 "><span class="text-black me-3"> Total
                                                    Received Amount:</span>
                                        </td>

                                        <td><span class="fw-bold">RM
                                                {{$propertyRental->payment->paymentAmount}}</span>
                                            </p>
                                        </td>
                                    </tr>


                                    </table>

                                </div>
                                <div class="col-xl-6">

                                    <p>Thank you for choosing RentSpace.</p>
                                    <p>We will continue improve our service to meets your satisfaction and rental
                                        experience.
                                    </p>
                                    <button class="btn btn-primary" onclick="printReceipt()">Print Receipt</button>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>



    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <script>
    function printReceipt() {
        window.print();
    }
    </script>
    @endsection
</body>

</html>