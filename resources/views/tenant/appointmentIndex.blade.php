<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Appointments</title>
    @extends('layouts.header')
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    .btn-action {
       width:30%;
    }
    </style>

</head>

<body>
@section('content')
    <div class="container">
        @csrf
        @if(\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success')}}</p>
        </div><br />
        @endif
        <h2 class="mt-4 mb-4">Upcoming Appointments</h2>
        <p> Notes :To ensure prompt attendance, you can set reminders for scheduled appointments.</p>
<p> Both tenants and agents will going to receive a reminder notification one day and one hour prior to the scheduled appointment date.</p>
        @if(count($appointments) > 0)
        <table class="table">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Property</th>
                    <th>Timeslot</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                <tr>
                <td>{{ $appointment->appID }}</td>
                    <td>{{ $appointment->property->propertyName }}</td>
                    <td width="30%">{{ $appointment->timeslot->date}} ( {{ $appointment->timeslot->startTime }} - {{ $appointment->timeslot->endTime }} )</td>
                    <td>{{ $appointment->status }}</td>
                    <td width="40%">
                    @if($appointment->timeslot->date > now()&&$appointment->status=="Pending")
                        <!-- Display information or message indicating that the status is completed -->
                        @if($appointment->reminder == 1)
                        <a href="#" class="btn btn-success disabled btn-action" >Reminder Set</a>
                        @else
                        <a href="{{ route('appointments.setReminder', $appointment->appID) }}" id="setReminderBtn" class="btn btn-warning btn-action text-white">Set Reminder</a>
                        @endif
                        <a href="{{ route('appointments.editTenant', $appointment->appID) }}"
                            class="btn btn-primary btn-action">Modify</a>
                        <a href="{{ route('appointments.showTenant', $appointment->appID) }}"
                            class="btn btn-danger btn-action">Cancel</a>
                            @else
                       <span>Completed</span>
                          @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @else
        <p>No upcoming appointments..</p>
        @endif
       
        {{ $appointments->links() }}
    </div>
    @endsection
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>