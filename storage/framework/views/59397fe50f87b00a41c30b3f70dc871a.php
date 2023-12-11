<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agents</title>
    <style>
        .agent {
            width: 90%;
        }

        .btn-action{
            width:25%;
        }
    </style>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 agent">

        <h2>Agents</h2>

        <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>

        <a href="<?php echo e(route('createAgent')); ?>" class="btn btn-primary">Create Agent</a>
        </br></br>
        <form action="<?php echo e(route('searchAgent')); ?>" method="get" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Search agents by id, name, phone or email address" value="<?php echo e(isset($searchTerm) ? $searchTerm : ''); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Number of Active Posts</th>
                    <th>Status</th>
                    <th width="30%">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $agents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($agent->agentID); ?></td>
                    <td><?php echo e($agent->agentName); ?></td>
                    <td><?php echo e($agent->agentPhone); ?></td>
                    <td><?php echo e($agent->agentEmail); ?></td>
                    <td><?php echo e($agent->properties()->where('propertyAvailability', 1)->count()); ?></td>
                    <td><?php echo e($agent->status); ?></td>
                    <td>
                        <a href="<?php echo e(route('AgentDetailsAdmin', $agent->agentID)); ?>" class="btn btn-primary btn-action">View</a>
                        <a href="<?php echo e(route('updateAgent', $agent->agentID)); ?>" class="btn btn-warning text-white btn-action">Update</a>
                        <a href="#" class="btn btn-danger btn-action" onclick="confirmDelete(
                            '<?php echo e(route('deleteAgent', $agent->agentID)); ?>',
                            '<?php echo e($agent->agentID); ?>',
                            '<?php echo e($agent->agentName); ?>',
                            '<?php echo e($agent->agentPhone); ?>',
                            '<?php echo e($agent->agentEmail); ?>',
                            '<?php echo e($agent->properties()->where('propertyAvailability', 1)->count()); ?>'
                        )">Delete</a>
                        </td>
                        
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if(count($agents)==0): ?>
                <td>
                    No agent record found.
                </td>
                <?php endif; ?>
            </tbody>
        </table>

        <?php echo e($agents->links()); ?>

    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>



    <!-- Bootstrap Modal for Delete Confirmation -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
        aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Are you sure you want to delete this
                        agent?</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                        onclick="closeDeleteConfirmationModal()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>ID:</strong> <span id="agentID"></span></p>
                    <p><strong>Name:</strong> <span id="agentName"></span></p>
                    <p><strong>Contact Information:</strong> <span id="agentPhone"></span></p>
                    <p><strong>Email:</strong> <span id="agentEmail"></span></p>
                    <p><strong>Number of Active Posts:</strong> <span id="activePosts"></span></p>

                    <!-- Add the deactivation reason input field -->
                    <div class="form-group">
                        <label for="deactivationReason">Deactivation Reason:</label>
                        <input type="text" class="form-control" id="deactivationReason" name="deactivationReason">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"
                        onclick="closeDeleteConfirmationModal()">Cancel</button>
                    <a href="#" id="deleteAgentLink" class="btn btn-danger" onclick="proceedWithDeletion()">Delete</a>
                </div>
            </div>
        </div>
    </div>


    <script>
        function confirmDelete(deleteUrl, agentID, agentName, agentPhone, agentEmail, activePosts) {
            // Set modal content
            $('#agentID').text(agentID);
            $('#agentName').text(agentName);
            $('#agentPhone').text(agentPhone);
            $('#agentEmail').text(agentEmail);
            $('#activePosts').text(activePosts);

            // Show the modal
            $('#deleteConfirmationModal').modal('show');

            // Set the deletion URL
            $('#deleteConfirmationModal').data('deleteUrl', deleteUrl);
        }

        function proceedWithDeletion() {
            // Get the deletion URL from the modal
            var deleteUrl = $('#deleteConfirmationModal').data('deleteUrl');

            // Get the deactivation reason from the input field
            var deactivationReason = $('#deactivationReason').val();

            // Append the deactivation reason to the deletion URL
            deleteUrl += '?deactivationReason=' + encodeURIComponent(deactivationReason);

            // Redirect to the deletion URL
            window.location.href = deleteUrl;
        }


        function closeDeleteConfirmationModal() {
            // Hide the modal
            $('#deleteConfirmationModal').modal('hide');
        }
    </script>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/admin/agentIndex.blade.php ENDPATH**/ ?>