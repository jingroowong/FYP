<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/sidebars/">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <style>
    .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
    }

    @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
    }

    .adminSidebar body {
        min-height: 100vh;
        min-height: -webkit-fill-available;
    }

    html {
        height: -webkit-fill-available;
    }

    .adminSidebar {
        display: flex;
        flex-direction: column;
        /* Ensure a column layout */
        height: 100vh;
        /* Full height of the viewport */
        overflow-x: auto;
        overflow-y: hidden;
        overflow-x: hidden;
        /* Hide horizontal scrollbar */
    }

    .adminSidebar .flex-grow-1 {
        flex-grow: 1;
    }

    .adminSidebar .bars {
        font-size: 30px;
    }

    .nav-link.active {
        background-color: #007bff;
        color: #fff;
    }

    .nav-link {
        background-color: #fff;
        color: #000;
    }

    .logo{
        margin-top : 50px;
        margin-left: 30px;
    }
    .profileTitle{
        border-bottom: 1px solid; 
    }

    .title, .agentIcon{
        margin-bottom: 20px;
        font-size: 25px;
        font-weight:bold;
    }

    .title{
        margin-left: 10px;

    }

    .agentIcon{
        margin-left: 20px;
    }
    </style>

</head>

<body>
    <div class="adminSidebar border-right">
    <a href="/" class="logo">
<img class="bi me-2" width="100" height="40"
    src="{{Storage::url('images/rentSpaceLogo.png')}}" alt="RentSpace Logo">
</a>
        <div class="d-flex flex-column bg-white py-4" style="width: 195px;">
            <div class="bars profileTitle">
                <i class="agentIcon las la-user-check"></i>
                @if(session('admin'))
                <span class="title">Admin</span>
                @else
                <span class="title">Agent</span>
                @endif
            </div>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto">
            @php
                    $userId = session('admin') ? session('admin')->adminID : session('agent')->agentID;
           @endphp
                <li class="nav-item">
                    <a href="{{route('MyAgentAccount', ['id' => $userId])}}" class="nav-link link-dark" aria-current="page" onclick="handleNavClick(this)">
                        <i class="las la-user"></i>
                        Profile
                    </a>
                </li>
                <!-- Admin Only Start -->
                @if(session('admin'))
                <li>
                    <a href="{{route('indexAgent')}}" class="nav-link link-dark" onclick="handleNavClick(this)">
                    <i class="las la-user-tie"></i>
                        Agents
                    </a>
                </li>

                <li>
                    <a href="{{route('properties.all')}}" class="nav-link link-dark" onclick="handleNavClick(this)">
                        <i class="las la-money-bill"></i>
                        Property
                    </a>
                </li>
              
             

                <li>
                    <a href="{{route('reports')}}" class="nav-link link-dark" onclick="handleNavClick(this)">
                        <i class="las la-file-alt"></i>
                        Reports
                    </a>
                </li>
                <li>
                    <a href="{{route('refunds')}}" class="nav-link link-dark" onclick="handleNavClick(this)">
                        <i class="las la-hand-holding-usd"></i>
                        Refund
                    </a>
                </li>
             
                @else
                <li>
                    <a href="{{route('properties')}}" class="nav-link link-dark" onclick="handleNavClick(this)">
                        <i class="las la-money-bill"></i>
                        Property
                    </a>
                </li>
                <!-- Admin Only End -->
                 <!-- Agent Only Start -->
                <li>
                    <a href="{{route('notifications')}}" class="nav-link link-dark" onclick="handleNavClick(this)">
                        <i class="las la-envelope-open"></i>
                        Notifications
                    </a>
                </li>

               
                <li>
                    <a href="{{route('agentWallet')}}" class="nav-link link-dark" onclick="handleNavClick(this)">
                        <i class="las la-wallet"></i>
                        Wallet
                    </a>
                </li>

                <li>
                    <a href="{{route('appointments.agentIndex')}}" class="nav-link link-dark"
                        onclick="handleNavClick(this)">
                        <i class="las la-calendar"></i>
                        Schedule
                    </a>
                </li>

                <li>
                    <a href="{{ url('/chatify')}}" class="nav-link link-dark" onclick="handleNavClick(this)">
                        <i class="las la-sms"></i>
                        My Chat
                    </a>
                </li>
                @endif
                <!-- Agent Only End -->
                <li>
                    <a href="{{route('users.logout')}}" class="nav-link link-dark" onclick="handleLogout()">
                        <i class="las la-sign-out-alt"></i>
                        Log Out
                    </a>
                </li>
            </ul>
            <hr>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
    function handleNavClick(element) {
        // Remove 'active' class from all nav links
        var navLinks = document.querySelectorAll('.nav-link');
        navLinks.forEach(link => link.classList.add('link-dark', 'text-dark'));

        // Add 'active' class to the clicked link
        element.classList.remove('link-dark', 'text-dark');
        element.classList.add('active');

        // Save active link to cookie
        document.cookie = 'activeLink=' + element.textContent.trim() + '; path=/';
    }

    // Check cookie for active link on page load
    document.addEventListener('DOMContentLoaded', function() {
        var activeLink = getCookie('activeLink');
        if (activeLink) {
            var navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                if (link.textContent.trim() === activeLink) {
                    link.classList.remove('link-dark', 'text-dark');
                    link.classList.add('active');
                }
            });
        }
    });

    // Function to get cookie value by name
    function getCookie(name) {
        var match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) return match[2];
    }

    // Function to remove a cookie by name
function removeCookie(name) {
    document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
}

// Function to handle logout
function handleLogout() {
    // Remove 'activeLink' cookie
    removeCookie('activeLink');
}
    </script>
</body>

</html>