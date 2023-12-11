<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund</title>
</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div class="ml-5 mt-2">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <h2>Refunds</h2>

    @if(count($refunds) > 0)
    <table class="table">
        <thead>
            <tr>
               
                <th>Property Rental ID</th>
                <th>Refund Amount</th>
                <th>Refund Date</th>
                <th>Status</th>
                <th>Tenant</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($refunds as $refund)
            <tr>
               
                <td>{{ $refund->propertyRental->propertyRentalID }}</td>
                <td>{{ $refund->propertyRental->payment->paymentAmount }}</td>
                <td>{{ $refund->refundDate }}</td>
                <td>{{ $refund->refundStatus }}</td>
                
                <td>{{ $refund->propertyRental->tenant->tenantName }}</td>
                <td>

                    <!-- Display buttons only if the status is not completed -->
                    <a href="{{ route('refunds.show', $refund->refundID) }}" class="btn btn-primary">Check</a>


                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>No refunds available.</p>
    @endif
</div>


@endsection
</body>

</html>