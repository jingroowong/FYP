<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    
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
    </style>
</head>

<body>
    <?php $__env->startSection('content'); ?>
    <div class="container">
        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back</a>
        <h2 class="mt-4 mb-4">Book Appointment</h2>

        <!-- Display Property Details -->
        <div class="property-details">
        <p>Property Name : <strong><?php echo e($property->propertyName); ?></strong> </p>
            <p> Property Address : <strong><?php echo e($property->propertyAddress); ?></strong> </p>
        </div>
        <form action="<?php echo e(url('appointments')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <div class="row">

                <div class="col-md-6">
                    <div class="appointment-step" id="step-1">
                        <h3>Step 1: Choose Appointment Timeslot</h3>
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
                    </br>
                    <!-- Step 2: Personal Data -->
                    <div class="appointment-step" id="step-2">
                        <h3>Step 2: Personal Data</h3>
                        <!-- Personal data form -->

                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" id="name" value="<?php echo e($tenant->tenantName); ?>"
                            required />

                        <label for="email">Email:</label>
                        <input type="email" class="form-control" name="email" id="email"
                            value="<?php echo e($tenant->tenantEmail); ?>" required />

                        <label for="contact_number">Contact Number:</label>
                        <input type="tel" class="form-control" name="contact_number" id="contact_number"
                            value="<?php echo e($tenant->tenantPhone); ?>" required />

                        <label for="num_of_viewers">Number of Viewers:</label>
                        <input type="number" class="form-control" name="num_of_viewers" id="num_of_viewers"
                            placeholder="1-5" required />
                        </br></br>
                        <!-- Additional appointment rules section -->
                        <h3> Step 3 : Read Appointment Rules</h3>
                        <div class="row appointment-rules">
                            <div class="col-md-6">
                                <!-- Viewing time rule -->
                                <div class="rule">
                                    <div class="icon"><i class="las la-clock"></i> Viewing time</div>
                                    <div class="details">
                                        <p>From 10 AM Until 6 PM</p>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <!-- Waiting time rule -->
                                <div class="rule">
                                    <div class="icon"><i class="las la-times"></i> Waiting Time</div>
                                    <div class="details">
                                        <p>Exceed 30 minutes would not be attended</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <!-- No pets allowed rule -->
                                <div class="beware">
                                    <i class="las la-paw"></i> No pets allowed
                                </div>
                            </div>
                            <div class="col-md-6">
                                <!-- No smoking rule -->
                                <div class="beware">
                                    <i class="las la-smoking-ban"></i> No smoking
                                </div>
                            </div>
                        </div>
                        </br>

                      
                    </div>
                </div>

                <!-- Display Property Photo -->
                <div class="col-md-6 propertyPhoto">
                <h3> Step 4 : Confirm Appointment</h3>
                    <img src="<?php echo e(Storage::url($property->propertyPhotos[0]->propertyPath)); ?>" alt="Property Photo">
                    <!-- Step 3: Appointment Preview -->
                    <div class="appointment-step" id="step-3">
                      
                        <!-- Display appointment details for preview -->

                        <div class="preview-details">
                            <table>
                                <tr>
                                    <td>
                                        <p><strong>Date : </strong>
                                    </td>
                                    <td> <span id="previewTimeslotDate"></span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><strong>Time : </strong>
                                    </td>
                                    <td> <span id="previewTimeslotTime"></span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><strong>Name : </strong>
                                    </td>
                                    <td> <span id="previewName"></span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><strong>Email : </strong>
                                    </td>
                                    <td><span id="previewEmail"></span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><strong>Contact Number : </strong>
                                    </td>
                                    <td> <span id="previewContactNumber"></span></p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p><strong>Number of Viewers : </strong>
                                    </td>
                                    <td> <span id="previewNumOfViewers"></span></p>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <strong> <label for="message">Message to Agent:</label></strong>
                        <textarea name="message" class="form-control" id="message"></textarea>


                            <div class="mt-3 form-check">
                                <input class="form-check-input" type="checkbox" id="readRulesCheckbox"
                                    onchange="toggleSubmitButton()">
                                <label class="form-check-label" for="readRulesCheckbox">
                                    I agree to RentSpace's Terms of Service and Privacy Policy including the collection,
                                    use
                                    and disclosure of my personal information.
                                </label>
                            </div>
                  
                        <div class="btn-container">
                            <input type="hidden" name="timeslotID" id="timeslotID" value="">
                            <input type="hidden" name="propertyID" value="<?php echo e($property->propertyID); ?>">
                            <button type="submit" class="btn btn-success" id="submitButton" disabled>Book Now</button>
                        </div>
                    </div>
                </div>
        </form>
    </div>


    <script>
    function toggleSubmitButton() {
        var checkbox = document.getElementById('readRulesCheckbox');
        var submitButton = document.getElementById('submitButton');

        // Enable the Next button if the checkbox is checked, otherwise disable it
        submitButton.disabled = !checkbox.checked;
    }

    function updateTimeslots() {
        // Retrieve selected date
        var selectedDate = document.getElementById('date').value;

        // Convert selected date to timestamp
        var selectedTimestamp = new Date(selectedDate).getTime();

        // Convert available timeslots to timestamps and filter
        var filteredTimeslots = <?php echo json_encode($availableTimeslots); ?>.filter(function(timeslot) {
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

    }

    // Function to update preview details
    function updatePreview() {
        // Display the selected timeslot in the preview
        var selectedTimeslot = document.getElementById('timeslot').value;

        // Split the value into date and time
        var [timeslotID, selectedDate, selectedStartTime, selectedEndTime] = selectedTimeslot.split(' ');

        // Now you can use selectedDate and selectedTime as needed
        document.getElementById('previewTimeslotDate').innerText = selectedDate;
        document.getElementById('previewTimeslotTime').innerText = selectedStartTime + '-' + selectedEndTime;
        document.getElementById('timeslotID').value = timeslotID;

        document.getElementById('previewName').innerText = document.getElementById('name').value;
        document.getElementById('previewEmail').innerText = document.getElementById('email').value;
        document.getElementById('previewContactNumber').innerText = document.getElementById('contact_number').value;
        document.getElementById('previewNumOfViewers').innerText = document.getElementById('num_of_viewers').value;
    }

    // Attach the function to form input change events
    document.getElementById('timeslot').addEventListener('change', updatePreview);
    document.getElementById('name').addEventListener('input', updatePreview);
    document.getElementById('email').addEventListener('input', updatePreview);
    document.getElementById('contact_number').addEventListener('input', updatePreview);
    document.getElementById('num_of_viewers').addEventListener('input', updatePreview);
    </script>
    <?php $__env->stopSection(); ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/tenant/appointmentCreate.blade.php ENDPATH**/ ?>