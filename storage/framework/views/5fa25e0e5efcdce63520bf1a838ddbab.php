<link rel="stylesheet" href="<?php echo e(asset('/storage/css/HomeLogin.css')); ?>" media="screen">


<?php $__env->startSection('content'); ?>

<script>
document.addEventListener("DOMContentLoaded", function() {

    const forgetButton = document.getElementById("forgetButton");


    forgetButton.addEventListener("click", function() {

        forgetButton.disabled = true;
        document.getElementById("forgetForm").submit();
    });

    <?php if(session('success') || session('error')): ?>

    $('#forgetPasswordModal').modal('show');

    document.getElementById('fgtCloseButton').addEventListener('click', function() {
        $('#forgetPasswordModal').modal('hide');
    });
    <?php elseif(session('fgtError')): ?>

    $('#forgetPasswordModal').modal('show');
    document.getElementById('fgtError').textContent =
        '*Email Address does not exist as registered email in RentSpace....';

    document.getElementById('fgtCloseButton').addEventListener('click', function() {
        $('#forgetPasswordModal').modal('hide');
        document.getElementById('fgtError').textContent = '';
    });
    <?php endif; ?>


    <?php if(session('tntError')): ?>
    $('#tenantSignInModal').modal('show');
    document.getElementById('tntError').textContent =
        '*Authentication failed, Incorrect Email Address or Password*';


    $('#tenantSignInModal').on('show.bs.modal', function(e) {
        $(this).data('bs.modal')._config.backdrop = 'static';
        $(this).data('bs.modal')._config.keyboard = false;
    });

    document.getElementById('customCloseButton').addEventListener('click', function() {
        $('#tenantSignInModal').modal('hide');
        document.getElementById('tntError').textContent = '';
    });
    <?php endif; ?>

    <?php if(session('agtError')): ?>
    $('#agentSignInModal').modal('show');
    document.getElementById('agtError').textContent =
        '*Authentication failed, Incorrect Email Address or Password*';

    document.getElementById('agentCloseButton').addEventListener('click', function() {
        $('#agentSignInModal').modal('hide');
        document.getElementById('agtError').textContent = '';
    });
    <?php endif; ?>

    <?php if(session('admError')): ?>
    $('#adminSignInModal').modal('show');
    document.getElementById('admError').textContent =
        '*Authentication failed, Incorrect Email Address or Password*';

    document.getElementById('adminCloseButton').addEventListener('click', function() {
        $('#adminSignInModal').modal('hide');
        document.getElementById('admError').textContent = '';
    });
    <?php endif; ?>
});
</script>


