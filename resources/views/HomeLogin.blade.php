@extends('layouts.header')
<link rel="stylesheet" href="{{ asset('/storage/css/HomeLogin.css') }}" media="screen">


@section('content')

<script>
document.addEventListener("DOMContentLoaded", function() {

    const forgetButton = document.getElementById("forgetButton");


    forgetButton.addEventListener("click", function() {

        forgetButton.disabled = true;
        document.getElementById("forgetForm").submit();
    });

    @if(session('success') || session('error'))

    $('#forgetPasswordModal').modal('show');

    document.getElementById('fgtCloseButton').addEventListener('click', function() {
        $('#forgetPasswordModal').modal('hide');
    });
    @elseif(session('fgtError'))

    $('#forgetPasswordModal').modal('show');
    document.getElementById('fgtError').textContent =
        '*Email Address does not exist as registered email in RentSpace....';

    document.getElementById('fgtCloseButton').addEventListener('click', function() {
        $('#forgetPasswordModal').modal('hide');
        document.getElementById('fgtError').textContent = '';
    });
    @endif


    @if(session('tntError'))
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
    @endif

    @if(session('agtError'))
    $('#agentSignInModal').modal('show');
    document.getElementById('agtError').textContent =
        '*Authentication failed, Incorrect Email Address or Password*';

    document.getElementById('agentCloseButton').addEventListener('click', function() {
        $('#agentSignInModal').modal('hide');
        document.getElementById('agtError').textContent = '';
    });
    @endif

    @if(session('admError'))
    $('#adminSignInModal').modal('show');
    document.getElementById('admError').textContent =
        '*Authentication failed, Incorrect Email Address or Password*';

    document.getElementById('adminCloseButton').addEventListener('click', function() {
        $('#adminSignInModal').modal('hide');
        document.getElementById('admError').textContent = '';
    });
    @endif
});
</script>


<div id="app">
    <div class="container">

        @if (\Session::has('reset_success'))

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="success-alert text-center" id="autoHideAlert">
                    <p>{{ \Session::get('reset_success') }}</p>
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
        @endif
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
                    <img src="{{ asset('storage/images/landlord.png') }}" alt="Tenant Account" width="210" height="210">
                    <h4 class="card-title">Tenant Account</h4>
                    <p class="card-description">If you are an individual looking to rent or find rental property.</p>
                    <button class="sign-in-button btn btn-success" data-toggle="modal"
                        data-target="#tenantSignInModal">Sign In</button>

                    <div class="signup-message">
                        <p class="register-title">Don't have an account? <a href="{{ route('TenantRegister') }}"
                                class="register-tenant">Sign up here</a></p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card common-card d-flex flex-column align-items-center">
                    <img src="{{ asset('storage/images/agent.png') }}" alt="Landlord Account" width="210" height="210">
                    <h4 class="card-title">Agent Account</h4>
                    <p class="card-description">If you are a landlord or a professional real estate agent that wants to
                        rent properties.</p>
                    <button class="sign-in-button btn btn-primary" data-toggle="modal"
                        data-target="#agentSignInModal">Sign In</button>
                    <div class="signup-message">
                        <p class="register-title">Don't have an account? <a href="{{ route('AgentRegister') }}"
                                class="register-agent">Sign up here</a></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card common-card d-flex flex-column align-items-center">
                    <img src="{{ asset('storage/images/admin.png') }}" alt="Admin Account" width="210" height="210">
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
                <img src="{{ asset('storage/images/warning.png') }}" alt="Your Image" class="image">
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
                        <img src="{{ asset('storage/images/landlord.png') }}" alt="Your Image"
                            class="img-fluid mx-auto d-block img-full-width">
                    </div>

                    <div class="col-md-8">
                        <form method="POST" action="{{ route('tenant.login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input id="tenantEmail" type="email"
                                    class="form-control @error('tenantEmail') is-invalid @enderror" name="tenantEmail"
                                    value="{{ old('tenantEmail') }}" placeholder="Enter your Email Address" required
                                    autocomplete="email" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                            <div class="form-group">
                                <span id="tntError" class="text-danger"></span>
                            </div>
                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-success"
                                    id="signInButton">{{ __('Sign In as Tenant') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="signup-message col text-center">
                        <p class="register-title">Don't have an account? <a href="{{ route('TenantRegister') }}"
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
                        <img src="{{ asset('storage/images/agent.png') }}" alt="Your Image"
                            class="img-fluid mx-auto d-block img-full-width">
                    </div>

                    <div class="col-md-8">
                        <form method="POST" action="{{ route('agent.login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input id="agentEmail" type="email"
                                    class="form-control @error('agentEmail') is-invalid @enderror" name="agentEmail"
                                    value="{{ old('agentEmail') }}" placeholder="Enter your Email Address" required
                                    autocomplete="email" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                            <div class="form-group">
                                <span id="agtError" class="text-danger"></span>
                            </div>
                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary"
                                    id="signInButton">{{ __('Sign In as Agent') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="signup-message col text-center">

                        <p class="register-title">Don't have an account? <a href="{{ route('AgentRegister') }}"
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
                        <img src="{{ asset('storage/images/admin.png') }}" alt="Your Image"
                            class="img-fluid mx-auto d-block img-full-width">
                    </div>

                    <div class="col-md-8">
                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input id="adminEmail" type="email"
                                    class="form-control @error('adminEmail') is-invalid @enderror" name="adminEmail"
                                    value="{{ old('adminEmail') }}" placeholder="Enter your Email Address" required
                                    autocomplete="email" autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input id="password" type="password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    placeholder="Enter your password" required autocomplete="current-password">
                            </div>
                            <div class="form-group">
                                <span id="admError" class="text-danger"></span>
                            </div>
                            <div class="form-group form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-danger"
                                    id="signInButton">{{ __('Sign In as Admin') }}</button>
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
                        <img src="{{ asset('storage/images/forgot-password.png') }}" alt="Forget Password Image"
                            class="img-fluid mx-auto d-block img-full-width">
                    </div>

                    <div class="col-md-7">
                        <p class="forgot-description">If you forgot your password, please enter your registered email to
                            reset your password...</p>


                        <div class="alert @if (session('success')) alert-success @elseif (session('error')) alert-danger @else d-none @endif"
                            id="successMessage">
                            @if (session('success'))
                            {{ session('success') }}
                            @elseif (session('error'))
                            {{ session('error') }}
                            @endif
                        </div>

                        <form method="POST" action="{{ route('users.forget') }}" id="forgetForm">
                            @csrf
                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    placeholder="Enter Your Email Address" id="email" name="email" required>
                                <span id="fgtError" class="text-danger"></span>
                            </div>


                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary"
                                    id="forgetButton">{{ __('Sent Forget Password Link to Email') }}</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    @endsection