
<link rel="stylesheet" href="<?php echo e(asset('/storage/css/Wishlist.css')); ?>" media="screen">
<link rel="stylesheet"
    href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

<?php $__env->startSection('content'); ?>

<?php if(!$searchHistory->isEmpty()): ?>

<div class="row justify-content-center">
    <h2 style="color:0d6efd;">My Search History</h2>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(\Session::get('success')); ?>.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
    </div>
</div>

    <div class="compare-top">
    <a class="btn btn-warning" style="color: white; padding: 10px 20px;" id="selectAllCheckbox" onclick="toggleSelectAll()">Select All</a>
    <button class="btn btn-danger trash-can-button" id="trashCanButton" style="visibility: hidden" onclick="deleteSelected()">🗑️ Remove Selected</button>
</div>



<form id="searchForm" action="<?php echo e(route('RemoveHistory')); ?>" method="POST">
    <?php echo csrf_field(); ?>

    


    <?php $__currentLoopData = $searchHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $search): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="container">
        <div class="row">
            <?php
            $state = app('App\Http\Controllers\AgentController')->getPropertyState($search->propertyID);
            ?>
            <!-- Wishlist with Checkbox -->
            <div class="col-md-2 d-flex align-items-center justify-content-center">
                <!-- Centered Checkbox -->
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="selectedItems[]"
                        value="<?php echo e(json_encode(['id' => $search->searchID, 'propertyID' => $search->propertyID, 'tenantID' => $search->tenantID])); ?>"
                        onchange="toggleTrashCan()">
                </div>
            </div>

            <!-- Wishlist details -->
            <div class="col-md-9 wishlist-card ">
            <div class="card mb-4" onclick="navigateToPropertyDetails('<?php echo e(route('properties.show', $search->propertyID)); ?>')">
                    <div class="card-body">
                        <!-- Part a: Image and Image count -->
                        <div class="row">
                            <div class="col-md-5">
                                <div class="property-image" onclick="navigateToPropertyDetails()">
                                    <img src="<?php echo e(asset('storage/'. $search->propertyPhotoPath)); ?>" class="img-fluid"
                                        alt="Property Image">
                                    <div class="image-overlay">
                                        <p class="photo-count"><i class="fas fa-image"></i>
                                            <?php echo e($search->photos_count); ?> Photos</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <h2 class="card-title mt-3"><?php echo e($search->propertyName); ?></h2>
                                <h4 class="card-text">For Rent: <span>RM<?php echo e($search->rentalAmount); ?></span></h4>
                                <p class="card-text"><?php echo e($search->propertyAddress); ?></p>
                                <p class="card-text"><?php echo e($search->propertyDesc); ?></p>
                            </div>
                        </div>
                        <!-- Part c: Additional details -->
                        <div class="row" style="padding-left:10px;">
                            <div class="col-md-6">
                                <p><i class="las la-city"></i> <?php echo e($search->propertyType); ?></p>
                                <p><i class="lab la-buffer"></i> <?php echo e($search->squareFeet); ?> SQ.FT</p>
                                <p><i class="las la-bed"></i> <?php echo e($search->bedroomNum); ?> bedroom(s)</p>
                            </div>
                            <div class="col-md-6">
                                <p><i class="las la-tools"></i> <?php echo e($search->furnishingType); ?></p>
                                <p><i class="las la-flag"></i> <?php echo e($state); ?></p>
                                <p><i class="las la-bath"></i> <?php echo e($search->bathroomNum); ?> bathroom(s)</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <div class="row">
        <div class="col-md-12 d-flex justify-content-center result-page">
            <?php echo e($searchHistory->links()); ?>

        </div>
    </div>


    <?php else: ?>
    <div class="wish-no-found">
        <h2>No Search History Found</h2>
        <p>Seeking for a specific house or room? Start searching your dream home now!</p>
        <a href="<?php echo e(route('propertyList')); ?>">Search Now</a>
    </div>
    <?php endif; ?>


</form>


<script>

function deleteSelected() {
    var confirmDelete = confirm('Are you sure you want to delete the selected items?');

    if (confirmDelete) {
        var form = document.getElementById('searchForm');
        form.submit();
    } else {
        // Optional: You can add a message or additional actions if the user cancels the deletion.
        console.log('Deletion canceled by the user.');
    }
}

function toggleSelectAll() {
    var checkboxes = document.querySelectorAll('input[name="selectedItems[]"]');
    var selectAllCheckbox = document.getElementById('selectAllCheckbox');
    var trashCanButton = document.getElementById('trashCanButton');

    checkboxes.forEach(function (checkbox) {
        checkbox.checked = !selectAllCheckbox.classList.contains('active');
    });

    if (selectAllCheckbox.classList.toggle('active')) {
        // If "active" class is present, change the text to "Cancel Selections"
        selectAllCheckbox.innerHTML = 'Cancel Selections';
    } else {
        // If "active" class is not present, change the text back to "Select All"
        selectAllCheckbox.innerHTML = 'Select All';
    }

    trashCanButton.style.visibility = selectAllCheckbox.classList.contains('active') ? 'visible' : 'hidden';
}

function toggleTrashCan() {
    var trashCanButton = document.getElementById('trashCanButton');
    var deleteButton = document.getElementById('deleteButton');
    var checkboxes = document.querySelectorAll('input[name="selectedItems[]"]:checked');
    trashCanButton.style.visibility = checkboxes.length > 0 ? 'visible' : 'hidden';
    deleteButton.style.display = checkboxes.length > 0 ? 'inline-block' : 'none';
}


function navigateToPropertyDetails(url) {
        window.location.href = url;
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/tenant/SearchHistory.blade.php ENDPATH**/ ?>