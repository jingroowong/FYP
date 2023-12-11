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
            <img src="{{ asset('storage/images/agent.png') }}" alt="Your Image" class="userImg">
            <p>Please fill up the fields to sign up for a Agent Account.</p>
            <div class="signup-message col text-center">
                <p class="register-title">Already have an account? <a href="{{ route('HomeLogin') }}"
                        class="register-agent">Sign in here</a></p>
            </div>
        </div>

        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('agent.register') }}">
                        @csrf
                        <div class="form-group">
                            <label for="username">User name:</label>
                            <input type="text" class="form-control @error('agentName') is-invalid @enderror"
                                id="agentName" name="agentName" placeholder="Enter Your User Name"
                                value="{{ old('agentName') }}" required>
                            @error('agentName')
                            <span class="text-danger">{{ $errors->first('agentName') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address:</label>
                            <input type="email" class="form-control @error('agentEmail') is-invalid @enderror"
                                id="agentEmail" name="agentEmail"
                                placeholder="Enter Your Email Address (Eg: jiahon@gmail.com)"
                                value="{{ old('agentEmail') }}" required>
                            @error('agentEmail')
                            <span class="text-danger">{{ $errors->first('agentEmail') }}</span>
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
                            <input type="tel" class="form-control @error('agentPhone') is-invalid @enderror"
                                id="agentPhone" name="agentPhone"
                                placeholder="Enter Your Phone Number (Eg: 01x-xxxxxxx)" value="{{ old('agentPhone') }}"
                                required>
                            @error('agentPhone')
                            <span class="text-danger">{{ $errors->first('agentPhone') }}</span>
                            @enderror
                        </div>

                        <div class="form-group">

                            <label for="licenseNum">License Number</label><span class="text-danger"> *IF any* </span>:
                            <input type="text" class="form-control @error('licenseNum') is-invalid @enderror"
                                id="licenseNum" name="licenseNum"
                                placeholder="Enter Your License Number (Eg: REAXXXXX or RENXXXXX) - Optional"
                                value="{{ old('licenseNum') }}">
                            @error('licenseNum')
                            <span class="text-danger">{{ $errors->first('licenseNum') }}</span>
                            @enderror
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

@endsection