<html>

<head>
    <meta charset="UTF-8">
    <title>Notifications</title>

    <!-- Use either Bootstrap 4.5.2 or Bootstrap 4.6.0, not both -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  </head>


<link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
<?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
<style>
.unreadNote {
    font-size: 15px;
}

.notification {

    max-width: 90%;
}
</style>

</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 notification">
        <?php echo csrf_field(); ?>
        <?php if(\Session::has('success')): ?>
        <div class="alert alert-success">
            <p><?php echo e(\Session::get('success')); ?></p>
        </div><br />
        <?php endif; ?>
        <h2>Notifications <span class="text-muted unreadNote"> (<?php echo e($count); ?> notifications)</span>
        </h2>

        <!-- Search Bar -->
        <form action="<?php echo e(route('notifications.agentSearch')); ?>" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Search notifications"
                    value="<?php echo e(isset($searchTerm) ? $searchTerm : ''); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <?php if(count($notifications) > 0): ?>
        <form action="#" method="POST" id="notification-form">
            <?php echo csrf_field(); ?>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="check-all">
                            </th>
                            <th>Subject</th>
                            <th width="60%">Content</th>
                            <th>Time Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $notifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <input type="checkbox" class="notification-checkbox" name="notification[]"
                                    value="<?php echo e($notification->notificationID); ?>">
                            </td>
                            <td><?php echo e($notification->subject); ?></td>
                            <td><?php echo e($notification->content); ?></td>
                            <td><?php echo e(Carbon\Carbon::parse($notification->timestamp)->diffForHumans()); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <?php echo e($notifications->links()); ?>

            <div class="row">

                <div class="col">
                    <button class="btn btn-danger" id="delete" type="button" data-toggle="modal"
                        data-target="#confirmDeleteModal">Delete</button>

                </div>
            </div>
            <?php else: ?>
            <p>No notifications available.</p>
            <?php endif; ?>

            <!-- Add this code to your HTML body -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
                aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete the selected notifications?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check/Uncheck All
            document.getElementById('check-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.notification-checkbox');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            // Handle Delete Button Click
            document.getElementById('delete').addEventListener('click', function() {
                // Show the Bootstrap Modal
                $('#confirmDeleteModal').modal('show');
            });

            // Handle Confirm Delete Button Click
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                // Proceed with the form submission
                document.getElementById('notification-form').action = '<?php echo e(route('notifications.delete')); ?>';
                document.getElementById('notification-form').submit();

                // Close the Bootstrap Modal
                $('#confirmDeleteModal').modal('hide');
            });

              // Handle Close Button Click
        document.querySelector('#confirmDeleteModal .close').addEventListener('click', function () {
            // Manually close the Bootstrap Modal
            $('#confirmDeleteModal').modal('hide');
        });

        // Handle Cancel Button Click
        document.querySelector('#confirmDeleteModal .btn-secondary').addEventListener('click', function () {
            // Manually close the Bootstrap Modal
            $('#confirmDeleteModal').modal('hide');
        });
        });
    </script>






    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/notificationIndex.blade.php ENDPATH**/ ?>