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
    </style>
</head>

<body>
    

    <?php $__env->startSection('content'); ?>
    <div class="ml-5 mt-2 agent">
    <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back
        </a>
<div class="row">
        <div class="text-description col-md-6 offset-md-3 text-center">
            <img src="<?php echo e(asset('storage/images/agent.png')); ?>" width="50%" height="80%" alt="Your Image" class="userImg">
            <p>Update Agent</p>
          
        </div>

        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('agents.update')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="username">User name:</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['agentName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="agentName" name="agentName" placeholder="Enter Agent Name"
                                value="<?php echo e($agent->agentName); ?>" required>
                            <?php $__errorArgs = ['agentName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger"><?php echo e($errors->first('agentName')); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address:</label>
                            <input type="email" class="form-control <?php $__errorArgs = ['agentEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="agentEmail" name="agentEmail"
                                placeholder="Enter Agent Email Address (Eg: jiahon@gmail.com)"
                                value="<?php echo e($agent->agentEmail); ?>" required>
                                <input type="hidden" name="agentID" value="<?php echo e($agent->agentID); ?>">
                            <?php $__errorArgs = ['agentEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger"><?php echo e($errors->first('agentEmail')); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="tel" class="form-control <?php $__errorArgs = ['agentPhone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="agentPhone" name="agentPhone"
                                placeholder="Enter Agent Phone Number (Eg: 01x-xxxxxxx)" value="<?php echo e($agent->agentPhone); ?>"
                                required>
                            <?php $__errorArgs = ['agentPhone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger"><?php echo e($errors->first('agentPhone')); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">

                            <label for="licenseNum">License Number</label><span class="text-danger"> *IF any* </span>:
                            <input type="text" class="form-control <?php $__errorArgs = ['licenseNum'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="licenseNum" name="licenseNum"
                                placeholder="Enter Your License Number (Eg: REAXXXXX or RENXXXXX) - Optional"
                                value="<?php echo e($agent->licenseNum); ?>">
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

                        <div class="form-group">
    <label for="agentStatus">Agent Status:</label>
    <select class="form-control" id="agentStatus" name="agentStatus" required>
        <option value="active" <?php echo e($agent->status === 'active' ? 'selected' : ''); ?>>Active</option>
        <option value="inactive" <?php echo e($agent->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
    </select>
</div>

                        <div class="row">
                            <div class="signup-button">
                               
                                <button type="submit" class="btn btn-primary"
                                    onclick="return confirm('Are you sure to update?')">Update Agent</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
    <?php $__env->stopSection(); ?>
</body>
</html>
<?php echo $__env->make('layouts.adminApp', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/admin/agentUpdate.blade.php ENDPATH**/ ?>