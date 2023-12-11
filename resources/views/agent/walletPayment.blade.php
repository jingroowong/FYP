<!-- resources/views/agent/pay-posting-fee.blade.php -->
<html>

<head>
    <meta charset="UTF-8">
    <title>Make Payment</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
    .wallet .card {
        border: none;
    }

    .wallet .form-control {
        border-bottom: 2px solid #eee !important;
        border: none;
        font-weight: 600
    }

    .wallet .form-control:focus {
        color: #495057;
        background-color: #fff;
        border-color: #8bbafe;
        outline: 0;
        box-shadow: none;
        border-radius: 0px;
        border-bottom: 2px solid blue !important;
    }

    .wallet .card-blue {
        background-color: #492bc4;
    }

    .wallet .hightlight {
        background-color: #5737d9;
        padding: 10px;
        border-radius: 10px;
        margin-top: 15px;
        font-size: 14px;
    }

    .wallet .yellow {
        color: #fdcc49;
    }

    .wallet .decoration {
        text-decoration: none;
        font-size: 14px;
    }

    .wallet .btn-success {
        color: #fff;
        background-color: #492bc4;
        border-color: #492bc4;
    }

    .wallet .btn-success:hover {
        color: #fff;
        background-color: #492bc4;
        border-color: #492bc4;
    }

    .wallet .decoration:hover {
        text-decoration: none;
        color: #fdcc49;
    }

    .wallet {

        max-width: 90%;
    }
    </style>

</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div class="ml-5 mt-2 wallet">
        <a href="{{ route('agentWallet') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2>Pay Posting Fee</h2>

        <div class="mb-4">
            <h2>Number of active property posts: {{ count($activeProperty) }}</h2>
            <p>Your current wallet balance: RM {{ $walletBalance }}</p>
            <span>Please make the payment to avoid losing your potential tenant.</span>
        </div>

        <form method="POST" action="{{ route('posting.payment') }}" id="paymentForm">
            @csrf
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="check-all">
                        </th>
                        <th>Property ID</th>
                        <th>Property Image</th>
                        <th>Property Name</th>
                        <th>Expired Date</th>
                        <th>Duration to Extend</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($activeProperty as $property)
                    <tr>
                        <td>
                            <input type="checkbox" name="property[]" class="property-checkbox"
                                value="{{ $property->propertyID }}">
                        </td>
                        <td>{{ $property->propertyID }}</td>
                        <td> <img src="{{ Storage::url($property->propertyPhotos[0]->propertyPath) }}"
                                alt="Property Photo" width="100" height="80"></td>
                        <td>{{ $property->propertyName }}</td>
                        <td>  {{\Carbon\Carbon::parse($property->expiredDate)->format('Y-m-d') }}
                           ({{ \Carbon\Carbon::parse($property->expiredDate)->diffForHumans() }})
                        </td>
                        <td>
                            <select name="duration[{{ $property->propertyID }}]" class="form-control duration">
                                <option value="7">7 days</option>
                                <option value="14">14 days (-10% off)</option>
                                <option value="30">30 days (-20% off)</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-3">
                <label for="amount">Total Amount:</label>
                <span id="totalAmount">RM 0.00</span>
                <input type="hidden" name="amount" id="amount" value="0">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 300px;" id="submitButton" disabled>Pay Now</button>
      </form>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Add event listener to update the total amount based on the selected durations
        function updateTotalAmount() {
            let totalAmount = 0;
            let originalAmount = 0;
            let hasDiscount = false;

            // Iterate over selected checkboxes
            document.querySelectorAll('.property-checkbox:checked').forEach(checkbox => {
                const propertyID = checkbox.value;
                const durationSelect = document.querySelector(`.duration[name="duration[${propertyID}]"]`);
                const duration = parseInt(durationSelect.value);

                // Calculate original price without discount
                originalAmount += 2 * duration;

                // Apply discount based on duration
                if (duration === 14) {
                    totalAmount += 0.9 * (2 * duration); // 10% discount for 14 days
                    hasDiscount = true;
                } else if (duration === 30) {
                    totalAmount += 0.8 * (2 * duration); // 20% discount for 30 days
                    hasDiscount = true;
                } else {
                    totalAmount += 2 * duration;
                }
            });

            // Display original price slashed only when there is a discount
            const totalAmountElement = document.getElementById('totalAmount');
            if (hasDiscount) {
                totalAmountElement.innerHTML = `RM${totalAmount.toFixed(2)} <span style="text-decoration: line-through; color: red;">RM${originalAmount.toFixed(2)}</span>`;
            } else {
                totalAmountElement.innerText = `RM${totalAmount.toFixed(2)}`;
            }

            // Update hidden input value for form submission
            document.getElementById('amount').value = totalAmount;

            // Disable/Enable submit button based on total amount
            const submitButton = document.getElementById('submitButton');
            submitButton.disabled = totalAmount <= 0;
        }


        // Add event listener to update total amount on checkbox change
        document.querySelectorAll('.property-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateTotalAmount);
        });

        // Add event listener to update total amount when duration is updated
        document.querySelectorAll('.duration').forEach(select => {
            select.addEventListener('change', updateTotalAmount);
        });

        // Check/Uncheck All
        document.getElementById('check-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.property-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            updateTotalAmount();
        });

        // Update total amount on page load
        updateTotalAmount();
    });
</script>


    @endsection
</body>

</html>