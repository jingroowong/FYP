<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund</title>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2">
    <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <h2>Refunds</h2>

    <?php if(count($refunds) > 0): ?>
    <table class="table">
        <thead>
            <tr>
               
                <th>Property Rental ID</th>
                <th>Refund Amount</th>
                <th>Refund Date</th>
                <th>Status</th>
                <th>Tenant</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $refunds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $refund): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
               
                <td><?php echo e($refund->propertyRental->propertyRentalID); ?></td>
                <td><?php echo e($refund->propertyRental->payment->paymentAmount); ?></td>
                <td><?php echo e($refund->refundDate); ?></td>
                <td><?php echo e($refund->refundStatus); ?></td>
                
                <td><?php echo e($refund->propertyRental->tenant->tenantName); ?></td>
                <td>

                    <!-- Display buttons only if the status is not completed -->
                    <a href="<?php echo e(route('refunds.show', $refund->refundID)); ?>" class="btn btn-primary">Check</a>


                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    <?php else: ?>
    <p>No refunds available.</p>
    <?php endif; ?>
</div>


<?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/admin/refundIndex.blade.php ENDPATH**/ ?>