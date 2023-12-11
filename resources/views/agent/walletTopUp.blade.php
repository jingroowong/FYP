<html>

<head>
    <meta charset="UTF-8">
    <title>Top Up Wallet</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">

    <style>
    body {
        background: #f5f5f5;
        width:90%;
    }

    .rounded {
        border-radius: 1rem
    }

    .nav-pills .nav-link {
        color: #555
    }

    .nav-pills .nav-link.active {
        color: white
    }

    input[type="radio"] {
        margin-right: 5px
    }

    .bold {
        font-weight: bold;
    }
   
    
    </style>
</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div class="ml-5 mt-2">
        <a href="{{ route('agentWallet') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2>Top-Up Wallet</h2>

        @csrf
        @if(\Session::has('error'))
        <div class="alert alert-warning">
            <p>{{ \Session::get('error')}}</p>
        </div><br />
        @endif

        <!-- Wallet Balance -->
        
        <p>Your Current Wallet Balance: RM {{ $agentWallet->balance }}</p>



        <!-- Payment Method Selection -->
        <div class="row">
            <div class="col-lg-6 mx-auto">
                <div class="card ">
                    <div class="card-header">
                        <div class="bg-white shadow-sm pt-4 pl-2 pr-2 pb-2">
                            <!-- Credit card form tabs -->
                            <ul role="tablist" class="nav nav-pills rounded nav-fill mb-3">
                                <li class="nav-item"> <i class="fas fa-credit-card mr-2"></i> Credit Card
                                </li>
                            </ul>
                        </div> <!-- End -->
                        <!-- Credit card form content -->
                        <div class="tab-content">
                            <!-- credit card info-->
                            <div id="credit-card" class="tab-pane fade show active pt-3">
                                <!-- Top-Up Amount -->
                                <div class="form-group">
                                    <form action="/session" method="POST">
                                        <label for="topUpAmount">
                                            <h6>Enter Top-Up Amount (MYR)</h6>
                                        </label>
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <input type="hidden" name="productname" value="{{$agentWallet->walletID}}">
                                        <input type="number" class="form-control" id="topUpAmount" name="topUpAmount"
                                            placeholder="Enter the desired amount" min=1 required>
                                </div>
                            </div>
                            <div class="card-footer"> <button type="submit"
                                    class="subscribe btn btn-primary btn-block shadow-sm"> Confirm Payment
                                </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="  https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>

        <script>
        $(function() {
            $('[data-toggle="tooltip"]').tooltip()
        })
        </script>


        @endsection
</body>

</html>