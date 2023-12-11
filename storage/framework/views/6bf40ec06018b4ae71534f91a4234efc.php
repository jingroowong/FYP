<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties</title>
    <style>
    .btn-custom {
        width: 30%; /* Set the width as needed */
      
    }
</style>


</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 container">
        <h2>Properties</h2>

        <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
        <?php endif; ?>
        <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
        <?php endif; ?>

        <a href="<?php echo e(route('createProperty')); ?>" class="btn btn-success mb-4"> + Create Property</a>


        <!-- Search Bar -->
        <form action="<?php echo e(route('properties.search')); ?>" method="get" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                    placeholder="Search properties by name, location, or type"  value="<?php echo e(isset($searchTerm) ? $searchTerm : ''); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
        </form>

        <ul class="nav nav-tabs" id="myTabs">
            <li class="nav-item">
                <a class="nav-link active" id="properties-tab" data-toggle="tab" href="#properties">Properties</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="rentals-tab" data-toggle="tab" href="#rentals">Rentals</a>
            </li>
        </ul>

        <div class="tab-content mt-2">
            <div class="tab-pane fade show active" id="properties">
                <?php if($properties!=null): ?>
                <?php if(count($properties) > 0 ): ?>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Property ID</th>
                            <th>Property Name</th>
                            <th>Property Type</th>
                            <th>Property Address</th>
                            <th>Active Until</th>
                            <th>Availability</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php $__currentLoopData = $properties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $property): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td width="5%"><?php echo e($property->propertyID); ?></td>
                            <td width="10%"><?php echo e($property->propertyName); ?></td>
                            <td width="5%"><?php echo e($property->propertyType); ?></td>
                            <td width="15%"><?php echo e($property->propertyAddress); ?></td>
                            <?php if( $property->expiredDate > now() ): ?>
                            <td width="10%"> <?php echo e(\Carbon\Carbon::parse($property->expiredDate)->format('Y-m-d')); ?></td>
                            <?php else: ?>
                            <td width="5%">Expired</td>
                            <?php endif; ?>
                            <?php if( $property->propertyAvailability == true ): ?>
                            <td width="5%">Active</td>
                            <?php else: ?>
                            <td width="5%">N/A</td>
                            <?php endif; ?>

                            <td width="30%">
                                <a href="<?php echo e(route('properties.showAgent', $property->propertyID)); ?>"
                                    class="btn btn-primary btn-custom">View</a>
                                <a href="<?php echo e(route('properties.edit', $property->propertyID)); ?>"
                                    class="btn btn-primary btn-custom">Update</a>
                                <a href="#" class="btn btn-danger btn-custom" onclick="confirmDelete(
                                '<?php echo e(route('properties.destroy', $property->propertyID)); ?>',
                                '<?php echo e($property->propertyName); ?>',
                                '<?php echo e($property->propertyType); ?>',
                                '<?php echo e($property->propertyAddress); ?>',
                                '<?php echo e(Storage::url($property->propertyPhotos[0]->propertyPath)); ?>'
                            )">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php echo e($properties->links()); ?>

                <?php else: ?>
                <p>No record found..</p>
                <?php endif; ?>
                <?php endif; ?>
            </div>

            <div class="tab-pane fade" id="rentals">
                <?php if($propertyRentals != null): ?>

                <?php if(count($propertyRentals) > 0): ?>

                <table class="table">
                    <thead>
                        <tr>
                            <th>Rental ID</th>
                            <th>Property Name</th>
                            <th>Property Address</th>
                            <th>Tenant</th>
                            <th>Tenant Email</th>
                            <th>Tenant Phone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php $__currentLoopData = $propertyRentals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $propertyRental): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td width="5%"><?php echo e($propertyRental->propertyRentalID); ?></td>
                            <td width="10%"><?php echo e($propertyRental->property->propertyName); ?></td>
                            <td width="20%"><?php echo e($propertyRental->property->propertyAddress); ?></td>

                            <td><?php echo e($propertyRental->tenant->tenantName); ?></td>
                            <td><?php echo e($propertyRental->tenant->tenantEmail); ?></td>
                            <td><?php echo e($propertyRental->tenant->tenantPhone); ?></td>
                            <td width="20%">
                                <?php if($propertyRental->rentStatus == "Applied"): ?>
                                <a href="<?php echo e(route('properties.approve', $propertyRental->propertyRentalID)); ?>"
                                    class="btn btn-success">Approve</a>
                                <a href="<?php echo e(route('properties.reject', $propertyRental->propertyRentalID)); ?>"
                                    class="btn btn-danger">Reject</a>
                                <?php else: ?>
                                <span class="text-muted"> <?php echo e($propertyRental->rentStatus); ?> </span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
                <?php echo e($propertyRentals->links()); ?>

                <?php else: ?>
                <p>No record found..</p>
                <?php endif; ?>
                <?php endif; ?>
            </div>
            <!-- Bootstrap CSS -->
            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

            <!-- jQuery -->
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <!-- Bootstrap JS -->
            <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

            <!-- Bootstrap Modal -->
            <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog"
                aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteConfirmationModalLabel">Are you sure to delete the
                                following
                                property?</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>Property Name:</strong> <span id="propertyName"></span></p>
                            <p><strong>Property Type:</strong> <span id="propertyType"></span></p>
                            <p><strong>Property Address:</strong> <span id="propertyAddress"></span></p>
                            <img id="propertyImage" src="" alt="Property Image"
                                style="max-width: 100%; max-height: 200px;">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" onclick="proceedWithDeletion()">Delete</button>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                function confirmDelete(deleteUrl, propertyName, propertyType, propertyAddress, propertyImage) {
                    // Set modal content
                    document.getElementById('propertyName').textContent = propertyName;
                    document.getElementById('propertyType').textContent = propertyType;
                    document.getElementById('propertyAddress').textContent = propertyAddress;
                    document.getElementById('propertyImage').src = propertyImage;

                    // Show the modal
                    $('#deleteConfirmationModal').modal('show');

                    // Set the deletion URL
                    $('#deleteConfirmationModal').data('deleteUrl', deleteUrl);
                }

                function proceedWithDeletion() {
                    // Get the deletion URL from the modal
                    var deleteUrl = $('#deleteConfirmationModal').data('deleteUrl');

                    // Redirect to the deletion URL
                    window.location.href = deleteUrl;
                }
            </script>
        </div>
        <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/propertyIndex.blade.php ENDPATH**/ ?>