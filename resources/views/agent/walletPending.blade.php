<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet</title>
</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div class="ml-5 mt-2">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
    <a href="{{ route('agentWallet') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1>Property Rentals with Pending Payment</h1>
        @if(count($pendingRentals) > 0)
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Property ID</th>
                    <th>Property Name</th>
                    <th>Tenant Name</th>
                    <th>Tenant Contact</th>
                    <th>Pending Amount</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($pendingRentals as $pendingRental)
                <tr>
                        <td>{{ $pendingRental->propertyID }}</td>
                        <td>{{ $pendingRental->property->propertyName }}</td>
                        <td>{{ $pendingRental->tenant->tenantName }}</td>
                        <td>{{ $pendingRental->tenant->tenantPhone }}</td>
                        <td>RM {{ $pendingRental->payment->paymentAmount }}</td>
                        <td>{{ \Carbon\Carbon::parse($pendingRental->date)->addDays(14)->toDateString()}}</td>
                     
                        <td>
                            <a href="{{ route('agent.requestPayment', $pendingRental->propertyRentalID) }}" class="btn btn-success">Request Payment</a>
                        </td>
                        @endforeach
            </tbody>
        </table>
        @else
        <p>No pending payments..</p>
        @endif
    </div>
    @endsection
</body>

</html>
