<link rel="stylesheet" href="<?php echo e(asset('/storage/css/ViewMyAccount.css')); ?>" media="screen">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<?php $__env->startSection('content'); ?>



<script>

$(document).ready(function() {

 $(".dynamic-content").hide();

 var sessionContent = "<?php echo e(session('dynamicContent')); ?>";


 if (sessionContent) {
     $("#" + sessionContent).show();
     <?php echo e(session()->forget('dynamicContent')); ?>


 } else {

     $("#profile").show();
 }


 $(".menu-left li").click(function() {
     
     $(".dynamic-content").hide();

    
     var contentToShow = $(this).data("content");

  
     $("#" + contentToShow).show();

     
 });


 document.getElementById("image-upload").addEventListener("change", function () {
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

document.getElementById("image-upload").addEventListener("change", function () {
 document.getElementById("save-image").style.visibility = "visible";
});

});
</script>



<div class="account-card">
    <div class="menu-left">
        <?php if(session('upload-error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('upload-error')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php elseif(session('upload-success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('upload-success')); ?>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <?php endif; ?>

        <div class="image-container">
            <form action="<?php echo e(route('UploadPhoto')); ?>" method="post" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div id="upload-img" style="text-align: center;">
                    <?php if(!empty(session('tenant')->photo)): ?>
                    <img src="<?php echo e(asset('storage/'. session('tenant')->photo)); ?>" alt="Tenant Photo">
                    <?php else: ?>
                    <img src="<?php echo e(asset('storage/users-avatar/landlord.png')); ?>" alt="Default Image">
                    <?php endif; ?>

                    <div class="upload-icon">
                        <label for="image-upload" style="cursor: pointer;">
                            <img src="<?php echo e(asset('storage/images/up-loading.png')); ?>" alt="Upload Image">
                        </label>
                    </div>
                </div>
                <input type="file" id="image-upload" name="profile_image" style="display: none;" accept="image/*">
                <input type="hidden" name="tenantID" value="<?php echo e(session('tenant')->tenantID); ?>">
                <button type="submit" id="save-image" style="margin-top: 10px; visibility: hidden;"
                    class="btn btn-primary"
                    onclick="return confirm('Are you sure to upload this image for your profile?')">Save Change</button>
            </form>
        </div>


        <div class="profile-details">
            <div class="detail text-center">
                <span style="font-size:24px; color:white;"><?php echo e(session('tenant')->tenantName); ?></span>
            </div>
            <div class="detail text-center">
                <i class="fas fa-envelope"></i>
                <span><?php echo e(session('tenant')->tenantEmail); ?></span>
            </div>
        </div>

        <ul class="profile-menu">
            <li data-content="profile">Edit My Profile</li>
            <li data-content="reset-password">Set New Password</li>
            <li data-content="reviews">My Reviews</li>
        </ul>
    </div>

    <div class="content-right">

        <div id="profile" class="dynamic-content">

            <?php if(session('update-success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo e(session('update-success')); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php elseif(session('update-error')): ?>
            <div class="alert alert-error alert-dismissible fade show">
                <?php echo e(session('update-error')); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>

            <div class="edit-profile-title text-center">
                <h1>Profile Settings</h1>
                <p>You can edit your profile here.</p>
            </div>

            <form action="<?php echo e(route('UpdateProfile')); ?>" method="post">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label for="tenantID">User ID:</label>
                    <input type="text" id="tenantID" name="tenantID" value="<?php echo e(session('tenant')->tenantID); ?>"
                        class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="tenantName">User Name:</label>
                    <input type="text" class="form-control <?php $__errorArgs = ['tenantName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="tenantName"
                        name="tenantName" placeholder="Enter Your User Name" value="<?php echo e(session('tenant')->tenantName); ?>"
                        required>
                    <?php $__errorArgs = ['tenantName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($errors->first('tenantName')); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="tenantEmail">Email Address:</label>
                    <input type="email" id="tenantEmail" name="tenantEmail" value="<?php echo e(session('tenant')->tenantEmail); ?>"
                        class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="tenantPhone">Contact Number:</label>
                    <input type="tel" id="tenantPhone" name="tenantPhone" value="<?php echo e(session('tenant')->tenantPhone); ?>"
                        placeholder="Enter Your Contact Number (Eg: 012-8697043)"
                        class="form-control <?php $__errorArgs = ['tenantPhone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['tenantPhone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($errors->first('tenantPhone')); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="dateofbirth">Date of Birth:</label>
                    <input type="date" id="tenantDOB" name="tenantDOB"
                        class="form-control <?php $__errorArgs = ['tenantDOB'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        value="<?php echo e(date('Y-m-d', strtotime(session('tenant')->tenantDOB))); ?>">
                    <?php $__errorArgs = ['tenantDOB'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($errors->first('tenantDOB')); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" class="form-control <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <option value="M" <?php echo e((session('tenant')->gender == 'M') ? 'selected' : ''); ?>>Male</option>
                        <option value="F" <?php echo e((session('tenant')->gender == 'F') ? 'selected' : ''); ?>>Female</option>
                    </select>
                    <?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($errors->first('gender')); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary"
                        onclick="return confirm('Are you sure to update your profile?')">Save Change</button>
                </div>
            </form>

        </div>


        <div id="reset-password" class="dynamic-content">

            <?php if(session('reset-error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo e(session('reset-error')); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php elseif(session('reset-success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo e(session('reset-success')); ?>

                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php endif; ?>

            <div class="edit-profile-title text-center">
                <h1>Reset New Password</h1>
                <p>You can set your new password here.</p>
            </div>

            <div class="password-rules">
                <p class="text-muted">Password Rules:</p>
                <ol>
                    <li>Minimum 6 characters</li>
                    <li>Maximum 15 characters</li>
                </ol>
            </div>

            <div class="password-safety">
                <p class="text-muted">Security Information:</p>
                <ul>
                    <li>To change the password whenever necessary.</li>
                    <li>You are responsible for keeping the password safe.</li>
                    <li>Do not share your password with anyone.</li>
                </ul>
            </div>

            <form action="<?php echo e(route('UpdatePassword')); ?>" method="post">
                <?php echo csrf_field(); ?>

                <input type="hidden" id="tenantID" name="tenantID" value="<?php echo e(session('tenant')->tenantID); ?>"
                    class="form-control">
                <div class="form-group">

                    <label for="currentPassword">Current Password:</label>
                    <input type="password" id="currentPassword" name="currentPassword"
                        class="form-control <?php $__errorArgs = ['currentPassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        placeholder="Enter Your Current Password" required>
                    <?php $__errorArgs = ['currentPassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($errors->first('currentPassword')); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="password">New Password:</label>
                    <input type="password" id="password" name="password"
                        class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" autocomplete="new-password"
                        placeholder="Enter Your New Password" required>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($errors->first('password')); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="form-group">
                    <label for="confirmPassword">Confirm Password:</label>
                    <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                        id="password-confirm" name="password_confirmation" placeholder="Confirm Your Confirm Password"
                        autocomplete="new-password" required>
                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="text-danger"><?php echo e($errors->first('password')); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary"
                        onclick="return confirm('Are you sure to reset your password?')">Reset Password</button>
                </div>
            </form>

        </div>


        <div id="reviews" class="dynamic-content">
            <div class="edit-profile-title text-center">
                <h1>My Reviews</h1>
                <p>You can view all reviews that review by you here.</p>
            </div>


            <?php $__currentLoopData = $userReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="review-account-container">

                <div class="item-name specific-item-name">Review for <?php echo e($review->itemName); ?></div>
                <div class="review-account-date">Reviewed
                    <?php echo e(\Carbon\Carbon::parse($review->reviewDate)->diffForHumans()); ?></div>
                <div class="account-comment"><?php echo e($review->comment); ?></div>
                <div class="account-rating">
                    Rating:
                    <?php for($i = 1; $i <= 5; $i++): ?> <?php if($i <=$review->rating): ?>
                        <i class="fas fa-star" style="color: #00ada0;"></i>
                        <?php else: ?>
                        <i class="fas fa-star" style="color: #ddd;"></i>
                        <?php endif; ?>
                        <?php endfor; ?>
                </div>

                <div class="details-link">
            <?php if(Str::startsWith($review->reviewItemID, 'PRO')): ?>
                <a href="<?php echo e(route('properties.show', $review->reviewItemID)); ?>">See what others have commented about <?php echo e($review->itemName); ?></a>
            <?php elseif(Str::startsWith($review->reviewItemID, 'AGT')): ?>
                <a href="<?php echo e(route('AgentDetails', ['id' => $review->reviewItemID])); ?>">See what others have commented about <?php echo e($review->itemName); ?></a>
            <?php endif; ?>
        </div>


            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



            </ul>

        </div>

    </div>
</div>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/tenant/ViewMyTenantAccount.blade.php ENDPATH**/ ?>