<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Property Pricing</title>
    <style>
    #footer {
        font-family: 'Arial', sans-serif;
        line-height: 1.6;
        color: #343a40;
        margin: 0;
        padding: 0;
    }

    #footer .container {
        max-width: 800px;
        margin: 0 auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-top: 20px;
    }

    #footer h2 {
        color: #007bff;
    }

    #footer h3 {
        color: #007bff;
    }

    #footer ul {
        list-style-type: none;
        padding: 0;
    }

    #footer li {
        margin-bottom: 10px;
    }

    #footer p {
        line-height: 1.4;
    }

    #footer a {
        color: #007bff;
        text-decoration: none;
        font-weight: bold;
    }

    #footer a:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div id="footer">
        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <h1 class="text-center mb-4">About RentSpace</h1>

                    <div class="text-center mb-4">
                        <p class="lead">Welcome to RentSpace, where we redefine the property rental experience. Our
                            platform
                            is designed to seamlessly connect property owners, agents, and tenants, streamlining the
                            entire
                            rental process.</p>
                    </div>

                    <div class="text-center mb-4">
                        <h2>Our Vision</h2>
                        <p>At RentSpace, our vision is to establish a modern and secure online environment that
                            simplifies
                            and enhances the property rental journey for all stakeholders.</p>
                    </div>

                    <div class="text-center">
                        <h2>Why RentSpace?</h2>
                        <p>RentSpace stands out with its commitment to providing:</p>

                        <ul class="list-group">
                            <li class="list-group-item"><strong>Extensive Property Listings:</strong> Discover a diverse
                                range of rental properties tailored to your preferences.</li>
                            <li class="list-group-item"><strong>Effortless Scheduling:</strong> Easily schedule property
                                viewings at your convenience.</li>
                            <li class="list-group-item"><strong>Secure Transactions:</strong> Trust in our secure online
                                transaction process for a worry-free rental experience.</li>
                            <li class="list-group-item"><strong>Transparent Fees:</strong> Experience clarity with
                                transparent and fair fee structures.</li>
                            <li class="list-group-item"><strong>Exceptional Support:</strong> Our dedicated customer
                                support
                                team is here to assist you throughout your journey.</li>
                        </ul>

                        <p class="mt-3">Join RentSpace today and embark on a journey where renting properties becomes an
                            enriching and straightforward experience.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection
</body>

</html>