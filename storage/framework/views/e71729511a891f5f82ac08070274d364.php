<?php $__env->startSection('content'); ?>
<div class="container">
    <?php if(session('success')): ?>
    <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <ul class="nav nav-tabs" id="myTabs">
        <li class="nav-item">
            <a class="nav-link active" id="allRental-tab" data-toggle="tab" href="#allRental">Rentals</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="application-tab" data-toggle="tab" href="#application">Applications</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="paymentHistory-tab" data-toggle="tab" href="#paymentHistory">Payment History</a>
        </li>
    </ul>

    <div class="tab-content">
        <div class="tab-pane fade show active" id="allRental">
            <!-- Content for All Rentals tab -->
            <div class="allRental">
                <h2>Your Properties Rentals</h2>
                <?php if(count($propertyRentals) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th>Property Name</th>
                            <th>Property Address</th>
                            <th>Agent</th>
                            <th>Status</th>

                        </tr>
                    </thead>

                    <tbody>
                        <?php $__currentLoopData = $propertyRentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $propertyRental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($propertyRental->propertyID); ?></td>
                            <td><?php echo e($propertyRental->property->propertyName); ?></td>
                            <td><?php echo e($propertyRental->property->propertyAddress); ?></td>
                            <td><?php echo e($propertyRental->property->agent->agentName); ?></td>
                            <td><?php echo e($propertyRental->rentStatus); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php echo e($propertyRentals->links()); ?>


                <?php else: ?>
                <p>No record found..</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="application">
            <!-- Content for Applications tab -->
            <div class="Application">
                <h2>Your Properties Application</h2>
                <?php if(count($propertyApplications) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th>Property Name</th>
                            <th>Property Address</th>
                            <th>Agent</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__currentLoopData = $propertyApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $propertyRental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($propertyRental->propertyID); ?></td>
                            <td><?php echo e($propertyRental->property->propertyName); ?></td>
                            <td><?php echo e($propertyRental->property->propertyAddress); ?></td>
                            <td><?php echo e($propertyRental->property->agent->agentName); ?></td>
                            <td><?php echo e($propertyRental->rentStatus); ?></td>
                            <td>
                                <?php if($propertyRental->rentStatus == "Applied"): ?>
                                <!-- Display information or message indicating that the status is completed -->
                                <span class="text-muted">Waiting for agent</span>
                                <?php else: ?>
                                <a href="<?php echo e(route('payments.create', $propertyRental->propertyRentalID)); ?>"
                                    class="btn btn-success">Make payment</a>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php echo e($propertyApplications->links()); ?>

                <?php else: ?>
                <p>No record found..</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="tab-pane fade" id="paymentHistory">
            <!-- Content for Payment History tab -->
            <div class="Payment History">
                <h2>Your Payment History</h2>
                <p>Notes : Your are protected, and eligible for a refund in the event of a scam.</p>
                <p> The advanced rental and security deposit will be held for 14 days to ensure the property is in good
                    condition and meets your satisfaction.</p>
                <?php if(count($paymentHistory) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th width="10%">Property Name</th>
                            <th width="20%">Property Address</th>

                            <th>Paid Amount (RM)</th>
                            <th>Payment Date</th>
                            <th>Effective Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__currentLoopData = $paymentHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $propertyRental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($propertyRental->propertyID); ?></td>
                            <td><?php echo e($propertyRental->property->propertyName); ?></td>
                            <td><?php echo e($propertyRental->property->propertyAddress); ?></td>

                            <td><?php echo e($propertyRental->payment->paymentAmount); ?></td>
                            <td><?php echo e($propertyRental->payment->paymentDate); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($propertyRental->effectiveDate)->format('Y-m-d')); ?></td>

                            <td>
                                <?php if($propertyRental->rentStatus == "Completed"): ?>
                                <!-- Display information or message indicating that the status is completed -->
                                <a href="<?php echo e(route('payments.paymentReceipt', $propertyRental->propertyRentalID)); ?>"
                                    class="btn btn-success">View Receipt</a>
                                <?php elseif($propertyRental->rentStatus == "Refund requested"): ?>
                                <!-- Display information or message indicating that the status is completed -->
                                <span class="text-muted">Refund Requested</span>
                                <?php elseif($propertyRental->rentStatus == "Refund approved"): ?>
                                <!-- Display information or message indicating that the status is completed -->
                                <span class="text-muted">Refund Approved</span>
                                <?php elseif($propertyRental->rentStatus == "Refund rejected"): ?>
                                <!-- Display information or message indicating that the status is completed -->
                                <span class="text-muted">Refund Rejected</span>
                                <?php else: ?>
                                <!-- Display buttons only if the status is not completed -->

                                <a href="<?php echo e(route('payments.release', $propertyRental->propertyRentalID)); ?>"
                                    class="btn btn-success">Release Fund</a>
                            </td>
                            <td>
                                <a href="<?php echo e(route('refunds.create', $propertyRental->propertyRentalID)); ?>"
                                    class="btn btn-danger">Make Refund</a>
                                <?php endif; ?>
                            </td>
                        </tr>

                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php echo e($paymentHistory->links()); ?>

                <?php else: ?>
                <p>No record found..</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function() {
        $('#myTabs a').on('click', function(e) {
            e.preventDefault()
            $(this).tab('show')
        })
    });
    </script>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/tenant/propertyRentApplication.blade.php ENDPATH**/ ?>