<div id="app">
    <div class="container">

        <?php if(\Session::has('reset_success')): ?>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="success-alert text-center" id="autoHideAlert">
                    <p><?php echo e(\Session::get('reset_success')); ?></p>
                </div>
            </div>
        </div>

        <script>
        setTimeout(function() {
            var autoHideAlert = document.getElementById('autoHideAlert');
            if (autoHideAlert) {
                autoHideAlert.style.display = 'none';
            }
        }, 5000);
        </script>
        <?php endif; ?>
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="welcome-message">
                    <span class="welcome-text">Welcome Back to</span>
                    <span class="rent-space">RentSpace!!!</span>
                </div>
                <p class="account-type-message">Sign in according your account type</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="card common-card d-flex flex-column align-items-center">
                    <img src="<?php echo e(asset('storage/images/landlord.png')); ?>" alt="Tenant Account" width="210" height="210">
                    <h4 class="card-title">Tenant Account</h4>
                    <p class="card-description">If you are an individual looking to rent or find rental property.</p>
                    <button class="sign-in-button btn btn-success" data-toggle="modal"
                        data-target="#tenantSignInModal">Sign In</button>

                    <div class="signup-message">
                        <p class="register-title">Don't have an account? <a href="<?php echo e(route('TenantRegister')); ?>"
                                class="register-tenant">Sign up here</a></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card common-card d-flex flex-column align-items-center">
                    <img src="<?php echo e(asset('storage/images/agent.png')); ?>" alt="Landlord Account" width="210" height="210">
                    <h4 class="card-title">Agent Account</h4>
                    <p class="card-description">If you are a landlord or a professional real estate agent that wants to
                        rent properties.</p>
                    <button class="sign-in-button btn btn-primary" data-toggle="modal"
                        data-target="#agentSignInModal">Sign In</button>
                    <div class="signup-message">
                        <p class="register-title">Don't have an account? <a href="<?php echo e(route('AgentRegister')); ?>"
                                class="register-agent">Sign up here</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card common-card d-flex flex-column align-items-center">
                    <img src="<?php echo e(asset('storage/images/admin.png')); ?>" alt="Admin Account" width="210" height="210">
                    <h4 class="card-title">Admin Account</h4>
                    <p class="card-description">Admin Account Type is available for internal staff login only...</p>
                    <button class="sign-in-button btn btn-danger" data-toggle="modal"
                        data-target="#adminSignInModal">Sign In</button>
                    <div class="signup-message">
                        <p class="register-admin">*Admin is not allowed to sign up an account* </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="centered-row">
            <div class="image-text-row">
                <img src="<?php echo e(asset('storage/images/warning.png')); ?>" alt="Your Image" class="image">
                <p class="text">
                    <span class="warning">Important Reminder:</span>
                    If you have forgot your password, you can click here to <a href="#" class="blue-link"
                        id="openForgetPasswordModal" data-toggle="modal" data-target="#forgetPasswordModal">reset your
                        password</a>. For any questions, please reach out to our customer support at
                    <span class="italic-text">014-616 6273</span> or
                    <span class="italic-text">rentspace@gmail.com</span>
                </p>

            </div>
        </div>

    </div>
</div>



