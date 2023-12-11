<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties</title>

    <style>
    .property {
        width: 90%;
    }

    .btn-action{
            width:25%;
        }
    </style>
</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div class="ml-5 mt-2 property container">
        <h2>Properties</h2>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Search Bar -->
        <form action="{{ route('properties.searchAll') }}" method="get" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Search properties by name, location or type" value="{{ isset($searchTerm) ? $searchTerm : '' }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <ul class="nav nav-tabs" id="myTabs">
            <li class="nav-item">
                <a class="nav-link active" id="properties-tab" data-toggle="tab" href="#properties">Properties</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="rentals-tab" data-toggle="tab" href="#rentals">Rentals</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="payment-tab" data-toggle="tab" href="#payment">Payments</a>
            </li>
        </ul>

        <div class="tab-content mt-2">
            <div class="tab-pane fade show active" id="properties">
                @if($properties!=null)
                @if(count($properties) > 0 )

                <table class="table">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th>Property Name</th>
                            <th>Property Type</th>
                            <th>Property Address</th>
                            <th>Status</th>

                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($properties as $property)
                        <tr>
                            <td>{{ $property->propertyID }}</td>
                            <td width="10%">{{ $property->propertyName }}</td>
                            <td>{{ $property->propertyType }}</td>
                            <td width="25%">{{ $property->propertyAddress }}</td>
                            @if( $property->propertyAvailability == true )
                            <td>Active until ( {{\Carbon\Carbon::parse($property->expiredDate)->format('Y-m-d') }})</td>
                            @else
                            <td>N/A</td>
                            @endif

                            <td  width="30%">
                                <a href="{{ route('properties.showAgent', $property->propertyID) }}"
                                    class="btn btn-primary btn-action">View</a>
                                <a href="{{ route('properties.edit', $property->propertyID) }}"
                                    class="btn btn-warning text-white btn-action">Update</a>
                                <a href="#" class="btn btn-danger btn-action" onclick="confirmDelete(
                                '{{ route('properties.destroy', $property->propertyID) }}',
                                '{{ $property->propertyName }}',
                                '{{ $property->propertyType }}',
                                '{{ $property->propertyAddress }}',
                                '{{ Storage::url($property->propertyPhotos[0]->propertyPath) }}'
                            )">Delete</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $properties->links() }}
                @else
                <p>No record found..</p>
                @endif
                @endif
            </div>

            <div class="tab-pane fade" id="rentals">
                @if($propertyRentals != null)
              
                @if(count($propertyRentals) > 0)

                <table class="table">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th>Property Name</th>
                            <th>Tenant</th>
                            <th>Status</th>
                            <th>Property Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($propertyRentals as $propertyRental)
                        <tr>
                            <td>{{ $propertyRental->property->propertyID }}</td>
                            <td>{{ $propertyRental->property->propertyName }}</td>
                            <td>{{ $propertyRental->tenant->tenantName }}</td>
                            <td>{{ $propertyRental->rentStatus }}</td>
                            <td>{{ $propertyRental->property->propertyAddress }}</td>

                            <td>
                                @if($propertyRental->rentStatus == "Applied")
                                <a href="{{ route('properties.approve', $propertyRental->propertyRentalID) }}"
                                    class="btn btn-success">Approve</a>
                                <a href="{{ route('properties.reject', $propertyRental->propertyRentalID) }}"
                                    class="btn btn-danger">Reject</a>
                                @else
                                <span class="text-muted"> {{$propertyRental->rentStatus}} </span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                {{ $propertyRentals->links() }}
                @else
                <p>No record found..</p>
                @endif
                @endif
            </div>

            <div class="tab-pane fade" id="payment">
                <!-- Content for Payment History tab -->
                <div class="Payment History">
                @if($paymentHistory!=null)

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
                                    <a href="{{ route('payments.paymentReceiptAdmin', $propertyRental->propertyRentalID) }}"
                                        class="btn btn-primary">View Receipt</a>
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

                                    <a href="{{ route('payments.releaseAdmin', $propertyRental->propertyRentalID) }}"
                                        class="btn btn-success">Release Fund</a>
                                </td>
                               
                                @endif
                            </tr>

                            @endforeach
                        </tbody>
                    </table>
                    {{ $paymentHistory->links() }}
                    @else
                    <p>No record found..</p>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        <!-- Bootstrap CSS -->
        <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <!-- Bootstrap JS -->
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <!-- Bootstrap Modal -->
        <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
            aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmationModalLabel">Are you sure to delete the
                            following
                            property?</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p><strong>Property Name:</strong> <span id="propertyName"></span></p>
                        <p><strong>Property Type:</strong> <span id="propertyType"></span></p>
                        <p><strong>Property Address:</strong> <span id="propertyAddress"></span></p>
                        <img id="propertyImage" src="" alt="Property Image" style="max-width: 100%; max-height: 200px;">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-danger" onclick="proceedWithDeletion()">Delete</button>
                    </div>
                </div>
            </div>
        </div>

        <script>
        function confirmDelete(deleteUrl, propertyName, propertyType, propertyAddress, propertyImage) {
            // Set modal content
            document.getElementById('propertyName').textContent = propertyName;
            document.getElementById('propertyType').textContent = propertyType;
            document.getElementById('propertyAddress').textContent = propertyAddress;
            document.getElementById('propertyImage').src = propertyImage;

            // Show the modal
            $('#deleteConfirmationModal').modal('show');

            // Set the deletion URL
            $('#deleteConfirmationModal').data('deleteUrl', deleteUrl);
        }

        function proceedWithDeletion() {
            // Get the deletion URL from the modal
            var deleteUrl = $('#deleteConfirmationModal').data('deleteUrl');

            // Redirect to the deletion URL
            window.location.href = deleteUrl;
        }
        </script>
    </div>

    @endsection
</body>

</html>