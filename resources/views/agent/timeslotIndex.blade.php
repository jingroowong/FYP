<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeslot</title>
    <style>
        .timeslot {
            width: 90%;
        }
    </style>
</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div class="ml-5 mt-2 timeslot">
        @csrf
        @if(\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success')}}</p>
        </div><br />
        @endif
        <a href="{{ route('appointments.agentIndex') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>



        <h2>Timeslots <a href="{{ route('timeslots.calendar') }}" class="btn btn-primary mb-3">
                <i class="las la-calendar"></i>View in Calendar
            </a></h2>
        <div id="calendar"></div>

        <div class="timeslotTable">
            @if(count($timeslots) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($timeslots as $timeslot)
                    <tr>
                        <td>{{ $timeslot->timeslotID }}</td>
                        <td>{{ $timeslot->startTime }}</td>
                        <td>{{ $timeslot->endTime }}</td>
                        <td>{{ $timeslot->date }}</td>
                        <td>
                            @php
                            $appointments = $timeslot->appointments;
                            $latestAppointment = $appointments->last();
                            $previousAppointment = $appointments->count() > 1 ? $appointments[$appointments->count() -
                            2] : null;
                            @endphp

                            @if (!$latestAppointment)
                            <form action="{{ route('timeslots.destroy', $timeslot->timeslotID) }}" method="get"
                                style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger"
                                    onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                            @else
                            <div>
                               
                                @if ($latestAppointment->status == "Pending")
                                <span class="text-muted">Booked</span>
                                @elseif ($latestAppointment->status == "Cancelled")

                                @if ($previousAppointment)
                                <div>
                                    
                                    @if ($previousAppointment->status == "Pending")
                                    <span class="text-muted">Booked</span>
                                    @elseif ($previousAppointment->status == "Cancelled")
                                    <span class="text-muted">Cancelled</span>
                                    @elseif ($previousAppointment->status == "Completed")
                                    <span class="text-muted">Completed</span>

                                    @endif
                                    @else
                                    <span class="text-muted">Cancelled</span>
                                </div>
                                @endif
                                @elseif ($latestAppointment->status == "Completed")
                                <span class="text-muted">Completed</span>
                                @endif
                            </div>


                            @endif
                        </td>
                    </tr>
                    @endforeach


                </tbody>
            </table>
            @else
            <p>No timeslots record..</p>
            @endif
            {{ $timeslots->links() }}
        </div>
    </div>
    @endsection
</body>

</html>