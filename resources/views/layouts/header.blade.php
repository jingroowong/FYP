<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'RentSpace') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">


    <!-- Styles -->
    <style>
    body {

        margin: 0;
        padding: 0;
    }

    .container {
        width: 100%;

    }

    .navbar {
        position: relative;
        top: 0;
        left: 0;
        right: 0;
        background-color: #1d64ec;
        z-index: 1000;
    }

    .divider {
        border-top: none !important;
    }

    .navbar-brand img {
        height: 50px;
        margin-right: 15px;
    }

    .navbar-nav {
        display: flex;
        align-items: center;
    }

    .navbar-nav .nav-item {
        margin-right: 20px;
        white-space: nowrap;
    }

    .navbar a.nav-link {
        color: white;
    }

    .navbar-nav .divider {
        margin-right: 0;
    }

    .nav-item.no-padding {
        padding: 0;
    }

    .nav-item.no-margin {
        margin: 0;
    }

    .rounded-image {
        border-radius: 10px;
        width: 120px;
        height: 90px;

    }

    .navbar-nav .nav-item a.nav-link:hover {
        color: #6699ff !important;

    }

    .nav-link:hover .chat-icon {
        filter: brightness(90%);
        /* Adjust the brightness for the desired effect */
        transition: filter 0.3s ease;
        /* Add a smooth transition effect */
    }

    .navbar-light .navbar-nav .nav-link {
        color: white;
    }

    @media (max-width: 768px) {
        .navbar {
            height: 70px;
            position: fixed;
            width: 100%;
            background-color: #1d64ec;
        }

        .navbar-brand img {
            height: 50px;
        }
    }


    .ddl-menu:hover {
        color: #007bff;
        background-color: transparent;
        transition: color 0.3s ease;
    }


    .dropdown-menu {
        background-color: #ffffff;
        border: 1px solid #ccc;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }


    .dropdown-items {
        padding: 8px 16px;
        color: #333;
        text-decoration: none;
        display: block;
        transition: background-color 0.3s ease;
    }


    .dropdown-items i {
        margin-right: 10px;
        font-size: 18px;
    }

    .dropdown-items i:hover {
        margin-right: 10px;
        font-size: 18px;
        color: black;
    }

    .dropdown-items:hover {
        background-color: #87CEFA;
        color: black;
        text-decoration: none;
    }

    .dropdown-items:active {
        background-color: #0056b3;
    }


    .dropdown-items:disabled {
        color: #ccc;
        cursor: not-allowed;
    }



    #chat-icon-badge {
    position: absolute;
    top: 5px;
    right: 5px;
    width: 16px;
    height: 16px;
    background-color: red;
    border-radius: 55%;
    display: none;
    text-align: center; /* Center the text horizontally */
    line-height: 15px; /* Center the text vertically */
    color: white; /* Set the text color */
    font-size: 12px; /* Set the font size */
}


.notification-tooltip {
            position: absolute;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 4px;
            padding: 10px;
            z-index: 9999;
           width: 300px; /* Set the maximum width */
        }

    .notification-ui_dd-content {
        margin-bottom: 30px;
    }

    .notification-list {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        padding: 20px;
        margin-bottom: 7px;
        background: #fff;
        -webkit-box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.06);
    }

    .notification-list--unread {
        border-left: 2px solid #29B6F6;
    }

    .notification-list .notification-list_content .notification-list_img img {
        height: 48px;
        width: 48px;
        border-radius: 50px;
        margin-right: 20px;
    }

    .notification-list .notification-list_content .notification-list_detail p {
        margin-bottom: 5px;
        line-height: 1.2;
    }
    </style>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    @if(Auth::user())
    const userId = "{{ Auth::user()->id }}";
    const chatBadge = document.getElementById('chat-icon-badge');

    // Make an asynchronous request to check for messages
    $.ajax({
        url: `{{ url('/check-messages') }}/${userId}`,
        method: 'GET',
        success: function(response) {
            const unseenMessagesCount = response.unseenMessagesCount;
            console.log(unseenMessagesCount);
            // Update the badge visibility based on the response
            
            chatBadge.style.display = unseenMessagesCount ? 'inline-block' : 'none';
            if (unseenMessagesCount > 10) {
                chatBadge.textContent = '...'; // Display ellipsis
            } else {
                chatBadge.textContent = unseenMessagesCount; // Set the count as the content
            }
        },
        error: function(error) {
            console.error('Error checking messages:', error);
        }
    });
    @endif
});

    function logout() {
        if (confirm("Confirm to logout?")) {
            document.getElementById('logout-form').submit();
        }
    }
