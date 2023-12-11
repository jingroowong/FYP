<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>

<body>
    @extends('layouts.adminApp')
    <style>

    </style>
    <script>

    </script>

    @section('content')
    <div class="ml-5 mt-2">

        <div class="row">
            <div class="col-md-1" style="padding:0;">
                <div class="form-group text-center">
                    @if (session('agent'))
                    <a href="{{ route('MyAgentAccount', ['id' => session('agent')->agentID]) }}" class="btn btn-primary"
                        style="padding: 10px 20px;">Back</a>
                    @else
                    <a href="{{ route('MyAgentAccount', ['id' => session('admin')->adminID]) }}" class="btn btn-primary"
                        style="padding: 10px 20px;">Back</a>
                    @endif
                </div>
            </div>
            <div class="col-md-8" style="padding:0;">
            </div>


        </div>

        <div class="edit-profile-title text-center">
            <h3>Reset New Password</h3>
            <p>You can set your new password here.</p>
        </div>

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @elseif(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

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

        <form action="{{ route('UpdateNewPassword') }}" method="post">
            @csrf
            @if (session('agent'))
            <input type="hidden" name="id" value="{{ session('agent')->agentID}}">
            <input type="hidden" name="userRole" value="agent">
            @else
            <input type="hidden" name="id" value="{{ session('admin')->adminID}}">
            <input type="hidden" name="userRole" value="admin">
            @endif


            <div class="form-group">
                <label for="currentPassword">Current Password:</label>
                <input type="password" id="currentPassword" name="currentPassword"
                    class="form-control @error('currentPassword') is-invalid @enderror"
                    placeholder="Enter Your Current Password" required>
                @error('currentPassword')
                <span class="text-danger">{{ $errors->first('currentPassword') }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">New Password:</label>
                <input type="password" id="password" name="password"
                    class="form-control @error('password') is-invalid @enderror" autocomplete="new-password"
                    placeholder="Enter Your New Password" required>
                @error('password')
                <span class="text-danger">{{ $errors->first('password') }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="confirmPassword">Confirm Password:</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror"
                    id="password-confirm" name="password_confirmation" placeholder="Confirm Your Confirm Password"
                    autocomplete="new-password" required>
                @error('password')
                <span class="text-danger">{{ $errors->first('password') }}</span>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-primary"
                    onclick="return confirm('Are you sure to reset your password?')">Reset Password</button>
            </div>
        </form>

    </div>

    @endsection
</body>

</html>