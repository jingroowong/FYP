<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeslot</title>
    <style>
        .timeslot {
            width: 90%;
        }
    </style>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 timeslot">
        <?php echo csrf_field(); ?>
        <?php if(\Session::has('success')): ?>
        <div class="alert alert-success">
            <p><?php echo e(\Session::get('success')); ?></p>
        </div><br />
        <?php endif; ?>
        <a href="<?php echo e(route('appointments.agentIndex')); ?>" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>



        <h2>Timeslots <a href="<?php echo e(route('timeslots.calendar')); ?>" class="btn btn-primary mb-3">
                <i class="las la-calendar"></i>View in Calendar
            </a></h2>
        <div id="calendar"></div>

        <div class="timeslotTable">
            <?php if(count($timeslots) > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $timeslots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $timeslot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($timeslot->timeslotID); ?></td>
                        <td><?php echo e($timeslot->startTime); ?></td>
                        <td><?php echo e($timeslot->endTime); ?></td>
                        <td><?php echo e($timeslot->date); ?></td>
                        <td>
                            <?php
                            $appointments = $timeslot->appointments;
                            $latestAppointment = $appointments->last();
                            $previousAppointment = $appointments->count() > 1 ? $appointments[$appointments->count() -
                            2] : null;
                            ?>

                            <?php if(!$latestAppointment): ?>
                            <form action="<?php echo e(route('timeslots.destroy', $timeslot->timeslotID)); ?>" method="get"
                                style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            <?php else: ?>
                            <div>
                               
                                <?php if($latestAppointment->status == "Pending"): ?>
                                <span class="text-muted">Booked</span>
                                <?php elseif($latestAppointment->status == "Cancelled"): ?>

                                <?php if($previousAppointment): ?>
                                <div>
                                    
                                    <?php if($previousAppointment->status == "Pending"): ?>
                                    <span class="text-muted">Booked</span>
                                    <?php elseif($previousAppointment->status == "Cancelled"): ?>
                                    <span class="text-muted">Cancelled</span>
                                    <?php elseif($previousAppointment->status == "Completed"): ?>
                                    <span class="text-muted">Completed</span>

                                    <?php endif; ?>
                                    <?php else: ?>
                                    <span class="text-muted">Cancelled</span>
                                </div>
                                <?php endif; ?>
                                <?php elseif($latestAppointment->status == "Completed"): ?>
                                <span class="text-muted">Completed</span>
                                <?php endif; ?>
                            </div>


                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                </tbody>
            </table>
            <?php else: ?>
            <p>No timeslots record..</p>
            <?php endif; ?>
            <?php echo e($timeslots->links()); ?>

        </div>
    </div>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/timeslotIndex.blade.php ENDPATH**/ ?>