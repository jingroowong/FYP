<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wallet</title>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2">
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        
    <a href="<?php echo e(route('agentWallet')); ?>" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1>Property Rentals with Pending Payment</h1>
        <?php if(count($pendingRentals) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Property ID</th>
                    <th>Property Name</th>
                    <th>Tenant Name</th>
                    <th>Tenant Contact</th>
                    <th>Pending Amount</th>
                    <th>Due Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php $__currentLoopData = $pendingRentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pendingRental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                        <td><?php echo e($pendingRental->propertyID); ?></td>
                        <td><?php echo e($pendingRental->property->propertyName); ?></td>
                        <td><?php echo e($pendingRental->tenant->tenantName); ?></td>
                        <td><?php echo e($pendingRental->tenant->tenantPhone); ?></td>
                        <td>RM <?php echo e($pendingRental->payment->paymentAmount); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($pendingRental->date)->addDays(14)->toDateString()); ?></td>
                     
                        <td>
                            <a href="<?php echo e(route('agent.requestPayment', $pendingRental->propertyRentalID)); ?>" class="btn btn-success">Request Payment</a>
                        </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No pending payments..</p>
        <?php endif; ?>
    </div>
    <?php $__env->stopSection(); ?>
</body>

</html>

<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/walletPending.blade.php ENDPATH**/ ?>