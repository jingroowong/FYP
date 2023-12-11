<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Refund</title>
    @extends('layouts.header')
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
    </style>
</head>

<body>
    @section('content')
    <div class="container">
        <h2 class="mt-4 mb-4">Refund Application for {{ $propertyRental->property->propertyName }}</h2>

        <!-- Display Property Details -->

        <div class="property-details">
            <p><strong>Address:</strong> {{ $propertyRental->property->propertyAddress }}</p>
        </div>

        <div class="row">
            <!-- Display Property Photo -->
            <div class="col-md-9 propertyPhoto">
                <img src="{{ Storage::url($propertyRental->property->propertyPhotos[0]->propertyPath) }}"
                    alt="Property Photo">
            </div>

        </div>
        </br>
        <form action="{{route('refunds.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="col-md-6">
                    <div class="tenantDetails">
                        <h3>Your details : </h3>
                        <table>
                            <tr>
                                <td>
                                    <p><strong>Tenant's Name : </strong>
                                </td>

                                <td> {{$propertyRental->tenant->tenantName}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Tenant's Email : </strong>
                                </td>
                                <td> {{$propertyRental->tenant->tenantEmail}}</p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Tenant's Contact Number : </strong>
                                </td>
                                <td> {{$propertyRental->tenant->tenantPhone}}</p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Step 3: Agent Preview -->
                    <h3>Agent details : </h3>
                    <table>
                        <tr>
                            <td>
                                <p><strong>Agent's Name : </strong>
                            </td>
                            <td> {{$propertyRental->property->agent->agentName}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>Agent's Email : </strong>
                            </td>
                            <td> {{$propertyRental->property->agent->agentEmail}}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>Agent's Contact Number : </strong>
                            </td>
                            <td> {{$propertyRental->property->agent->agentPhone}}</p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>




            </br>

            <div class="row">
                <!-- Step 3: Appointment Preview -->
                <h3>Refund Reason</h3>
                <div class="appointment-step col-md-6" id="step-3">


                    <strong> <label for="message">Please give reasonable reason:</label></strong>
                    <textarea name="reason" class="form-control" id="reason"></textarea>
                    <h6> If your rental fails to meet expectations
                        set by the agent, or is critically flawed in some way, contact RentSpace and we
                        will issue a full refund pending a review.</h6>
                    </br>
                </div>

                <div class="col-md-6">
                    <strong> <label for="photoUpload">Upload Supporting Photos:</label></strong>
                    <input type="file" class="form-control" id="photoUpload" name="photoUpload" accept="image/*">

                    <small class="text-muted">
                        You can upload photos to support your refund request.
                    </small>
                </div>
            </div>

            <!-- Additional appointment rules section -->

            <div class="row appointment-rules">
                <h3>Refund Rules</h3>
                <div class="col-md-6">
                    <!-- Viewing time rule -->
                    <div class="rule">
                        <div class="icon"><i class="las la-clock"></i> Days </div>
                        <div class="details">
                            <p>You have 14 days to evaluate your rent experience.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- Waiting time rule -->
                    <div class="rule">
                        <div class="icon"><i class="las la-hands-helping"></i> Issue </div>
                        <div class="details">
                            <p>The issue of refunds is at the complete discretion of RentSpace.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <!-- No pets allowed rule -->
                    <div class="beware">
                        <i class="las la-money-bill"></i> 100% refundable
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- No smoking rule -->
                    <div class="beware">
                        <i class="las la-hand-holding-usd"></i> Refund process takes 2-3 working days.
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <div class="mt-3 form-check">
                    <input class="form-check-input" type="checkbox" id="readRulesCheckbox"
                        onchange="toggleSubmitButton()">
                    <label class="form-check-label" for="readRulesCheckbox">
                        I agree to RentSpace's Terms of Service and Privacy Policy including the collection,
                        use
                        and disclosure of my personal information.
                    </label>
                </div>
            </div>
            </br>

            <div class="btn-container">
                <input type="hidden" name="propertyRentalID" id="propertyRentalID"
                    value="{{$propertyRental->propertyRentalID}}">

                <button type="submit" class="btn btn-warning px-3 mr-3" id="submitButton" disabled> Confirm </button>
                <a href="{{ route('applicationIndex') }}" class="btn btn-secondary px-3">
                    Cancel
                </a>
            </div>




        </form>
    </div>
    @endsection

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
</body>

</html>