</script>
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand navbar-light shadow">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/HomePage') }}">
                    <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" class="rounded-image">
                </a>


                <ul class="navbar-nav">
                    <li class="nav-item mr-4">
                        <a class="nav-link" href="{{ route('HomePage') }}">{{ __('Home') }}</a>
                    </li>
                    <li class="nav-item mr-4">
                        <a class="nav-link" href="{{ route('propertyList') }}">{{ __('Property') }}</a>
                    </li>
                    <li class="nav-item mr-4">
                        <a class="nav-link" href="{{ route('AgentLists') }}">{{ __('Agent') }}</a>
                    </li>
                    <li class="nav-item mr-4">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('About Us') }}</a>
                    </li>
                </ul>

                <ul class="navbar-nav">
                    @if(!session('tenant'))
                    <li class="nav-item divider no-padding no-margin">
                        <a class="nav-link" href="{{ route('HomeLogin') }}"><img
                                src="{{ asset('storage/images/user.png') }}"
                                style="padding-right:2px; margin-right:0;"></a>
                    </li>


                    <li class="nav-item mr-3">
                        <a class="nav-link" href="{{ route('HomeLogin') }}"
                            style="padding-left:2px; margin-left:0;">{{ __('Sign In') }}</a>
                    </li>
                    @else
                    <li class="ddl-menu nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            <i class="fas fa-user-circle"></i> {{ "Hello, " . session('tenant')->tenantName }}
                        </a>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <a class="dropdown-items" href="{{ route('SearchHistory',['id' => session('tenant')->tenantID]) }}">
                                <i class="fas fa-history"></i> {{ __('Search History') }}
                            </a>
                            <a class="dropdown-items"
                                href="{{ route('WishLists',['id' => session('tenant')->tenantID]) }}">
                                <i class="fas fa-heart"></i> {{ __('My WishList') }}
                            </a>
                            <a class="dropdown-items"
                                href="{{ route('applicationIndex',['id' => session('tenant')->tenantID]) }}">
                                <i class="fa-regular fa-building"></i> {{ __('My Rental') }}
                            </a>
                            <a class="dropdown-items"
                                href="{{ route('appointments',['id' => session('tenant')->tenantID]) }}">
                                <i class="fa-solid fa-calendar-days"></i> {{ __('My Appointment') }}
                            </a>
                            <a class="dropdown-items"
                                href="{{ route('MyTenantAccount', ['id' => session('tenant')->tenantID]) }}">
                                <i class="fas fa-user"></i> {{ __('My Account') }}
                            </a>
                            <a class="dropdown-items" href="{{ route('users.logout') }}"
                                onclick="event.preventDefault(); logout();">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                            </a>
                            <form id="logout-form" action="{{ route('users.logout') }}" method="get" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </li>

                    @endif

                    @if(Auth::user())

                    <li class="nav-item divider no-padding no-margin">
                        <a id="chat-link" class="nav-link " href="{{ url('/chatify')}}"
                            style="padding-right:2px; margin-right:0; position: relative;">
                            {{ __('My Chat') }}
                        </a>
                    </li>

                    <li class="nav-item mr-4">
                        <a class="nav-link" href="{{ url('/chatify')}}"
                            style="padding-left:2px; margin-left:0; position: relative;">
                            <img src="{{ asset('storage/images/chat.png') }}" class="chat-icon"
                                style="width: 24px; height: 24px;">
                            <span id="chat-icon-badge"></span>
                        </a>
                    </li>
                @endif


                    <li class="nav-item custom-mr">
                        <a class="nav-link" id="notificationIcon" href="{{ route('notifications.tenant') }}"><img
                                src="{{ asset('storage/images/notification.png') }}"></a>
                    </li>
                </ul>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <script>
    $(document).ready(function() {
        // Function to fetch and display notifications
        function fetchNotifications() {
            $.ajax({
                url: "{{ route('notifications.latest') }}",
                type: 'GET',
                success: function(response) {
                    console.log("Notifications fetched:", response);
                    displayNotifications(response);
                },
                error: function(error) {
                    console.log("Error fetching notifications:", error);
                }
            });
        }

        // Function to display notifications in a tooltip
     
        function displayNotifications(response) {
            var notifications = response;

            var tooltip = $('<div class="notification-tooltip"></div>');

            // Check if notifications is an array
            if (Array.isArray(notifications) && notifications.length > 0) {
                var notificationList = $('<div class="notification-ui_dd-content"></div>');

                notifications.forEach(function(notification) {
                    var formattedTimestamp = moment(notification.timestamp).fromNow();


                    var notificationItem = `
                <div class="notification-list">
                    <div class="notification-list_content">
                        <div class="notification-list_detail">
                            <p><b>${notification.subject}</b></p><p> ${notification.content}</p>
                            <p class="text-muted">${formattedTimestamp}</p>
                        </div>
                    </div>
                </div>`;

                    notificationList.append(notificationItem);
                });

                tooltip.append(notificationList);
            } else {
                tooltip.append('<div class="no-notifications">No notifications</div>');
            }

            var iconPosition = $('#notificationIcon').offset();
            console.log("Icon position:", iconPosition);

            tooltip.css({
                top: iconPosition.top + $('#notificationIcon').outerHeight(),
                left: iconPosition.left-200
            });

            $('body').append(tooltip);

            $('#notificationIcon').mouseleave(function() {
                tooltip.remove();
            });
        }


        // Event listener for hovering over the notification icon
        $('#notificationIcon').mouseenter(function() {
            fetchNotifications();
        });
    });

   
    </script>
</body>

</html>