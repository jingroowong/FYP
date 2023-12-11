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
        <h2 class="text-center text-primary">Welcome to RentSpace</h2>
        <p class="lead text-center">Your Gateway to Seamless Property Transactions</p>

        <section class="mt-4">
            <h5>Discover Your Dream Home</h5>
            <p>Explore a diverse range of rental properties and homes for sale. Find the perfect place that suits your lifestyle and preferences.</p>
        </section>

        <section class="mt-4">
            <h5>List Your Property with Ease</h5>
            <p>Are you a property owner or agent? List your property effortlessly on PropertyHub and connect with potential tenants or buyers. Manage your listings with a user-friendly dashboard.</p>
        </section>

        <section class="mt-4">
            <h5>Schedule Viewings Conveniently</h5>
            <p>Book property viewings at your convenience. Receive instant notifications, track your scheduled viewings, and make informed decisions about your next home.</p>
        </section>

        <section class="mt-4">
            <h5>Stay Informed with Market Trends</h5>
            <p>Get insights into the real estate market. Stay informed about property trends, market prices, and neighborhood statistics to make well-informed decisions.</p>
        </section>

        <section class="mt-4">
            <h5>Expert Assistance at Your Fingertips</h5>
            <p>Our dedicated support team is ready to assist you. Have questions or need guidance? Reach out to our experts for personalized assistance throughout your property journey.</p>
        </section>
    </div>
    </div>
@endsection
</body>
</html>
