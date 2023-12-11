<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Timeslot</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="container mt-5">
    <a href="<?php echo e(route('appointments.agentIndex')); ?>" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        
                        <h2 class="ml-3 mb-0">Add Timeslot</h2>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="<?php echo e(route('timeslots.store')); ?>">
                            <?php echo csrf_field(); ?>

                            <div class="form-group">
                                <label for="date">Date:</label>
                                <input type="date" id="date" name="date" class="form-control" required>
                                <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="form-group">
                                <label>Select Timeslots:</label>
                                <div class="row">
                                    <?php
                                    $start_time = strtotime('08:00');
                                    $end_time = strtotime('18:00');
                                    $interval = 30 * 60; // 30 minutes in seconds
                                    ?>
                                    <?php for($time = $start_time; $time <= $end_time; $time += $interval): ?>
                                    <?php
                                    $formatted_time = date('H:i', $time);
                                    $formattedEnd_time = date('H:i', $time + 30*60);
                                    ?>
                                    <div class="col-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="timeslots[]"
                                                value="<?php echo e($formatted_time); ?>" id="<?php echo e($formatted_time); ?>">
                                            <label class="custom-control-label"
                                                for="<?php echo e($formatted_time); ?>"><?php echo e($formatted_time); ?> - <?php echo e($formattedEnd_time); ?></label>
                                        </div>
                                    </div>
                                    <?php endfor; ?>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <?php $__env->stopSection(); ?>
</body>

</html>

<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/timeslotCreate.blade.php ENDPATH**/ ?>