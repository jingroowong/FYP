<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timeslots</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <style>
        .schedule-row {
            display: flex;
        }

        .schedule-cell {
            border: 1px solid #ccc;
            padding: 8px;
            width: 142px;
            text-align: center;
            flex-grow: 1;

        }

        .legend {
    display: flex;
    justify-content: center;
    margin-top: 10px;
}

.legend-item {
    display: flex;
    align-items: center;
    margin-right: 20px;
}

.legend-color {
    width: 20px;
    height: 20px;
    margin-right: 5px;
}

.available {
    background-color: #4dff4d;
}

.booked {
    background-color: #80bfff;
}

.tooltip-style {
    position: absolute;
    border: 1px solid #ccc;
    padding: 8px;
    background-color: #fff;
    /* Add any additional styles as needed */
}

    </style>
</head>

<body>
    <!-- resources/views/agent/timeslotCalendar.blade.php -->
    @extends('layouts.adminApp')

    @section('content')
    <div class="container mt-5">
        @csrf
        @if(\Session::has('success'))
        <div class="alert alert-success">
            <p>{{ \Session::get('success')}}</p>
        </div>
        @endif
        <a href="{{ route('timeslots') }}" class="btn btn-secondary mb-3">
            <i class="fas fa-arrow-left"></i> Back
        </a>

        <h2>Timeslots</h2>

        <div class="form-group">
            <label for="week">Select Week:</label>
            <input type="week" id="week" name="week" class="form-control" required>
        </div>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-color available"></div>
                <div class="legend-text">Available</div>
            </div>
            <div class="legend-item">
                <div class="legend-color booked"></div>
                <div class="legend-text">Booked</div>
            </div>
        </div>

        <div id="schedule-container" class="schedule-container">
            <div class="schedule-row">
                <div class="schedule-cell day">Time</div>
                <div class="schedule-cell day">MON</div>
                <div class="schedule-cell day">TUE</div>
                <div class="schedule-cell day">WED</div>
                <div class="schedule-cell day">THU</div>
                <div class="schedule-cell day">FRI</div>
                <div class="schedule-cell day">SAT</div>
                <div class="schedule-cell day">SUN</div>
            </div>
        </div>

        <div id="timeslot-container" class="schedule-container">
            <!-- Timeslots will be dynamically inserted here -->
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        let startTime;
        let endTime;
        const daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

        document.getElementById('week').addEventListener('change', function () {
            const selectedWeek = this.value;
            startTime = new Date('1970-01-01T08:00:00').getTime();
            endTime = new Date('1970-01-01T18:00:00');
            loadTimeslots(selectedWeek);
        });

        function loadTimeslots(week) {
            console.log('Fetching timeslots for week:', week);

            const timeslotContainer = document.getElementById('timeslot-container');
            timeslotContainer.innerHTML = ''; // Clear existing content

            fetch(`/api/timeslots?week=${week}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(timeslots => {
                    const timetable = createTimetable();

                    // Update timetable based on booked slots
                    timeslots.forEach(slot => {
                        const slotDay = new Date(`${slot.date}T${slot.startTime}`).getUTCDay();
                        const slotStartTime = new Date(`${slot.date}T${slot.startTime}`).getHours() * 60 + new Date(`${slot.date}T${slot.startTime}`).getMinutes();
                        const slotEndTime = new Date(`${slot.date}T${slot.endTime}`).getHours() * 60 + new Date(`${slot.date}T${slot.endTime}`).getMinutes();

                        for (let time = slotStartTime; time < slotEndTime; time += 30) {
                            timetable[slotDay][time].booked = true;
                            timetable[slotDay][time].hasAppointment = Boolean(slot.appointment); // Check if there is an appointment
                            timetable[slotDay][time].appointmentDetails = slot.appointment ? slot.appointment : null;
                            if (Boolean(slot.appointment)) {
                                timetable[slotDay][time].appointmentName = slot.appointment.name;
                            }
                        }
                    });

                    // Render the timetable
                    renderTimetable(timeslotContainer, timetable);
                })
                .catch(error => {
                    console.error('Error fetching timeslots:', error);
                });
        }

        function createTimetable() {
            const timetable = [];
            for (let day = 0; day < 7; day++) {
                timetable[day] = {};
                for (let time = 8 * 60; time < 18 * 60; time += 30) {
                    timetable[day][time] = { booked: false };
                }
            }
            return timetable;
        }

        function renderTimetable(container, timetable) {

            // Render timetable cells
            for (let time = 8 * 60; time < 18 * 60; time += 30) {
                const row = document.createElement('div');
                row.classList.add('schedule-row');

                // Time cell
                const timeCell = document.createElement('div');
                timeCell.classList.add('schedule-cell', 'time-cell');
                timeCell.textContent = formatTime(time);
                row.appendChild(timeCell);

                for (let day = 0; day < 7; day++) {
                    const timeslotCell = document.createElement('div');
                    timeslotCell.classList.add('schedule-cell');
                    if (timetable[day][time].hasAppointment) {
                        timeslotCell.classList.add('booked');
                       
                        // Add mouseover event to display appointment details
                        timeslotCell.addEventListener('mouseover', (event) => {
                            showAppointmentDetails(timetable[day][time].appointmentDetails, event);
                        });

                        // Add mouseout event to hide appointment details
                        timeslotCell.addEventListener('mouseout', hideAppointmentDetails);

                    } else if (timetable[day][time].booked) {
                        timeslotCell.classList.add('available');
                      
                    }

                    row.appendChild(timeslotCell);
                }

                container.appendChild(row);
            }
            function showAppointmentDetails(appointment, event) {
    // Create or update a tooltip to display appointment details
    let tooltip = document.getElementById('appointment-tooltip');

    if (!tooltip) {
        tooltip = document.createElement('div');
        tooltip.id = 'appointment-tooltip';
        tooltip.classList.add('tooltip-style'); // Add the CSS class
        document.body.appendChild(tooltip);
    }

    // Get the mouse coordinates
    const mouseX = event.pageX;
    const mouseY = event.pageY;

    // Calculate the position of the tooltip next to the mouse cursor
    tooltip.style.left = `${mouseX + 10}px`; // Adjust the offset as needed
    tooltip.style.top = `${mouseY + 10}px`; // Adjust the offset as needed

    const tooltipContent = `Name: ${appointment.name}<br>Email: ${appointment.email}<br>Headcount: ${appointment.headcount}<br>Contact: ${appointment.contactNo}`;
    tooltip.innerHTML = tooltipContent;
    tooltip.style.display = 'block';
}




            function hideAppointmentDetails() {
                // Hide the tooltip when mouseout
                const tooltip = document.getElementById('appointment-tooltip');
                if (tooltip) {
                    tooltip.style.display = 'none';
                }
            }
        }


        function formatTime(time) {
            const hours = Math.floor(time / 60);
            const minutes = time % 60;
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
        }


    </script>
    @endsection

</body>

</html>