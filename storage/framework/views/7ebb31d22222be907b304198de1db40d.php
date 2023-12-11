<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .btn-custom {
        width: 20%; /* Set the width as needed */
      
    }

    .btn-action {
        width: 30%; /* Set the width as needed */
      
    }
</style>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 container">
        <?php echo csrf_field(); ?>
        <?php if(\Session::has('success')): ?>
        <div class="alert alert-success">
            <p><?php echo e(\Session::get('success')); ?></p>
        </div><br />
        <?php endif; ?>
        <h2>Appointments</h2>
        <a href="<?php echo e(route('timeslots.create')); ?>" class="btn btn-primary btn-custom">Set Up Timeslot Availability</a>
        <a href="<?php echo e(route('timeslots')); ?>" class="btn btn-primary btn-custom">View Available Timeslot</a>
        </br></br>
        <h3>Upcoming Appointments</h3>
        <p> Notes :To ensure prompt attendance, you can set reminders for scheduled appointments.</p>
<p> Both tenants and agents will going to receive a reminder notification one day and one hour prior to the scheduled appointment date.</p>
        <?php if(count($appointments) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Property</th>
                    <th>Timeslot</th>
                    <th>Tenant</th>
                    <th>Number of Viewer</th>
                    <th>Status</th>
                    <th width="40%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($appointment->appID); ?></td>
                    <td><?php echo e($appointment->property->propertyName); ?></td>
                    <td><?php echo e($appointment->timeslot->date); ?> ( <?php echo e($appointment->timeslot->startTime); ?> - <?php echo e($appointment->timeslot->endTime); ?> )</td>
                    <td><?php echo e($appointment->tenant->tenantName); ?></td>
                    <td><?php echo e($appointment->headcount); ?></td>
                    <td><?php echo e($appointment->status); ?></td>
                    <td>
                    <?php if($appointment->timeslot->date > now()&&$appointment->status=="Pending"): ?>
                        <!-- Display information or message indicating that the status is completed -->
                        <?php if($appointment->reminder == 1): ?>
                        <a href="#" class="btn btn-success disabled btn-action" >Reminder Set</a>
                        <?php else: ?>
                        <a href="<?php echo e(route('appointments.setReminder', $appointment->appID)); ?>" id="setReminderBtn" class="btn btn-warning btn-action">Set Reminder</a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('appointments.edit', $appointment->appID)); ?>"
                            class="btn btn-primary btn-action">Modify</a>
                        <a href="<?php echo e(route('appointments.show', $appointment->appID)); ?>"
                            class="btn btn-danger btn-action">Cancel</a>
                        <?php else: ?>
                       <span>Completed</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php echo e($appointments->links()); ?>

        <?php else: ?>
        <p>No upcoming appointments..</p>
        <?php endif; ?>
    </div>

    <!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Confirmation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeBtn">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to set a reminder for this appointment?
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelSetReminderBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmSetReminderBtn">Yes, Set Reminder</button>
            </div>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    // Wait for the document to be ready
    document.addEventListener("DOMContentLoaded", function () {
        // Get the "Set Reminder" button
        const setReminderBtn = document.getElementById('setReminderBtn');

        // Add a click event listener
        setReminderBtn.addEventListener('click', function (event) {
            // Prevent the default link behavior
            event.preventDefault();

            // Show the confirmation modal
            $('#confirmationModal').modal('show');
        });

        // Get the "Cancel" button from the modal
        const cancelSetReminderBtn = document.getElementById('cancelSetReminderBtn');

        // Add a click event listener to the cancel button
        cancelSetReminderBtn.addEventListener('click', function () {
            // Close the confirmation modal
            $('#confirmationModal').modal('hide');
        });

  // Get the "Close" button from the modal
  const closeBtn = document.getElementById('closeBtn');

// Add a click event listener to the 'Close' button
closeBtn.addEventListener('click', function () {
    // Close the confirmation modal
    $('#confirmationModal').modal('hide');
});
        // Get the "Yes, Set Reminder" button from the modal
        const confirmSetReminderBtn = document.getElementById('confirmSetReminderBtn');

        // Add a click event listener to the confirmation button
        confirmSetReminderBtn.addEventListener('click', function () {
            // Redirect to the setReminder route when confirmed
            window.location.href = setReminderBtn.getAttribute('href');
        });
    });
</script>


    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/appointmentIndex.blade.php ENDPATH**/ ?>