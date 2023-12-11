<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .donut-inner {
            margin-top: 40%;

        }

        .donut-inner span {
            font-size: 50px;
            margin-left: -50vh;
        }
    </style>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 container">
    <a href="<?php echo e(route('reports')); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back
        </a>
        <div class="row">
        <div class="col-6">
        <h2>Rental Transaction Report</h2>
        <p><strong>Period:</strong> <?php echo e($startDate->format('d F Y')); ?> - <?php echo e($endDate->format('d F Y')); ?></p>
    </div>
    <div class="col-3">
        <button class="btn btn-primary py-3 px-3 mt-2" onclick="printReceipt()">Print Report</button>
</div>
</div>
   
      
        <table class="table">
            <thead>
                <tr>
                    <th>Total Transaction Amount</th>
                    <th>Number of Days</th>
                    <th>Number of Transactions</th>
                    <th>Number of Refund Cases</th>


                    <th>Occupancy Rate</th>
                    <th>Total</th>
                    <th>Current</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo e($data['totalTransactionAmount']); ?></td>
                    <td><?php echo e($data['numberOfDays']); ?></td>
                    <td><?php echo e($data['numberOfTransactions']); ?></td>
                    <td><?php echo e($data['numberOfRefundCases']); ?></td>
                    <td><?php echo e($data['occupancyRate']); ?> %</td>
                    <td><?php echo e($data['numberOfProperties']); ?></td>
                    <td><?php echo e($data['numberOfOccupancy']); ?></td>
                </tr>
            </tbody>
        </table>

        <h2>Occupancy Rate</h2>
        <div wire:ignore>
            <div class="chart-container d-flex justify-content-center" style="height: 50vh; width: 50vh;">
                <canvas id="donutChart"></canvas>
                <div class="donut-inner d-flex justify-content-center">
                    <span><?php echo e($data['occupancyRate']); ?> %</span>
                </div>
            </div>
        </div>

        <h3>Transaction Types:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Transaction Type</th>
                    <th>No. of Transaction</th>
                    <th>No. of Refund</th>
                    <th>Refund Amount(RM)</th>
                    <th>Percentage</th>
                    <th>Amount(RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $data['transactionTypes']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transactionType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($loop->iteration); ?> </td>
                    <td><?php echo e($transactionType['type']); ?></td>
                    <td><?php echo e($transactionType['numberOfTransactions']); ?></td>
                    <td><?php echo e($transactionType['refundCount']); ?> </td>
                    <td><?php echo e($transactionType['refundAmount']); ?> </td>
                    <?php if($data['numberOfTransactions']!=0): ?>
                    <td>
                        <?php echo e(number_format(
                        ($transactionType['numberOfTransactions'] - $transactionType['refundCount']) /
                        ($data['numberOfTransactions']) * 100,  2 )); ?>%
                    </td>
                    <?php else: ?>
        <td>0%</td>
        <?php endif; ?>
                    <td><?php echo e($transactionType['amount'] - $transactionType['refundAmount']); ?></td>

                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2">Total</th>
                    <th><?php echo e($data['numberOfTransactions']); ?></th>
                    <th><?php echo e($data['numberOfRefundCases']); ?></th>
                    <th><?php echo e($data['totalRefundAmount']); ?></th>
                    <?php if($data['numberOfTransactions']!=0): ?>
                    <th>
                        
                        <?php echo e(number_format(
        
                        (($data['numberOfTransactions'] - $data['numberOfRefundCases']) / $data['numberOfTransactions']) * 100,
                        2
                        )); ?>%
                    </th>
                    <?php else: ?>
        <th>0%</th>
        <?php endif; ?>
                    <th><?php echo e($data['totalTransactionAmount'] - $data['totalRefundAmount']); ?></th>
                </tr>
            </tfoot>
        </table>


        <script>
    document.addEventListener('DOMContentLoaded', function () {
        var ctx = document.getElementById('donutChart').getContext('2d');

        var data = {
            labels: ['Occupied', 'Empty'],
            datasets: [{
                data: [<?php echo e($data['numberOfOccupancy']); ?>, <?php echo e($data['numberOfProperties']); ?>],
                backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(255, 255, 255, 0.5)'],
                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(255, 255, 255, 1)'],
                borderWidth: 1
            }]
        };

        var options = {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    display: false,
                },
                labels: {
                    render: 'percentage',
                    fontColor: 'black',
                    fontSize: 14,
                    fontStyle: 'bold',
                    position: 'default',
                    textMargin: 8,
                    overlap: true,
                }
            },
        };

        // Destroy existing chart if it exists
        if (window.myDonutChart) {
            window.myDonutChart.destroy();
        }

        window.myDonutChart = new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options
        });

        // Draw label
        var fontSize = 20;
        ctx.font = fontSize + "px Arial";
        ctx.fillStyle = 'black';
        ctx.textAlign = 'center';
        ctx.fillText(collectionRateLabel, ctx.canvas.width / 2, ctx.canvas.height / 2);
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<script>
function printReceipt() {
    window.print();
}
</script>
    </div>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/admin/reportShowRental.blade.php ENDPATH**/ ?>