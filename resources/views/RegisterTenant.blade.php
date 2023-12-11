@extends('layouts.header')
<link rel="stylesheet" href="{{ asset('/storage/css/UserRegistration.css') }}" media="screen">
@section('content')
<script>

</script>
<div class="container">
    @if (\Session::has('success'))
    <script>
    function countdown() {
        var seconds = 5;
        var countdownElement = document.getElementById('countdown');

        var timer = setInterval(function() {
            seconds--;
            countdownElement.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(timer);
                window.location.href = "{{ route('HomeLogin') }}";
            }
        }, 1000);
    }
    window.onload = countdown;
    </script>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="success-alert text-center">
                <p>{{ \Session::get('success') }}. Redirecting to <a href="{{ route('HomeLogin') }}"
                        class="no-underline">Login Page</a> in <span id="countdown">5</span> seconds...</p>
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="text-description col-md-6 offset-md-3 text-center">
            <img src="{{ asset('storage/images/landlord.png') }}" alt="Your Image" class="userImg">
            <p>Please fill up the fields to sign up for a Tenant Account.</p>
            <div class="signup-message col text-center">
                <p class="register-title">Already have an account? <a href="{{ route('HomeLogin') }}"
                        class="register-tenant">Sign in here</a></p>
            </div>
        </div>

        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('tenant.register') }}">
                        @csrf
                        <div class="form-group">
                            <label for="username">User name:</label>
                            <input type="text" class="form-control @error('tenantName') is-invalid @enderror"
                                id="tenantName" name="tenantName" placeholder="Enter Your User Name"
                                value="{{ old('tenantName') }}" required>
                            @error('tenantName')
                            <span class="text-danger">{{ $errors->first('tenantName') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address:</label>
                            <input type="email" class="form-control @error('tenantEmail') is-invalid @enderror"
                                id="tenantEmail" name="tenantEmail"
                                placeholder="Enter Your Email Address (Eg: jiahon@gmail.com)"
                                value="{{ old('tenantEmail') }}" required>
                            @error('tenantEmail')
                            <span class="text-danger">{{ $errors->first('tenantEmail') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password" placeholder="Enter Password" required
                                autocomplete="new-password">
                            @error('password')
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="password-confirm">Confirm Password:</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password-confirm" name="password_confirmation" placeholder="Enter Confirm Password"
                                autocomplete="new-password" required>
                            @error('password')
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="tel" class="form-control @error('tenantPhone') is-invalid @enderror"
                                id="tenantPhone" name="tenantPhone"
                                placeholder="Enter Your Phone Number (Eg: 01x-xxxxxxx)" value="{{ old('tenantPhone') }}"
                                required>
                            @error('tenantPhone')
                            <span class="text-danger">{{ $errors->first('tenantPhone') }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="tenantDOB">Date of Birth:</label>
                            <input type="date" value="{{ old('tenantDOB') }}" class="form-control @error('tenantDOB') is-invalid @enderror"
                                id="tenantDOB" name="tenantDOB" required>
                            @error('tenantDOB')
                            <span class="text-danger">{{ $errors->first('tenantDOB') }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="gender">Gender:</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <option value="M">Male</option>
                                <option value="F">Female</option>
                            </select>
                            @error('gender')
                            <span class="text-danger">{{ $errors->first('gender') }}</span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center">
                                <div class="signup-button">
                                    <button type="submit" class="btn btn-success"
                                        onclick="return confirm('Are you sure to register?')">Sign up as Tenant</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection