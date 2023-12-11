<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund</title>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .icon {
        font-size: 18px;
    }

    .beware {
        font-size: 18px;
    }

    .propertyPhoto img {
        border-radius: 8px;
        width: 400px;
        height: 200px;
    }

    .payment-details img {
        border-radius: 8px;
        width: 400px;
        height: 300px;
    }

    .table {
        max-width: 80%;
    }
    </style>
</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div class="ml-5 mt-2 container">
        <a href="{{ route('refunds') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2 class="mt-4 mb-4">Refund Application for Rental # {{$refund->propertyRentalID}}</h2>

        <!-- Display Property Details -->

        <div class="property-details">
            <p><strong>Property Name :</strong> {{ $refund->propertyRental->property->propertyName }}</p>
            <p><strong>Property Address:</strong> {{ $refund->propertyRental->property->propertyAddress }}</p>
        </div>

        <div class="row">
            <!-- Display Property Photo -->
            <div class="col-md-6 propertyPhoto">
                <img src="{{ Storage::url($refund->propertyRental->property->propertyPhotos[0]->propertyPath) }}"
                    alt="Property Photo">
            </div>
        </div>
        <!-- Display Payment Information -->
        <div class="payment-details mt-4">
            <h3>Refund Information:</h3>
            <table class="table">
                <tr>
                    <td>Property Rental ID: </td>
                    <td><strong> # {{$refund->propertyRental->propertyID}}</strong></td>
                </tr>
                <tr>
                    <td>Requested by Tenant : </td>
                    <td><strong>{{$refund->propertyRental->tenant->tenantName}}
                            ({{$refund->propertyRental->tenant->tenantPhone}}) </strong></td>
                </tr>

                <tr>
                    <td>Payment Date:</td>
                    <td><strong>{{ $refund->propertyRental->payment->paymentDate }}</strong></td>
                </tr>
                <tr>
                    <td>Payment Amount:</td>
                    <td><strong>RM {{ $refund->propertyRental->payment->paymentAmount }}</strong></td>
                </tr>
                <tr>
                    <td>Payment Method:</td>
                    <td><strong>{{ $refund->propertyRental->payment->paymentMethod }}</strong></td>
                </tr>
                <tr>
                    <td>Agent In Charge:</td>
                    <td><strong> {{$refund->propertyRental->property->agent->agentName}}
                            ({{$refund->propertyRental->property->agent->agentPhone}})</strong></td>
                </tr>
                <tr>
                    <td>Reason:</td>
                    <td><strong>{{$refund->refundReason}}</strong>
                        </br>
                        @if($refund->refundPhoto!=null)
                        <img src="{{ Storage::url($refund->refundPhoto) }}" alt="Refund Photo">
                        @endif
                    </td>
                </tr>

            </table>
        </div>
        <div>
            <form action="{{route('refunds.reject')}}" method="post" id="rejectForm">
                @csrf
                <div class="btn-container">
                    <input type="hidden" name="refundID" id="refundID" value="{{$refund->refundID}}">
                    @if($refund->refundStatus == "Approved")
                    <!-- Display information or message indicating that the status is completed -->
                    <span class="text-muted">Refund Approved</span>
                    @elseif($refund->refundStatus == "Rejected")
                    <!-- Display information or message indicating that the status is completed -->
                    <span class="text-muted">Refund Rejected</span>
                    @else
                    <button type="button" class="btn btn-success" id="confirmRefund"> Confirm Refund </button>
                    <button type="button" class="btn btn-danger" id="rejectButton"> Reject </button>
                    @endif
                </div>
            </form>
        </div>

        <!-- Bootstrap Modal for Reject Reason -->
        <div class="modal fade" id="rejectReasonModal" tabindex="-1" role="dialog"
            aria-labelledby="rejectReasonModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectReasonModalLabel">Reject Reason</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            onclick="closeRejectReasonModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <label for="rejectReason">Enter Reason:</label>
                        <textarea class="form-control" id="rejectReason" name="rejectReason" rows="4"
                            placeholder="Enter the reason for rejection"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"
                            onclick="closeRejectReasonModal()">Close</button>
                        <button type="button" class="btn btn-danger" id="confirmReject">Confirm Reject</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap Modal for Approve -->
        <div class="modal fade" id="adminRefundConfirmationModal" tabindex="-1" role="dialog"
            aria-labelledby="adminRefundConfirmationModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adminRefundConfirmationModalLabel">Admin Refund Confirmation</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                            onclick="closeApproveModal()">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Refund to Tenant Name: <strong>{{ $refund->propertyRental->tenant->tenantName }}</strong></p>
                        <p>Refund Amount: <strong>RM {{ $refund->propertyRental->payment->paymentAmount }}</strong></p>
                        <label for="adminRefundConfirmationCode">Type "Refund" to confirm:</label>
                        <input type="text" class="form-control" id="adminRefundConfirmationCode"
                            name="adminRefundConfirmationCode" />
                    </div>

                    <div class="modal-footer">
                        <!-- Add this form inside the adminRefundConfirmationModal -->
                        <form action="{{ route('refunds.approve') }}" method="post" id="approveRefundForm">
                            @csrf
                            <input type="hidden" name="refundID" id="refundID" value="{{ $refund->refundID }}">
                            <button type="submit" class="btn btn-success" id="confirmAdminRefund">Confirm
                                Refund</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
        function toggleSubmitButton() {
            var checkbox = document.getElementById('readRulesCheckbox');
            var submitButton = document.getElementById('submitButton');

            // Enable the Next button if the checkbox is checked, otherwise disable it
            submitButton.disabled = !checkbox.checked;
        }
        </script>

        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

        <script>
        $(document).ready(function() {
            // Show modal when Reject button is clicked
            $('#rejectButton').click(function() {
                $('#rejectReasonModal').modal('show');
            });

            // Submit the rejection form with reason
            $('#confirmReject').click(function() {
                var rejectReason = $('#rejectReason').val();
                if (rejectReason.trim() !== '') {
                    // Set the reason value to a hidden input in the form
                    $('#rejectForm').append('<input type="hidden" name="rejectReason" value="' +
                        rejectReason + '">');
                    // Submit the form
                    $('#rejectForm').submit();
                } else {
                    alert('Please enter a reason for rejection.');
                }
            });

            // Show modal when Confirm Refund button is clicked
            $('#confirmRefund').click(function() {
                $('#adminRefundConfirmationModal').modal('show');
            });

            // Submit the admin refund confirmation form
            $('#confirmAdminRefund').click(function() {
                var adminRefundConfirmationCode = $('#adminRefundConfirmationCode').val();

                // Check if the confirmation code is correct
                if (adminRefundConfirmationCode.trim().toLowerCase() === 'refund') {
                    // Trigger the submission of the approveRefundForm
                    $('#approveRefundForm').submit();
                } else {
                    alert('Invalid confirmation code. Please type "Refund" to confirm the refund.');
                }
            });



        });

        function closeRejectReasonModal() {
            // Hide the modal
            $('#rejectReasonModal').modal('hide');
        }

        function closeApproveModal() {
            // Hide the modal
            $('#adminRefundConfirmationModal').modal('hide');
        }
        </script>

        @endsection
</body>

</html>