<!-- Tenant Login -->
<div class="modal" id="tenantSignInModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog custom-dialog" role="document">
        <div class="modal-content"
            style="width: 800px; position: fixed; top: 30%; left: 50%; transform: translate(-50%, -50%);">
            <div class="modal-header">
                <h5 class="modal-title">Sign In as Tenant</h5>
                <button type="button" id="customCloseButton" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col text-center" style=" margin-bottom:12px;">
                        <strong style="font-size: 24px;  font-weight: bold;">Login to RentSpace</strong>
                    </div>
                </div>


                <div class="row">

                    <div class="col-md-4">
                        <img src="<?php echo e(asset('storage/images/landlord.png')); ?>" alt="Your Image"
                            class="img-fluid mx-auto d-block img-full-width">
                    </div>

                    <div class="col-md-8">
                        <form method="POST" action="<?php echo e(route('tenant.login')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input id="tenantEmail" type="email"
                                    class="form-control <?php $__errorArgs = ['tenantEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="tenantEmail"
                                    value="<?php echo e(old('tenantEmail')); ?>" placeholder="Enter your Email Address" required
                                    autocomplete="email" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input id="password" type="password"
                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password"
                                    placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                            <div class="form-group">
                                <span id="tntError" class="text-danger"></span>
                            </div>
                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="remember">
                                    <?php echo e(__('Remember Me')); ?>

                                </label>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success"
                                    id="signInButton"><?php echo e(__('Sign In as Tenant')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="signup-message col text-center">
                        <p class="register-title">Don't have an account? <a href="<?php echo e(route('TenantRegister')); ?>"
                                class="register-tenant">Sign up here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Agent Login -->
<div class="modal" id="agentSignInModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog custom-dialog" role="document">
        <div class="modal-content"
            style="  width:800px; position: fixed; top: 30%; left: 50%; transform: translate(-50%, -50%);">
            <div class="modal-header">
                <h5 class="modal-title">Sign In as Agent</h5>
                <button type="button" id="agentCloseButton" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col text-center" style=" margin-bottom:12px;">
                        <strong style="font-size: 24px;  font-weight: bold;">Login to RentSpace</strong>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <img src="<?php echo e(asset('storage/images/agent.png')); ?>" alt="Your Image"
                            class="img-fluid mx-auto d-block img-full-width">
                    </div>

                    <div class="col-md-8">
                        <form method="POST" action="<?php echo e(route('agent.login')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input id="agentEmail" type="email"
                                    class="form-control <?php $__errorArgs = ['agentEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="agentEmail"
                                    value="<?php echo e(old('agentEmail')); ?>" placeholder="Enter your Email Address" required
                                    autocomplete="email" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input id="password" type="password"
                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password"
                                    placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                            <div class="form-group">
                                <span id="agtError" class="text-danger"></span>
                            </div>
                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="remember">
                                    <?php echo e(__('Remember Me')); ?>

                                </label>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary"
                                    id="signInButton"><?php echo e(__('Sign In as Agent')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="signup-message col text-center">

                        <p class="register-title">Don't have an account? <a href="<?php echo e(route('AgentRegister')); ?>"
                                class="register-agent">Sign up here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Admin Login -->
<div class="modal" id="adminSignInModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog custom-dialog" role="document">
        <div class="modal-content"
            style="  width:800px; position: fixed; top: 30%; left: 50%; transform: translate(-50%, -50%);">
            <div class="modal-header">
                <h5 class="modal-title">Sign In as Admin</h5>
                <button type="button" id="adminCloseButton" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col text-center" style=" margin-bottom:12px;">
                        <strong style="font-size: 24px;  font-weight: bold;">Login to RentSpace</strong>
                    </div>
                </div>


                <div class="row">

                    <div class="col-md-4">
                        <img src="<?php echo e(asset('storage/images/admin.png')); ?>" alt="Your Image"
                            class="img-fluid mx-auto d-block img-full-width">
                    </div>

                    <div class="col-md-8">
                        <form method="POST" action="<?php echo e(route('admin.login')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input id="adminEmail" type="email"
                                    class="form-control <?php $__errorArgs = ['adminEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="adminEmail"
                                    value="<?php echo e(old('adminEmail')); ?>" placeholder="Enter your Email Address" required
                                    autocomplete="email" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input id="password" type="password"
                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password"
                                    placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                            <div class="form-group">
                                <span id="admError" class="text-danger"></span>
                            </div>
                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="remember">
                                    <?php echo e(__('Remember Me')); ?>

                                </label>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-danger"
                                    id="signInButton"><?php echo e(__('Sign In as Admin')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!-- Forget Password Modal -->
<div class="modal fade" id="forgetPasswordModal" tabindex="-1" role="dialog" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog custom-dialog" role="document">
        <div class="modal-content"
            style="  width:800px; position: fixed; top: 30%; left: 50%; transform: translate(-50%, -50%);">

            <div class="modal-header" style="border:none;">

                <button type="button" id="fgtCloseButton" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-14 text-center" style=" margin-bottom:12px;">
                        <strong style="font-size: 24px;  font-weight: bold;">Forgot Password</strong>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-5">
                        <img src="<?php echo e(asset('storage/images/forgot-password.png')); ?>" alt="Forget Password Image"
                            class="img-fluid mx-auto d-block img-full-width">
                    </div>

                    <div class="col-md-7">
                        <p class="forgot-description">If you forgot your password, please enter your registered email to
                            reset your password...</p>


                        <div class="alert <?php if(session('success')): ?> alert-success <?php elseif(session('error')): ?> alert-danger <?php else: ?> d-none <?php endif; ?>"
                            id="successMessage">
                            <?php if(session('success')): ?>
                            <?php echo e(session('success')); ?>

                            <?php elseif(session('error')): ?>
                            <?php echo e(session('error')); ?>

                            <?php endif; ?>
                        </div>

                        <form method="POST" action="<?php echo e(route('users.forget')); ?>" id="forgetForm">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input type="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    placeholder="Enter Your Email Address" id="email" name="email" required>
                                <span id="fgtError" class="text-danger"></span>
                            </div>


                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary"
                                    id="forgetButton"><?php echo e(__('Sent Forget Password Link to Email')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\rentalsystem\resources\views/HomeLogin.blade.php ENDPATH**/ ?>