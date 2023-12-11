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
    @extends('layouts.adminApp')

    @section('content')
    <div class="ml-5 mt-2 agent">
    <a href="{{ url()->previous() }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back
        </a>
<div class="row">
        <div class="text-description col-md-6 offset-md-3 text-center">
            <img src="{{ asset('storage/images/agent.png') }}" width="50%" height="80%" alt="Your Image" class="userImg">
            <p>Update Agent</p>
          
        </div>

        <div class="col-md-6 offset-md-3">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('agents.update') }}">
                        @csrf
                        <div class="form-group">
                            <label for="username">User name:</label>
                            <input type="text" class="form-control @error('agentName') is-invalid @enderror"
                                id="agentName" name="agentName" placeholder="Enter Agent Name"
                                value="{{ $agent->agentName }}" required>
                            @error('agentName')
                            <span class="text-danger">{{ $errors->first('agentName') }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="email">Email Address:</label>
                            <input type="email" class="form-control @error('agentEmail') is-invalid @enderror"
                                id="agentEmail" name="agentEmail"
                                placeholder="Enter Agent Email Address (Eg: jiahon@gmail.com)"
                                value="{{ $agent->agentEmail }}" required>
                                <input type="hidden" name="agentID" value="{{ $agent->agentID }}">
                            @error('agentEmail')
                            <span class="text-danger">{{ $errors->first('agentEmail') }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone:</label>
                            <input type="tel" class="form-control @error('agentPhone') is-invalid @enderror"
                                id="agentPhone" name="agentPhone"
                                placeholder="Enter Agent Phone Number (Eg: 01x-xxxxxxx)" value="{{ $agent->agentPhone }}"
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
                                value="{{ $agent->licenseNum }}">
                            @error('licenseNum')
                            <span class="text-danger">{{ $errors->first('licenseNum') }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
    <label for="agentStatus">Agent Status:</label>
    <select class="form-control" id="agentStatus" name="agentStatus" required>
        <option value="active" {{ $agent->status === 'active' ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $agent->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
    @endsection
</body>
</html>