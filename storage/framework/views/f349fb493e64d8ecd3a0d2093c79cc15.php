<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment detail</title>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
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

        .table td {
            min-width: 150px;
        }
    </style>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2">
        <a href="<?php echo e(route('appointments.agentIndex')); ?>" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h2 class="mt-4 mb-3 d-flex justify-content-center fw-bold">Appointment details</h2>
        <hr class="mb-5">
        <div class="row">
            <!-- Display Property Photo -->
            <div class="col-md-6 propertyPhoto d-flex justify-content-center mb-3">
                <img src="<?php echo e(Storage::url($appointment->property->propertyPhotos[0]->propertyPath)); ?>"
                    alt="Property Photo">
            </div>
            <div class="col-md-6 mb-5">
                <form action="<?php echo e(route('appointments.updateByAgent', $appointment->appID)); ?>" method="get">
                    <?php echo csrf_field(); ?>

                    <div class="appointment-step">
                        <h4>Selects a new date and time slots</h4>
                        <!-- Display available dates using date picker -->
                        <label for="date">Select Date:</label>
                        <input type="date" id="date" name="date" onchange="updateTimeslots()">

                        <!-- Display available timeslots here -->
                        <label for="timeslot">Select Timeslot:</label>
                        <select name="timeslot" id="timeslot" disabled>

                            <!-- Timeslots will be dynamically populated here -->
                        </select>

                        <div id="availability-message"></div>
                        <div id="date-list" class="small"></div>
                    </div>
                    <input type="hidden" name="timeslotID" id="timeslotID" value="">
                    <input type="submit" class="btn btn-success" value="Confirm Update">
                </form>
            </div>
        </div>
        <!-- Display appointment details for preview -->

        <div class="row mt-3">
            <div class="preview-details col-6 px-5">
                <table class="table">
                    <tr>
                        <td>
                            <strong>Property : </strong>
                        </td>
                        <td> <?php echo e($appointment->property->propertyName); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Location : </strong>
                        </td>
                        <td> <?php echo e($appointment->property->propertyAddress); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Date : </strong>
                        </td>
                        <td> <?php echo e($appointment->timeslot->date); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Time : </strong>
                        </td>
                        <td><?php echo e($appointment->timeslot->startTime); ?> - <?php echo e($appointment->timeslot->endTime); ?>

                        </td>
                    </tr>

                </table>
            </div>
            <div class="preview-details col-6 px-5">
                <table class="table">

                    <tr>
                        <td>
                            <strong>Name : </strong>
                        </td>
                        <td> <?php echo e($appointment->name); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Email : </strong>
                        </td>
                        <td><?php echo e($appointment->email); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Contact Number : </strong>
                        </td>
                        <td> <?php echo e($appointment->contactNo); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Number of Viewers : </strong>
                        </td>
                        <td> <?php echo e($appointment->headcount); ?>

                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Message by tenant: </strong>
                        </td>
                        <td> <?php echo e($appointment->message); ?>

                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>




    <script>
    function updateTimeslots() {
        // Retrieve selected date
        var selectedDate = document.getElementById('date').value;

        // Convert selected date to timestamp
        var selectedTimestamp = new Date(selectedDate).getTime();

        // Convert available timeslots to timestamps and filter
        var filteredTimeslots = <?php echo json_encode($availableTimeslots); ?>.filter(function(
            timeslot) {
            var timeslotTimestamp = new Date(timeslot.date).getTime();
            return timeslotTimestamp === selectedTimestamp;
        });

        var timeslotSelect = document.getElementById('timeslot');

        // Clear existing options
        timeslotSelect.innerHTML = '';

        // Enable the 'timeslot' select element
        timeslotSelect.disabled = false;

        // Populate options with filtered timeslots
        for (var i = 0; i < filteredTimeslots.length; i++) {
            var option = document.createElement('option');
            option.value = filteredTimeslots[i].timeslotID + ' ' + filteredTimeslots[i].date + ' ' +
                filteredTimeslots[
                    i].startTime + ' ' + filteredTimeslots[i].endTime;
            option.text = filteredTimeslots[i].startTime + ' - ' + filteredTimeslots[i].endTime;
            timeslotSelect.add(option);
        }
            // Auto-select the first option
            timeslotSelect.selectedIndex = 0;

// Trigger the updatePreview function after auto-selecting the first option
updatePreview();

        var messageContainer = document.getElementById('availability-message');
        var dateListContainer = document.getElementById('date-list');


        // Display availability message
        if (filteredTimeslots.length > 0) {
            messageContainer.innerHTML = 'Timeslots are available on this date.';
            messageContainer.style.color = '#5cb85c'; // Green color for positive message
        } else {
            messageContainer.innerHTML = 'No available timeslots on this date.';
            messageContainer.style.color = '#d9534f'; // Red color for negative message
        }

        // Display list of distinct available dates
        var distinctDates = <?php echo json_encode($availableDates); ?>;
        dateListContainer.innerHTML = 'Hints : Available Dates = ' + distinctDates.join(', ');


        // Function to update preview details
        function updatePreview() {
            // Display the selected timeslot in the preview
            var selectedTimeslot = document.getElementById('timeslot').value;

            // Split the value into date and time
            var [timeslotID, selectedDate, selectedStartTime, selectedEndTime] = selectedTimeslot.split(' ');

            document.getElementById('timeslotID').value = timeslotID;

        }

        // Attach the function to form input change events
        document.getElementById('timeslot').addEventListener('change', updatePreview);

    }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/appointmentUpdate.blade.php ENDPATH**/ ?>