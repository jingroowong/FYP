<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="<?php echo e(asset('css/app.css')); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body>
    
    <style>

    #upload-img {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 20px;

    }

    .rounded-circle-container {
        position: relative;

    }

    .rounded-circle {
        max-width: 150px;
        max-height: 150px;
        width: 100%;
        height: 100%;
        border: solid 1px black;
    }

    .upload-icon img {
        height: 80px;
        width: 80px;
    }

    .upload-icon {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        opacity: 0;
        /* Initially hidden */
        transition: opacity 0.3s ease-in-out;
    }

    #upload-img:hover .upload-icon {
        opacity: 1;
        /* Show on hover */
    }

    #save-image {
        margin-top: 10px;
        visibility: hidden;
    }
    </style>
    <script>
    $(document).ready(function() {


        document.getElementById("image-upload").addEventListener("change", function() {
            var imageElement = document.getElementById("upload-img").querySelector("img");
            var selectedImage = this.files[0];

            if (selectedImage) {
                var imageUrl = URL.createObjectURL(selectedImage);
                imageElement.src = imageUrl;
            }
        });

        function triggerImageUpload() {
            document.getElementById("image-upload").click();
        }

        document.getElementById("image-upload").addEventListener("change", function() {
            document.getElementById("save-image").style.visibility = "visible";
        });

    });
    </script>

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 container">
        <h2>My Profile</h2>
        <?php if($user->userRole=="agent"): ?>
        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php elseif(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-8" style="padding:0;">
            </div>

            <div class="col-md-4" style="padding:0;">
                <div class="form-group text-center">
                    <a href="<?php echo e(route('ChangePassword')); ?>" class="btn btn-primary" style="padding: 10px 20px;">Change
                        Password</a>
                </div>
            </div>
        </div>


        <div class="container mt-4 border">
            <div class="row">
                <div class="col-md-4">
                    <!-- Left Side: User Image -->
                    <form action="<?php echo e(route('UploadAgentPhoto')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div id="upload-img">
                            <div class="rounded-circle-container">
                                <div class="rounded-circle overflow-hidden text-center">
                                    <?php if(!empty($user->photo)): ?>
                                    <img src="<?php echo e(asset('storage/'. $user->photo)); ?>" alt="Agent Photo"
                                        class="mx-auto d-block rounded-circle" style="width: 150px; height: 150px;">
                                    <?php else: ?>
                                    <img src="<?php echo e(asset('storage/users-avatar/agent.png')); ?>" alt="Default Image"
                                        class="mx-auto d-block rounded-circle" style="width: 150px; height: 150px;">
                                    <?php endif; ?>
                                    <div class="upload-icon">
                                        <label for="image-upload" style="cursor: pointer;">
                                            <img src="<?php echo e(asset('storage/images/up-loading.png')); ?>" alt="Upload Image">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <input type="file" id="image-upload" name="profile_image" style="display: none;"
                            accept="image/*">
                        <div class="form-group col-md-12 text-center">
                            <input type="hidden" name="id" value="<?php echo e($user->agentID); ?>">
                            <input type="hidden" name="userRole" value="<?php echo e($user->userRole); ?>">
                            <h4><?php echo e($user->agentID); ?></h4>
                            <button type="submit" id="save-image" class="btn btn-primary"
                                onclick="return confirm('Are you sure to upload this image for your profile?')">Save
                                Change</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-8">
                    <!-- Right Side: User Details Form -->
                    <form action="<?php echo e(route('UpdateAgentProfile')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($user->agentID); ?>">
                        <input type="hidden" name="userRole" value="<?php echo e($user->userRole); ?>">
                        <div class="card border-0">
                            <div class="card-body">
                                <h5 class="card-title">Profile Details</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name"
                                            class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e($user->agentName); ?>" required>
                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="email">Email Address:</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="<?php echo e($user->agentEmail); ?>" required readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="phone">Contact Number:</label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e($user->agentPhone); ?>">
                                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($errors->first('phone')); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="licenseNumber">License Number:</label>
                                        <input type="text" id="licenseNumber" name="licenseNum"
                                            class="form-control <?php $__errorArgs = ['licenseNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            placeholder="Optional Eg(REN/REAXXXXX)" value="<?php echo e($user->licenseNum); ?>">
                                        <?php $__errorArgs = ['licenseNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($errors->first('licenseNum')); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php if(count($reviews) > 0 ): ?>
        <h2 style="margin-top:30px;">My Reviews</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>Comment</th>
                    <th>Rating</th>
                    <th>Reviewer Name</th>
                    <th>Reviewed Date</th>
                </tr>
            </thead>

            <tbody>
                <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($review->reviewID); ?></td>
                    <td><?php echo e($review->comment); ?></td>
                    <td><?php echo e($review->rating); ?></td>
                    <td>
                        <?php if($review->agent): ?>
                        <?php echo e($review->agent->agentName); ?>

                        <?php elseif($review->tenant): ?>
                        <?php echo e($review->tenant->tenantName); ?>

                        <?php endif; ?>
                    </td>
                    <td><?php echo e(\Carbon\Carbon::parse($review->reviewDate)->diffForHumans()); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </tbody>
        </table>
        <div class="row">
            <div class="col-md-12 d-flex justify-content-center result-page">
                <?php echo e($reviews->onEachSide(1)->links()); ?>

            </div>
        </div>
        <?php endif; ?>
        <?php else: ?>
        <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php elseif(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-8" style="padding:0;">
            </div>

            <div class="col-md-4" style="padding:0;">
                <div class="form-group text-center">
                    <a href="<?php echo e(route('ChangePassword')); ?>" class="btn btn-primary" style="padding: 10px 20px;">Change
                        Password</a>
                </div>
            </div>
        </div>

        <div class="container mt-4 border">
            <div class="row">
                <div class="col-md-4">
                    <!-- Left Side: User Image -->
                    <form action="<?php echo e(route('UploadAgentPhoto')); ?>" method="post" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div id="upload-img">
                            <div class="rounded-circle-container">
                                <div class="rounded-circle overflow-hidden text-center">
                                    <?php if(!empty($user->photo)): ?>
                                    <img src="<?php echo e(asset('storage/'. $user->photo)); ?>" alt="Agent Photo"
                                        class="mx-auto d-block rounded-circle" style="width: 150px; height: 150px;">
                                    <?php else: ?>
                                    <img src="<?php echo e(asset('storage/users-avatar/admin.png')); ?>" alt="Default Image"
                                        class="mx-auto d-block rounded-circle" style="width: 150px; height: 150px;">
                                    <?php endif; ?>
                                    <div class="upload-icon">
                                        <label for="image-upload" style="cursor: pointer;">
                                            <img src="<?php echo e(asset('storage/images/up-loading.png')); ?>" alt="Upload Image">
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <input type="file" id="image-upload" name="profile_image" style="display: none;"
                            accept="image/*">
                        <div class="form-group col-md-12 text-center">
                            <input type="hidden" name="id" value="<?php echo e($user->adminID); ?>">
                            <input type="hidden" name="userRole" value="<?php echo e($user->userRole); ?>">
                            <h4><?php echo e($user->adminID); ?></h4>
                            <button type="submit" id="save-image" class="btn btn-primary"
                                onclick="return confirm('Are you sure to upload this image for your profile?')">Save
                                Change</button>
                        </div>
                    </form>
                </div>
                <div class="col-md-8">
                    <!-- Right Side: User Details Form -->
                    <form action="<?php echo e(route('UpdateAgentProfile')); ?>" method="post">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="id" value="<?php echo e($user->adminID); ?>">
                        <input type="hidden" name="userRole" value="<?php echo e($user->userRole); ?>">
                        <div class="card border-0">
                            <div class="card-body">
                                <h5 class="card-title">Profile Details</h5>
                                <div class="form-row">
                                    <div class="form-group col-md-12">
                                        <label for="name">Name:</label>
                                        <input type="text" id="name" name="name"
                                            class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e($user->adminName); ?>" required>
                                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($errors->first('name')); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="email">Email Address:</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="<?php echo e($user->adminEmail); ?>" required readonly>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <label for="phone">Contact Number:</label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            value="<?php echo e($user->adminPhone); ?>">
                                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($errors->first('phone')); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <div class="form-group col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Update Profile</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/agentProfile.blade.php ENDPATH**/ ?>