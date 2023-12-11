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

    </style>
    <script>

    </script>

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2">

        <div class="row">
            <div class="col-md-1" style="padding:0;">
                <div class="form-group text-center">
                    <?php if(session('agent')): ?>
                    <a href="<?php echo e(route('MyAgentAccount', ['id' => session('agent')->agentID])); ?>" class="btn btn-primary"
                        style="padding: 10px 20px;">Back</a>
                    <?php else: ?>
                    <a href="<?php echo e(route('MyAgentAccount', ['id' => session('admin')->adminID])); ?>" class="btn btn-primary"
                        style="padding: 10px 20px;">Back</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-md-8" style="padding:0;">
            </div>


        </div>

        <div class="edit-profile-title text-center">
            <h3>Reset New Password</h3>
            <p>You can set your new password here.</p>
        </div>

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

        <form action="<?php echo e(route('UpdateNewPassword')); ?>" method="post">
            <?php echo csrf_field(); ?>
            <?php if(session('agent')): ?>
            <input type="hidden" name="id" value="<?php echo e(session('agent')->agentID); ?>">
            <input type="hidden" name="userRole" value="agent">
            <?php else: ?>
            <input type="hidden" name="id" value="<?php echo e(session('admin')->adminID); ?>">
            <input type="hidden" name="userRole" value="admin">
            <?php endif; ?>


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

    <?php $__env->stopSection(); ?>
</body>

</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/agent/changePassword.blade.php ENDPATH**/ ?>