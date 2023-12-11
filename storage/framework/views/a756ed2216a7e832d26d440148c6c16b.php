<link rel="stylesheet" href="<?php echo e(asset('/storage/css/UserRegistration.css')); ?>" media="screen">
<?php $__env->startSection('content'); ?>
<script>

</script>
<div class="container">
    <?php if(\Session::has('success')): ?>
    <script>
    function countdown() {
        var seconds = 5;
        var countdownElement = document.getElementById('countdown');

        var timer = setInterval(function() {
            seconds--;
            countdownElement.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = "<?php echo e(route('HomeLogin')); ?>";
            }
        }, 1000);
    }
    window.onload = countdown;
    </script>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="success-alert text-center">
                <p><?php echo e(\Session::get('success')); ?>. Redirecting to <a href="<?php echo e(route('HomeLogin')); ?>"
                        class="no-underline">Login Page</a> in <span id="countdown">5</span> seconds...</p>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="row">
        <div class="text-description col-md-6 offset-md-3 text-center">
            <img src="<?php echo e(asset('storage/images/agent.png')); ?>" alt="Your Image" class="userImg">
            <p>Please fill up the fields to sign up for a Agent Account.</p>
            <div class="signup-message col text-center">
                <p class="register-title">Already have an account? <a href="<?php echo e(route('HomeLogin')); ?>"
                        class="register-agent">Sign in here</a></p>
            </div>
        </div>

        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('agent.register')); ?>">
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
                                id="agentName" name="agentName" placeholder="Enter Your User Name"
                                value="<?php echo e(old('agentName')); ?>" required>
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
                                placeholder="Enter Your Email Address (Eg: jiahon@gmail.com)"
                                value="<?php echo e(old('agentEmail')); ?>" required>
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
                            <label for="password">Password:</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="password" name="password" placeholder="Enter Password" required
                                autocomplete="new-password">
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
                            <label for="password-confirm">Confirm Password:</label>
                            <input type="password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="password-confirm" name="password_confirmation" placeholder="Enter Confirm Password"
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
                                placeholder="Enter Your Phone Number (Eg: 01x-xxxxxxx)" value="<?php echo e(old('agentPhone')); ?>"
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
                                value="<?php echo e(old('licenseNum')); ?>">
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

                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <div class="signup-button">
                                    <button type="submit" class="btn btn-primary"
                                        onclick="return confirm('Are you sure to register?')">Sign up as Agent</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/RegisterAgent.blade.php ENDPATH**/ ?>