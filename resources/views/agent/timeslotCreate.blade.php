<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Add New Timeslot</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div class="container mt-5">
    <a href="{{ route('appointments.agentIndex') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        
                        <h2 class="ml-3 mb-0">Add Timeslot</h2>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('timeslots.store') }}">
                            @csrf

                            <div class="form-group">
                                <label for="date">Date:</label>
                                <input type="date" id="date" name="date" class="form-control" required>
                                @error('date')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Select Timeslots:</label>
                                <div class="row">
                                    @php
                                    $start_time = strtotime('08:00');
                                    $end_time = strtotime('18:00');
                                    $interval = 30 * 60; // 30 minutes in seconds
                                    @endphp
                                    @for ($time = $start_time; $time <= $end_time; $time += $interval)
                                    @php
                                    $formatted_time = date('H:i', $time);
                                    $formattedEnd_time = date('H:i', $time + 30*60);
                                    @endphp
                                    <div class="col-4 mb-2">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" name="timeslots[]"
                                                value="{{ $formatted_time }}" id="{{ $formatted_time }}">
                                            <label class="custom-control-label"
                                                for="{{ $formatted_time }}">{{ $formatted_time}} - {{$formattedEnd_time }}</label>
                                        </div>
                                    </div>
                                    @endfor
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    @endsection
</body>

</html>
