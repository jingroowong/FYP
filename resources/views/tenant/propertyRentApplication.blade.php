@extends('layouts.header')

@section('content')
<div class="container">
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <ul class="nav nav-tabs" id="myTabs">
        <li class="nav-item">
            <a class="nav-link active" id="allRental-tab" data-toggle="tab" href="#allRental">Rentals</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="application-tab" data-toggle="tab" href="#application">Applications</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="paymentHistory-tab" data-toggle="tab" href="#paymentHistory">Payment History</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="allRental">
            <!-- Content for All Rentals tab -->
            <div class="allRental">
                <h2>Your Properties Rentals</h2>
                @if(count($propertyRentals) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th>Property Name</th>
                            <th>Property Address</th>
                            <th>Agent</th>
                            <th>Status</th>

                        </tr>
                    </thead>

                    <tbody>
                        @foreach($propertyRentals as $propertyRental)
                        <tr>
                            <td>{{ $propertyRental->propertyID }}</td>
                            <td>{{ $propertyRental->property->propertyName }}</td>
                            <td>{{ $propertyRental->property->propertyAddress }}</td>
                            <td>{{ $propertyRental->property->agent->agentName }}</td>
                            <td>{{ $propertyRental->rentStatus }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $propertyRentals->links() }}

                @else
                <p>No record found..</p>
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="application">
            <!-- Content for Applications tab -->
            <div class="Application">
                <h2>Your Properties Application</h2>
                @if(count($propertyApplications) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th>Property Name</th>
                            <th>Property Address</th>
                            <th>Agent</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($propertyApplications as $propertyRental)
                        <tr>
                            <td>{{ $propertyRental->propertyID }}</td>
                            <td>{{ $propertyRental->property->propertyName }}</td>
                            <td>{{ $propertyRental->property->propertyAddress }}</td>
                            <td>{{ $propertyRental->property->agent->agentName }}</td>
                            <td>{{ $propertyRental->rentStatus }}</td>
                            <td>
                                @if($propertyRental->rentStatus == "Applied")
                                <!-- Display information or message indicating that the status is completed -->
                                <span class="text-muted">Waiting for agent</span>
                                @else
                                <a href="{{ route('payments.create', $propertyRental->propertyRentalID) }}"
                                    class="btn btn-success">Make payment</a>
                                @endif
                            </td>

                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $propertyApplications->links() }}
                @else
                <p>No record found..</p>
                @endif
            </div>
        </div>

        <div class="tab-pane fade" id="paymentHistory">
            <!-- Content for Payment History tab -->
            <div class="Payment History">
                <h2>Your Payment History</h2>
                <p>Notes : Your are protected, and eligible for a refund in the event of a scam.</p>
                <p> The advanced rental and security deposit will be held for 14 days to ensure the property is in good
                    condition and meets your satisfaction.</p>
                @if(count($paymentHistory) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th width="10%">Property Name</th>
                            <th width="20%">Property Address</th>

                            <th>Paid Amount (RM)</th>
                            <th>Payment Date</th>
                            <th>Effective Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($paymentHistory as $propertyRental)
                        <tr>
                            <td>{{ $propertyRental->propertyID }}</td>
                            <td>{{ $propertyRental->property->propertyName }}</td>
                            <td>{{ $propertyRental->property->propertyAddress }}</td>

                            <td>{{ $propertyRental->payment->paymentAmount}}</td>
                            <td>{{ $propertyRental->payment->paymentDate }}</td>
                            <td>{{ \Carbon\Carbon::parse($propertyRental->effectiveDate)->format('Y-m-d') }}</td>

                            <td>
                                @if($propertyRental->rentStatus == "Completed")
                                <!-- Display information or message indicating that the status is completed -->
                                <a href="{{ route('payments.paymentReceipt', $propertyRental->propertyRentalID) }}"
                                    class="btn btn-success">View Receipt</a>
                                @elseif($propertyRental->rentStatus == "Refund requested")
                                <!-- Display information or message indicating that the status is completed -->
                                <span class="text-muted">Refund Requested</span>
                                @elseif($propertyRental->rentStatus == "Refund approved")
                                <!-- Display information or message indicating that the status is completed -->
                                <span class="text-muted">Refund Approved</span>
                                @elseif($propertyRental->rentStatus == "Refund rejected")
                                <!-- Display information or message indicating that the status is completed -->
                                <span class="text-muted">Refund Rejected</span>
                                @else
                                <!-- Display buttons only if the status is not completed -->

                                <a href="{{ route('payments.release', $propertyRental->propertyRentalID) }}"
                                    class="btn btn-success">Release Fund</a>
                            </td>
                            <td>
                                <a href="{{ route('refunds.create', $propertyRental->propertyRentalID) }}"
                                    class="btn btn-danger">Make Refund</a>
                                @endif
                            </td>
                        </tr>

                        @endforeach
                    </tbody>
                </table>
                {{ $paymentHistory->links() }}
                @else
                <p>No record found..</p>
                @endif
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#myTabs a').on('click', function(e) {
            e.preventDefault()
            $(this).tab('show')
        })
    });
    </script>

</div>
@endsection