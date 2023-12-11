@extends('layouts.header')

@section('content')
<main class="login-form">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="background-color: #87CEFA;">Reset New Password</div>
                    <div class="card-body">

                        <p class="description">You can reset your password below:</p>

                        @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                        @endif

                        <form action="{{ route('users.reset') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <div class="form-group">
                                <label for="email">Email Address:</label>
                                <input id="email" type="email"
                                    class="form-control no-border @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" placeholder="Enter your Email Address" required
                                    autocomplete="email" autofocus>
                                @error('email')
                                <span class="text-danger">{{ $errors->first('email') }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password">Password:</label>
                                <input type="password"
                                    class="form-control no-border @error('password') is-invalid @enderror" id="password"
                                    name="password" placeholder="Enter Password" required autocomplete="new-password">
                                @error('password')
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="password-confirm">Confirm Password:</label>
                                <input type="password"
                                    class="form-control no-border @error('password') is-invalid @enderror"
                                    id="password-confirm" name="password_confirmation"
                                    placeholder="Enter Confirm Password" autocomplete="new-password" required>
                                @error('password')
                                <span class="text-danger">{{ $errors->first('password') }}</span>
                                @enderror
                            </div>

                            <div class="form-group text-center">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
.card-header {
    text-align: center;
    font-size: 24px;
    color: white;
}

.card {
    border: solid black 1px;
}

.no-border {
    border: none;
    border-bottom: 1px solid #ccc;
    border-radius: 0;
    padding: 10px;
}

.btn-primary {
    background-color: #007bff;
    border: none;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
    border: 1px solid #f5c6cb;
    border-radius: 3px;
    padding: 10px;
    margin-bottom: 10px;
}

.text-danger {
    color: #721c24;
}

.description {
    color: #979595;
}
</style>
@endsection