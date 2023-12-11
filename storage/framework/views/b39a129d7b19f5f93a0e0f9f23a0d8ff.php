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
    </style>
</head>

<body>


<?php $__env->startSection('content'); ?>
<div class="ml-5 mt-2">

        <h2>Appointment details</h2>
        <div class="row">
            <!-- Display Property Photo -->
            <div class="col-md-6 propertyPhoto">
                <img src="<?php echo e(Storage::url($appointment->property->propertyPhotos[0]->propertyPath)); ?>"
                    alt="Property Photo">
                <!-- Step 3: Appointment Preview -->
                <div>
                    <!-- Display appointment details for preview -->

                    <div class="preview-details">
                        <table>
                            <tr>
                                <td>
                                    <p><strong>Property : </strong>
                                </td>
                                <td> <?php echo e($appointment->property->propertyName); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Location : </strong>
                                </td>
                                <td> <?php echo e($appointment->property->propertyAddress); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Date : </strong>
                                </td>
                                <td> <?php echo e($appointment->timeslot->date); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Time : </strong>
                                </td>
                                <td><?php echo e($appointment->timeslot->startTime); ?> - <?php echo e($appointment->timeslot->endTime); ?> </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Name : </strong>
                                </td>
                                <td> <?php echo e($appointment->name); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Email : </strong>
                                </td>
                                <td><?php echo e($appointment->email); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Contact Number : </strong>
                                </td>
                                <td> <?php echo e($appointment->contactNo); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Number of Viewers : </strong>
                                </td>
                                <td> <?php echo e($appointment->headcount); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Message to agent: </strong>
                                </td>
                                <td> <?php echo e($appointment->message); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">

                <div>
                <?php if($appointment->status == "Pending"): ?>
                    <h4>Are you sure to cancel the appointment?</h4>
                    <a href="<?php echo e(route('appointments.agentIndex')); ?>" class="btn btn-secondary">Back</a>
                    <a href="<?php echo e(route('appointments.agentCancel', $appointment->appID)); ?>" class="btn btn-danger"> Confirm
                        Cancel</a>
                        <?php else: ?>
                        <p>The appointment had <?php echo e($appointment->status); ?>.</p>
                        <?php endif; ?>
                </div>

            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/appointmentDelete.blade.php ENDPATH**/ ?>