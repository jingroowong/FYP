<html>

<head>
    <meta charset="UTF-8">
    <title>Update Property</title>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    <style>
    .hidden {
        display: none;
    }

    /* CSS to set a fixed size for the images */
    #uploadedImages img,
    #retrievedImages img,
    #photoPreview img {
        width: 100px;
        /* Adjust the width as needed */
        height: auto;
        /* Maintain aspect ratio */
        margin: 5px;
        /* Add spacing between images */
    }

    #uploadedImages button {
        background-color: red;
        color: white;
        border: none;
        padding: 5px;
        cursor: pointer;
    }

    .retrievedImages {}
    </style>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="container">
        <h2>Update Property</h2>
        <div class="progress">
            <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="33" aria-valuemin="0"
                aria-valuemax="100">Stage 1</div>
        </div>
        <form method="POST" action="<?php echo e(route('properties.update', $property->propertyID)); ?>"
            enctype="multipart/form-data">
            <?php echo csrf_field(); ?>
            <!-- Stage 1: Fill in Property Form -->
            <div class="stage" id="stage-1">
                <h3>Stage 1 : Fill in Property Details</h3>

                <div class="row">
                    <!-- Property Name -->
                    <div class="form-group col-md-6">
                        <label for="propertyName">Property Name</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['propertyName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="propertyName" name="propertyName" value="<?php echo e($property->propertyName); ?>">
                        <?php $__errorArgs = ['propertyName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Property Description -->
                    <div class="form-group col-md-12">
                        <label for="propertyDesc">Property Description</label>
                        <textarea class="form-control <?php $__errorArgs = ['propertyDesc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="propertyDesc"
                            name="propertyDesc" rows="4"><?php echo e($property->propertyDesc); ?></textarea>
                        <?php $__errorArgs = ['propertyDesc'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>



                    <!-- Property Address -->
                    <div class="form-group col-md-6">
                        <label for="propertyAddress">Property Address</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['propertyAddress'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="propertyAddress" name="propertyAddress" value="<?php echo e($property->propertyAddress); ?>">
                        <?php $__errorArgs = ['propertyAddress'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- State (Dropdown) -->
                    <div class="form-group col-md-6">
                        <label for="stateID">State</label>
                        <select class="form-control <?php $__errorArgs = ['stateID'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="stateID" name="stateID">
                            <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($state->stateID); ?>" <?php echo e($state->stateID == $property->stateID ? 'selected'
                                : ''); ?>><?php echo e($state->stateName); ?>

                            </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['stateID'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="propertyType">Property Type</label>
                        <select class="form-control <?php $__errorArgs = ['propertyType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="propertyType"
                            name="propertyType">
                            <option value="Residential apartment" <?php echo e($property->propertyType =='Residential apartment'
                                    ? 'selected' : ''); ?>>Residential apartment</option>
                            <option value="House" <?php echo e($property->propertyType =='House' ? 'selected' : ''); ?>>House
                            </option>
                            <option value="Condominium" <?php echo e($property->propertyType =='Condominium' ? 'selected' : ''); ?>>
                                Condominium</option>
                            <option value="Commercial spaces" <?php echo e($property->propertyType =='Commercial spaces'
                                    ? 'selected' : ''); ?>>Commercial spaces</option>
                        </select>
                        <?php $__errorArgs = ['propertyType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <!-- Housing Type (Dropdown) based on Property Type -->
                    <div class="form-group col-md-6">
                        <label for="housingType">Housing Type</label>
                        <?php
                        $housingTypes = [];
                        switch ($property->propertyType) {
                        case 'Residential apartment':
                        $housingTypes = ['Loft Apartment', 'Studio Apartment', 'Luxury Apartment', 'Garden Apartment',
                        'Duplex Apartment'];
                        break;
                        case 'Condominium':
                        $housingTypes = ['High-Rise Condominium', 'Low-Rise Condominium', 'Luxury Condominium',
                        'Executive Condominium'];
                        break;
                        case 'Commercial spaces':
                        $housingTypes = ['Terraced House', 'Detached House', 'Semi-Detached House', 'Bungalow House',
                        'Shop House'];
                        break;
                        case 'House':
                        $housingTypes = ['Retail Space', 'Office Building', 'Shopping Complex', 'Industrial Warehouse',
                        'Restaurant Space'];
                        break;
                        }
                        ?>
                        <select class="form-control <?php $__errorArgs = ['housingType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="housingType"
                            name="housingType">
                            <?php $__currentLoopData = $housingTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>" <?php echo e($property->housingType == $type ? 'selected' : ''); ?>>
                                <?php echo e($type); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['housingType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <!-- Room Type (Dropdown) -->
                    <div class="form-group col-md-6">
                        <label for="roomType">Room Type</label>
                        <select class="form-control <?php $__errorArgs = ['roomType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="roomType"
                            name="roomType">
                            <?php $__currentLoopData = ['Unit', 'Small Room', 'Medium Room', 'Big Medium Room', 'Master Room']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($type); ?>" <?php echo e($property->roomType == $type ? 'selected' : ''); ?>>
                                <?php echo e($type); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['roomType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <?php if($property->roomType == 'Unit'): ?>
                    <!-- Prompt user for any number of bedrooms for Unit -->
                    <div class="form-group col-md-6">
                        <label for="bedroomNum">Number of Bedrooms</label>
                        <input type="number" class="form-control <?php $__errorArgs = ['bedroomNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="bedroomNum" name="bedroomNum" value="<?php echo e($property->bedroomNum); ?>">
                        <?php $__errorArgs = ['bedroomNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <?php else: ?>
                    <!-- Set number of bedrooms to 1 for other RoomTypes -->
                    <input type="hidden" name="bedroomNum" value="1">
                    <?php endif; ?>

                    <!-- Number of Bathrooms -->
                    <div class="form-group col-md-6">
                        <label for="bathroomNum">Number of Bathrooms</label>
                        <input type="number" class="form-control <?php $__errorArgs = ['bathroomNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="bathroomNum" name="bathroomNum" value="<?php echo e($property->bathroomNum); ?>">
                        <?php $__errorArgs = ['bathroomNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>



                    <!-- Property Size (Square Feet) -->
                    <div class="form-group col-md-6">
                        <label for="squareFeet">Property Size (Square Feet)</label>
                        <input type="number" class="form-control <?php $__errorArgs = ['squareFeet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="squareFeet" name="squareFeet" value="<?php echo e($property->squareFeet); ?>">
                        <?php $__errorArgs = ['squareFeet'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Furnishing Type (Radio Buttons) -->
                    <div class="form-group col-md-6">
                        <label>Furnishing Type</label><br>
                        <?php $__currentLoopData = ['Fully Furnished', 'Partial Furnished', 'Unfurnished']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="furnishingType"
                                id="<?php echo e(strtolower($type)); ?>" value="<?php echo e($type); ?>" <?php echo e($property->furnishingType == $type ?
                            'checked' : ''); ?>>
                            <label class="form-check-label" for="<?php echo e(strtolower($type)); ?>"><?php echo e($type); ?></label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php $__errorArgs = ['furnishingType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Build Year -->
                    <div class="form-group col-md-6">
                        <label for="buildYear">Build Year</label>
                        <input type="number" class="form-control <?php $__errorArgs = ['buildYear'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="buildYear" name="buildYear" value="<?php echo e($property->buildYear); ?>">
                        <?php $__errorArgs = ['buildYear'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Rental Amount -->
                    <div class="form-group col-md-6">
                        <label for="rentalAmount">Rental Amount (MYR)</label>
                        <input type="number" step="0.01"
                            class="form-control <?php $__errorArgs = ['rentalAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="rentalAmount"
                            name="rentalAmount" value="<?php echo e($property->rentalAmount); ?>">
                        <?php $__errorArgs = ['rentalAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Deposit Amount -->
                    <div class="form-group col-md-6">
                        <label for="depositAmount">Deposit Amount (MYR)</label>
                        <input type="number" step="0.01"
                            class="form-control <?php $__errorArgs = ['depositAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="depositAmount"
                            name="depositAmount" value="<?php echo e($property->depositAmount); ?>">
                        <?php $__errorArgs = ['depositAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Property Availability (Radio Buttons) -->
                    <div class="form-group col-md-6">
                        <label>Property Availability</label><br>
                        <?php $__currentLoopData = [1 => 'Available', 0 => 'Not Available']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="propertyAvailability"
                                id="<?php echo e(strtolower($label)); ?>" value="<?php echo e($value); ?>" <?php echo e($property->propertyAvailability ==
                            $value ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="<?php echo e(strtolower($label)); ?>"><?php echo e($label); ?></label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php $__errorArgs = ['propertyAvailability'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group col-md-6">
                    </div>
                    </br>
                    </br>

                    <!-- Facilities (Checklist) -->
                    <div class="form-group col-md-6">
                        <label>Facilities (Check all that apply)</label><br>
                        <?php $__currentLoopData = $facilities->slice(0, 40); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="facilities[]"
                                value="<?php echo e($facility->facilityID); ?>" <?php echo e(in_array($facility->facilityID,
                            $property->propertyFacilities->pluck('facilityID')->toArray()) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="<?php echo e($facility->facilityID); ?>">
                                <i class="las <?php echo e($facility->facilityIcon); ?>"></i> <?php echo e($facility->facilityName); ?>

                            </label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- Unit Features (Checklist) -->
                    <div class="form-group col-md-6">
                        <label>Unit Features (Check all that apply)</label><br>
                        <?php $__currentLoopData = $facilities->slice(40); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $facility): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="facilities[]"
                                value="<?php echo e($facility->facilityID); ?>" <?php echo e(in_array($facility->facilityID,
                            $property->propertyFacilities->pluck('facilityID')->toArray()) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="<?php echo e($facility->facilityID); ?>">
                                <i class="las <?php echo e($facility->facilityIcon); ?>"></i> <?php echo e($facility->facilityName); ?>

                            </label>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <button type="button" class="btn btn-primary" id="next-stage-2">Next</button>
            </div>

            <!-- Stage 2: Upload Photos -->
            <div class="stage hidden" id="stage-2">
                <h3>Stage 2: Upload Photos</h3>

                <!-- Property Photos (File Upload) -->
                <div class="form-group col-md-6">
                    <label for="propertyPhotos">Property Photos</label>
                    <input type="file" class="form-control" id="propertyPhotos" name="propertyPhotos[]" accept="image/*"
                        multiple>
                </div>

                <!-- Display retrieved images -->
                <div id="retrievedImages" class="retrievedImages">

                    <?php $__currentLoopData = $property->propertyPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div>
                        <img src="<?php echo e(Storage::url($photo->propertyPath)); ?>" class="img-thumbnail"
                            alt="Property Photo <?php echo e($index + 1); ?>">
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Display uploaded images -->
                <div id="uploadedImages"></div>

                <!-- Next button to move to Stage 3 -->
                <button type="button" class="btn btn-primary" id="next-stage-3">Next</button>
                <button type="button" class="btn btn-secondary" id="previous-stage-1">Previous</button>

                <script>
                // Function to display uploaded images
                function displayImages(event) {
                    const fileInput = event.target;
                    const uploadedImagesDiv = document.getElementById('uploadedImages');
                    const previewContainer = document.getElementById('photoPreview');


                    // Clear the previous images
                    uploadedImagesDiv.innerHTML = '';

                    // Display each selected image
                    for (const file of fileInput.files) {
                        const img = document.createElement('img');
                        img.src = URL.createObjectURL(file);
                        img.alt = file.name;
                        uploadedImagesDiv.appendChild(img);

                        // Add a button to remove the image
                        const removeButton = document.createElement('button');
                        removeButton.textContent = 'X';

                        removeButton.addEventListener('click', function() {
                            img.remove();
                            removeButton.remove();
                            fileInput.value = '';
                            previewContainer.innerHTML =
                            ''; // Clear the input to allow re-uploading the same file
                        });
                        uploadedImagesDiv.appendChild(removeButton);
                    }
                }

                // Attach the displayImages function to the change event of the file input
                document.getElementById('propertyPhotos').addEventListener('change', displayImages);
                </script>
            </div>

            <!-- Stage 3: Preview -->
            <div class="stage hidden" id="stage-3">
                <h3>Stage 3: Preview</h3>
                <!-- Property Preview Container -->
                <div id="propertyPreview" class="card">
                    <div class="card-header">
                        <h4 class="card-title">Property Preview</h4>
                    </div>
                    <div class="card-body">
                        <!-- Property Name Preview -->
                        <p><strong>Property Name:</strong> <span id="previewPropertyName"></span></p>

                        <!-- Property Description Preview -->
                        <p><strong>Property Description:</strong> <span id="previewPropertyDesc"></span></p>

                        <!-- Property Address Preview -->
                        <p><strong>Property Address:</strong> <span id="previewPropertyAddress"></span></p>

                        <!-- State Preview -->
                        <p><strong>State:</strong> <span id="previewState"></span></p>

                        <!-- Number of Bedrooms Preview -->
                        <p><strong>Number of Bedrooms:</strong> <span id="previewBedroomNum"></span></p>

                        <!-- Number of Bathrooms Preview -->
                        <p><strong>Number of Bathrooms:</strong> <span id="previewBathroomNum"></span></p>

                        <!-- Property Type Preview -->
                        <p><strong>Property Type:</strong> <span id="previewPropertyType"></span></p>

                        <!-- Housing Type Preview -->
                        <p><strong>Housing Type:</strong> <span id="previewHousingType"></span></p>

                        <!-- Room Type Preview -->
                        <p><strong>Room Type:</strong> <span id="previewRoomType"></span></p>

                        <!-- Property Size Preview -->
                        <p><strong>Property Size (Square Feet):</strong> <span id="previewSquareFeet"></span></p>

                        <!-- Furnishing Type Preview -->
                        <p><strong>Furnishing Type:</strong> <span id="previewFurnishingType"></span></p>

                        <!-- Build Year Preview -->
                        <p><strong>Build Year:</strong> <span id="previewBuildYear"></span></p>

                        <!-- Rental Amount Preview -->
                        <p><strong>Rental Amount (MYR):</strong> <span id="previewRentalAmount"></span></p>

                        <!-- Deposit Amount Preview -->
                        <p><strong>Deposit Amount (MYR):</strong> <span id="previewDepositAmount"></span></p>

                        <!-- Property Availability Preview -->
                        <p><strong>Property Availability:</strong> <span id="previewPropertyAvailability"></span></p>

                        <!-- Facilities Preview -->
                        <p><strong>Selected Facilities:</strong> <span id="previewFacilities"></span></p>

                        <!-- Photo Preview -->
                        <p><strong>Property Photos:</strong></p>
                        <div id="photoPreview"></div>
                        <!-- Display retrieved images -->
                        <div id="retrievedImages" class="retrievedImages">

                            <?php $__currentLoopData = $property->propertyPhotos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <img src="<?php echo e(Storage::url($photo->propertyPath)); ?>" class="img-thumbnail"
                                    alt="Property Photo <?php echo e($index + 1); ?>">
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                    </div>
                </div>
                <p><span style="color:red;">Notes :</span> Please make sure all the property information is correct
                    before proceed to next stage.</p>

                <!-- Next button to move to Stage 4 or Previous button to go back to Stage 2 -->
                <input type="submit" class="btn btn-success" id="confirm" value="Confirm Update">
                <button type="button" class="btn btn-secondary" id="previous-stage-3">Previous</button>
            </div>

            <script>
            // Function to update the preview based on user input
            function updatePreview() {
                // Update Property Name Preview
                document.getElementById('previewPropertyName').innerText = document.getElementById('propertyName')
                    .value;

                // Update Property Description Preview
                document.getElementById('previewPropertyDesc').innerText = document.getElementById('propertyDesc')
                    .value;

                // Update Property Address Preview
                document.getElementById('previewPropertyAddress').innerText = document.getElementById('propertyAddress')
                    .value;

                // Update State Preview
                document.getElementById('previewState').innerText = document.getElementById('stateID').options[document
                    .getElementById('stateID').selectedIndex].text;

                // Update Number of Bedrooms Preview
                document.getElementById('previewBedroomNum').innerText = document.getElementById('bedroomNum').value;

                // Update Number of Bathrooms Preview
                document.getElementById('previewBathroomNum').innerText = document.getElementById('bathroomNum').value;

                // Update Property Type Preview
                document.getElementById('previewPropertyType').innerText = document.getElementById('propertyType')
                    .value;

                // Update Room Type Preview
                document.getElementById('previewRoomType').innerText = document.getElementById('roomType')
                    .value;

                // Update Housing Type Preview
                document.getElementById('previewHousingType').innerText = document.getElementById('housingType')
                    .value;


                // Update Property Size Preview
                document.getElementById('previewSquareFeet').innerText = document.getElementById('squareFeet').value;

                // Update Furnishing Type Preview
                document.getElementById('previewFurnishingType').innerText = document.querySelector(
                    'input[name="furnishingType"]:checked').value;

                // Update Build Year Preview
                document.getElementById('previewBuildYear').innerText = document.getElementById('buildYear').value;

                // Update Rental Amount Preview
                document.getElementById('previewRentalAmount').innerText = document.getElementById('rentalAmount')
                    .value;

                // Update Deposit Amount Preview
                document.getElementById('previewDepositAmount').innerText = document.getElementById('depositAmount')
                    .value;

                // Update Property Availability Preview
                document.getElementById('previewPropertyAvailability').innerText = document.querySelector(
                    'input[name="propertyAvailability"]:checked').value === '1' ? 'Available' : 'Not Available';
            }

            // Function to handle file input changes
            function handleFileInput() {
                const input = document.getElementById('propertyPhotos');
                const previewContainer = document.getElementById('photoPreview');

                // Clear previous previews
                previewContainer.innerHTML = '';

                // Display preview for each selected file
                for (const file of input.files) {
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'img-thumbnail';
                        previewContainer.appendChild(img);
                    };

                    reader.readAsDataURL(file);
                }
            }

            // Event listener for file input changes
            document.getElementById('propertyPhotos').addEventListener('change', handleFileInput);

            // Event listeners for input changes
            document.getElementById('propertyName').addEventListener('input', updatePreview);
            document.getElementById('propertyDesc').addEventListener('input', updatePreview);
            document.getElementById('propertyAddress').addEventListener('input', updatePreview);
            document.getElementById('stateID').addEventListener('change', updatePreview);
            document.getElementById('bedroomNum').addEventListener('input', updatePreview);
            document.getElementById('bathroomNum').addEventListener('input', updatePreview);
            document.getElementById('propertyType').addEventListener('change', updatePreview);
            document.getElementById('housingType').addEventListener('change', updatePreview);
            document.getElementById('roomType').addEventListener('change', updatePreview);
            document.getElementById('squareFeet').addEventListener('input', updatePreview);
            document.querySelectorAll('input[name="furnishingType"]').forEach(function(radio) {
                radio.addEventListener('change', updatePreview);
            });
            document.getElementById('buildYear').addEventListener('input', updatePreview);
            document.getElementById('rentalAmount').addEventListener('input', updatePreview);
            document.getElementById('depositAmount').addEventListener('input', updatePreview);
            document.querySelectorAll('input[name="propertyAvailability"]').forEach(function(radio) {
                radio.addEventListener('change', updatePreview);
            });

            // Function to update the number of bedrooms based on Room Type
            document.getElementById('roomType').addEventListener('change', function() {
                const bedroomNumInput = document.getElementById('bedroomNum');
                bedroomNumInput.value = this.value === 'Unit' ? '' : '1';
                bedroomNumInput.readOnly = this.value !== 'Unit';
            });

            // Function to handle property type changes
            function handlePropertyTypeChange() {
                const propertyType = document.getElementById('propertyType').value;
                const roomTypeSelect = document.getElementById('roomType');
                const housingTypeSelect = document.getElementById('housingType');

                // Reset options
                roomTypeSelect.innerHTML = '';
                housingTypeSelect.innerHTML = '';

                // Define options based on property type
                let roomTypeOptions = ['Unit', 'Small Room', 'Medium Room', 'Big Medium Room', 'Master Room'];
                let housingTypeOptions = [];

                switch (propertyType) {
                    case 'Residential apartment':
                        housingTypeOptions = ['Loft Apartment', 'Studio Apartment', 'Luxury Apartment',
                            'Garden Apartment', 'Duplex Apartment'
                        ];
                        break;
                    case 'Condominium':
                        housingTypeOptions = ['High-Rise Condominium', 'Low-Rise Condominium', 'Luxury Condominium',
                            'Executive Condominium'
                        ];
                        break;
                    case 'Commercial spaces':
                        housingTypeOptions = ['Terraced House', 'Detached House', 'Semi-Detached House',
                            'Bungalow House', 'Shop House'
                        ];
                        break;
                    case 'House':
                        housingTypeOptions = ['Retail Space', 'Office Building', 'Shopping Complex',
                            'Industrial Warehouse', 'Restaurant Space'
                        ];
                        break;
                }

                // Populate Room Type dropdown
                roomTypeOptions.forEach(option => {
                    const newOption = document.createElement('option');
                    newOption.value = option;
                    newOption.text = option;
                    roomTypeSelect.add(newOption);
                });

                // Populate Housing Type dropdown
                housingTypeOptions.forEach(option => {
                    const newOption = document.createElement('option');
                    newOption.value = option;
                    newOption.text = option;
                    housingTypeSelect.add(newOption);
                });


            }

            // Event listener for property type changes
            document.getElementById('propertyType').addEventListener('change', handlePropertyTypeChange);

            function updateFacilitiesPreview() {
                // Get all checked checkboxes
                var selectedFacilities = document.querySelectorAll('input[name="facilities[]"]:checked');
                // Extract the facility names and join them with a comma
                var selectedFacilityNames = Array.from(selectedFacilities).map(function(checkbox) {
                    return checkbox.nextElementSibling.innerText.trim();
                }).join(', ');

                // Update Facilities Preview
                document.getElementById('previewFacilities').innerText = selectedFacilityNames;
            }

            // Event listeners for checkbox changes
            var checkboxes = document.querySelectorAll('input[name="facilities[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.addEventListener('change', updateFacilitiesPreview);
            });
            </script>


            <!-- Stage 4: Completed -->
            <div class="stage hidden" id="stage-4">
                <!-- Confirm button to move to Stage 5 or Previous button to go back to Stage 3 -->

                <h3>Update Completed</h3>
                <!-- Completion message and option to start a new property posting -->
                <a href="<?php echo e(route('properties')); ?>" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left"></i> Back to Home
                </a>
            </div>

        </form>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

        <script>
        // JavaScript and jQuery code for handling stage transitions
        $(document).ready(function() {
            function updateProgressBar(stage) {
                // Define the total number of stages in your form
                const totalStages = 4;

                // Calculate the new width for the progress bar
                const newWidth = (stage / totalStages) * 100 + '%';

                // Update the progress bar width
                $('.progress-bar').css('width', newWidth);

                // Update the progress text (optional)
                $('.progress-bar').text('Stage ' + stage);
            }
            $('#next-stage-2').click(function() {
                // Move to Stage 2
                $('#stage-1').addClass('hidden');
                $('#stage-2').removeClass('hidden');
                updateProgressBar(2);
                updatePreview();
                updateFacilitiesPreview();
            });

            $('#next-stage-3').click(function() {
                // Move to Stage 3
                $('#stage-2').addClass('hidden');
                $('#stage-3').removeClass('hidden');
                updateProgressBar(3);
                updatePreview();
                updateFacilitiesPreview();
            });

            $('#previous-stage-1').click(function() {
                // Go back to Stage 1
                $('#stage-2').addClass('hidden');
                $('#stage-1').removeClass('hidden');
                updateProgressBar(1);
            });

            $('#previous-stage-2').click(function() {
                // Go back to Stage 2
                $('#stage-3').addClass('hidden');
                $('#stage-2').removeClass('hidden');
                updateProgressBar(2);
            });

            $('#previous-stage-3').click(function() {
                // Go back to Stage 3
                $('#stage-3').addClass('hidden');
                $('#stage-2').removeClass('hidden');
                updateProgressBar(3);
            });



        });

        document.getElementById('confirm').addEventListener('click', function() {
            document.querySelector('form').submit();
        });
        </script>
    </div>

    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/propertyUpdate.blade.php ENDPATH**/ ?>