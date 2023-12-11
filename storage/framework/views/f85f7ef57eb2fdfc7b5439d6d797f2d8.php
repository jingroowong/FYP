<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Appointments</title>
    
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .btn-action {
       width:30%;
    }
    </style>

</head>

<body>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <?php echo csrf_field(); ?>
        <?php if(\Session::has('success')): ?>
        <div class="alert alert-success">
            <p><?php echo e(\Session::get('success')); ?></p>
        </div><br />
        <?php endif; ?>
        <h2 class="mt-4 mb-4">Upcoming Appointments</h2>
        <p> Notes :To ensure prompt attendance, you can set reminders for scheduled appointments.</p>
<p> Both tenants and agents will going to receive a reminder notification one day and one hour prior to the scheduled appointment date.</p>
        <?php if(count($appointments) > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Property</th>
                    <th>Timeslot</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                <td><?php echo e($appointment->appID); ?></td>
                    <td><?php echo e($appointment->property->propertyName); ?></td>
                    <td width="30%"><?php echo e($appointment->timeslot->date); ?> ( <?php echo e($appointment->timeslot->startTime); ?> - <?php echo e($appointment->timeslot->endTime); ?> )</td>
                    <td><?php echo e($appointment->status); ?></td>
                    <td width="40%">
                    <?php if($appointment->timeslot->date > now()&&$appointment->status=="Pending"): ?>
                        <!-- Display information or message indicating that the status is completed -->
                        <?php if($appointment->reminder == 1): ?>
                        <a href="#" class="btn btn-success disabled btn-action" >Reminder Set</a>
                        <?php else: ?>
                        <a href="<?php echo e(route('appointments.setReminder', $appointment->appID)); ?>" id="setReminderBtn" class="btn btn-warning btn-action text-white">Set Reminder</a>
                        <?php endif; ?>
                        <a href="<?php echo e(route('appointments.editTenant', $appointment->appID)); ?>"
                            class="btn btn-primary btn-action">Modify</a>
                        <a href="<?php echo e(route('appointments.showTenant', $appointment->appID)); ?>"
                            class="btn btn-danger btn-action">Cancel</a>
                            <?php else: ?>
                       <span>Completed</span>
                          <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <?php else: ?>
        <p>No upcoming appointments..</p>
        <?php endif; ?>
       
        <?php echo e($appointments->links()); ?>

    </div>
    <?php $__env->stopSection(); ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/tenant/appointmentIndex.blade.php ENDPATH**/ ?>