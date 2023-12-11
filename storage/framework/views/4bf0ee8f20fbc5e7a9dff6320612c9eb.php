<html>

<head>
    <meta charset="UTF-8">
    <title>Wallet</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    <style>

.wallet{

    max-width:90%;
}

    </style>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 wallet">
        <?php echo csrf_field(); ?>
        <?php if(\Session::has('success')): ?>
        <div class="alert alert-success">
            <p><?php echo e(\Session::get('success')); ?></p>
        </div><br />
        <?php endif; ?>

        <?php if(\Session::has('error')): ?>
        <div class="alert alert-danger">
            <p><?php echo e(\Session::get('error')); ?></p>
        </div><br />
        <?php endif; ?>
        <h2>Agent Wallet</h2> ID : <?php echo e($walletID); ?>



        <div class="row">
            <div class="col-md-4">
                <h2>Your Balance: RM<?php echo e($walletBalance); ?></h2>
                <a href="<?php echo e(route('pendingPayment')); ?>" class="link-secondary">View Pending Payment</a>
            </div>
            <div class="col-md-6">
                <a href="<?php echo e(route('makePayment')); ?>" class="btn btn-primary btn-block mt-2">Make Payment for Rental
                    Posting</a> </br>
                <a href="<?php echo e(route('topUpMoney')); ?>" class="btn btn-secondary btn-block  mt-2">Top Up Wallet</a></br>
                <a href="<?php echo e(route('withdrawMoney')); ?>" class="btn btn-success btn-block mt-2">Withdraw Money to
                    Bank</a></br>
            </div>
        </div>
        <h2>Payment History</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Description</th>
                    <th>Amount</th>
                    <th>Transaction</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $agentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($transaction-> transactionID); ?></td>
                    <td><?php echo e($transaction->transactionType); ?></td>
                    <td>RM<?php echo e($transaction->transactionAmount); ?></td>
                    <td><?php echo e(Carbon\Carbon::parse($transaction->created_at)->diffForHumans()); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <?php echo e($agentTransactions->links()); ?>


    </div>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/walletIndex.blade.php ENDPATH**/ ?>