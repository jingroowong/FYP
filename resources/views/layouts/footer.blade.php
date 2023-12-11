<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="canonical" href="https://getbootstrap.com/docs/5.1/examples/footers/">

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">



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

        .footer {
            padding: 2%;
        }

        .footer .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }
    </style>



</head>

<body>

<!-- Your existing HTML content -->

<div class="footer">
    <footer class="py-3 my-4 border-top">
        <ul class="nav justify-content-center pb-3 mb-3">
            <li class="nav-item"><a href="{{ route('home') }}" class="nav-link px-2 text-muted" onclick="handleLinkClick('home')">Home</a></li>
            <li class="nav-item"><a href="{{ route('feature') }}" class="nav-link px-2 text-muted" onclick="handleLinkClick('feature')">Features</a></li>
            <li class="nav-item"><a href="{{ route('pricing') }}" class="nav-link px-2 text-muted" onclick="handleLinkClick('pricing')">Pricing</a></li>
            <li class="nav-item"><a href="{{ route('faq') }}" class="nav-link px-2 text-muted" onclick="handleLinkClick('faq')">FAQs</a></li>
            <li class="nav-item"><a href="{{ route('aboutUs') }}" class="nav-link px-2 text-muted" onclick="handleLinkClick('about')">About</a></li>
        </ul>
     
        <p class="text-bold text-muted text-center"> RentSpace &copy; 2023 Your favourite Property Rental Platform </p>
   
    </footer>
</div>


<script>
    // Function to remove a cookie by name
    function removeCookie(name) {
        document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;';
    }

    // Function to handle link click and remove 'activeLink' cookie
    function handleLinkClick(link) {
        // Remove 'activeLink' cookie
        removeCookie('activeLink');
    }
</script>

</body>
</html>
