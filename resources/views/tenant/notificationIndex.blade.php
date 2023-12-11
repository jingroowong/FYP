<html>

<head>
    <meta charset="UTF-8">
    <title>View Notifications</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @extends('layouts.header')

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <style>
    .unreadNote {
        font-size: 15px;
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
        <h2>Notifications <span class="text-muted unreadNote"> ({{ $count }} notifications)</span>
        </h2>
<!-- Search Bar -->
<form action="{{ route('notifications.tenantSearch') }}" method="GET" class="mb-3" id="searchForm">
    <div class="input-group">
        <input type="text" name="search" class="form-control" placeholder="Search notifications" value="{{ isset($searchTerm) ? $searchTerm : '' }}">
        @if(isset($searchTerm))
            <div class="input-group-append">
                <button type="button" class="btn btn-secondary" onclick="clearSearch()">X</button>
            </div>
        @endif
        <div class="input-group-append">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </div>
</form>

<script>
    function clearSearch() {
        document.querySelector('input[name="search"]').value = '';
        document.getElementById('searchForm').submit();
    }
</script>





        @if(count($notifications) > 0)
        <form action="#" method="POST" id="notification-form">
            @csrf

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="check-all">
                            </th>
                            <th>Subject</th>
                            <th>Content</th>
                            <th>Time Received</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notifications as $notification)
                        <tr>
                            <td>
                                <input type="checkbox" class="notification-checkbox" name="notification[]"
                                    value="{{ $notification->notificationID }}">
                            </td>
                            <td>{{ $notification->subject }}</td>
                            <td>{{ $notification->content }}</td>
                            <td>{{ Carbon\Carbon::parse($notification->timestamp)->diffForHumans() }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $notifications->links() }}

            <div class="row">
                <div class="col">
                    <button class="btn btn-danger" id="delete" type="button">Delete</button>
                </div>
            </div>
            @else
            <p>No notifications available.</p>
            @endif

              <!-- Add this code to your HTML body -->
              <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
                aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteModalLabel">Confirm Deletion</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Are you sure you want to delete the selected notifications?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check/Uncheck All
            document.getElementById('check-all').addEventListener('change', function() {
                const checkboxes = document.querySelectorAll('.notification-checkbox');
                checkboxes.forEach(checkbox => checkbox.checked = this.checked);
            });

            // Handle Delete Button Click
            document.getElementById('delete').addEventListener('click', function() {
                // Show the Bootstrap Modal
                $('#confirmDeleteModal').modal('show');
            });

            // Handle Confirm Delete Button Click
            document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
                // Proceed with the form submission
                document.getElementById('notification-form').action = '{{ route('notifications.delete') }}';
                document.getElementById('notification-form').submit();

                // Close the Bootstrap Modal
                $('#confirmDeleteModal').modal('hide');
            });

              // Handle Close Button Click
        document.querySelector('#confirmDeleteModal .close').addEventListener('click', function () {
            // Manually close the Bootstrap Modal
            $('#confirmDeleteModal').modal('hide');
        });

        // Handle Cancel Button Click
        document.querySelector('#confirmDeleteModal .btn-secondary').addEventListener('click', function () {
            // Manually close the Bootstrap Modal
            $('#confirmDeleteModal').modal('hide');
        });
        });
    </script>

    @endsection
</body>

</html>