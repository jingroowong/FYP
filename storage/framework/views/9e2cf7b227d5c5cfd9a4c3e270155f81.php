<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rent Property</title>
    
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">

    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .icon {
        font-size: 18px;
    }

    .beware {
        font-size: 18px;
    }

    .property-details img,
    .propertyPhoto img {
        border-radius: 8px;
        width: 300px;
        height: 200px;

    }
    </style>
</head>

<body>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <h2 class="mt-4 mb-4">Submit Property Application</h2>

        <!-- Display Property Details -->


        <h4><?php echo e($property->propertyName); ?></h4>
        <p><strong>Address:</strong> <?php echo e($property->propertyAddress); ?></p>
        <div class="row">
            <div class="property-details col-md-6">
                <img src="<?php echo e(Storage::url($property->propertyPhotos[0]->propertyPath)); ?>" alt="Property Photo">
            </div>
            <div class="col-md-6">
                <div class="propertyPhoto">
                    <img src="<?php echo e(Storage::url($property->agent->photo)); ?>" alt="Agent Photo">
                </div>
            </div>
        </div>
        <form action="<?php echo e(route('properties.submitApplication', ['propertyID' => $property->propertyID])); ?>" method="get">
            <div class="row">
                <div class="col-md-6">
                    <div class="tenantDetails">
                        <h3>Please check your personal details : </h3>
                        <table>
                            <tr>
                                <td>
                                    <p><strong>Tenant's Name : </strong>
                                </td>

                                <td> <?php echo e($tenant->tenantName); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Tenant's Email : </strong>
                                </td>
                                <td> <?php echo e($tenant->tenantEmail); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p><strong>Tenant's Contact Number : </strong>
                                </td>
                                <td> <?php echo e($tenant->tenantPhone); ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
              
                <div class="col-md-6">
                    <!-- Step 3: Agent Preview -->
                    <h3>Agent details : </h3>
                    <table>
                        <tr>
                            <td>
                                <p><strong>Agent's Name : </strong>
                            </td>
                            <td> <?php echo e($property->agent->agentName); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>Agent's Email : </strong>
                            </td>
                            <td> <?php echo e($property->agent->agentEmail); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p><strong>Agent's Contact Number : </strong>
                            </td>
                            <td> <?php echo e($property->agent->agentPhone); ?></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <p>Are you sure to rent this space? </p>
            <div class="mt-3">
                <div class="mt-3 form-check">
                    <input class="form-check-input" type="checkbox" id="readRulesCheckbox"
                        onchange="toggleSubmitButton()">
                    <label class="form-check-label" for="readRulesCheckbox">
                        I agree to RentSpace's Terms of Service and Privacy Policy including the collection, use
                        and disclosure of my personal information.
                    </label>
                </div>

                <div class="btn-container">
                     <button type="submit" class="btn btn-success" id="submitButton" disabled>Submit Now</button>
                </div>
            </div>
    </div>
    </div>
    </form>
    </div>

    <?php $__env->stopSection(); ?>
    <script>
    function toggleSubmitButton() {
        var checkbox = document.getElementById('readRulesCheckbox');
        var submitButton = document.getElementById('submitButton');

        // Enable the Next button if the checkbox is checked, otherwise disable it
        submitButton.disabled = !checkbox.checked;
    }
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/tenant/propertyApply.blade.php ENDPATH**/ ?>