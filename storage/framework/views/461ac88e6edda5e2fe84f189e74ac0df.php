<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 container">
        <h2>Reports</h2>
        <div class="row">
            <div class="col-6">
                <form action="<?php echo e(route('reports.generate')); ?>" method="post">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label for="report_type">Select Report Type:</label>
                        <select class="form-control" name="report_type">
                            <option value="rental_transaction">Rental Transaction</option>
                            <option value="agent_fees">Agent Fees</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="month">Select Month:</label>
                        <input type="month" class="form-control" name="month" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Show</button>
                </form>
            </div>
        </div>
    </div>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/admin/reportIndex.blade.php ENDPATH**/ ?>