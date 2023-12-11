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
    <div class="container">
        <h1>Key Features</h1>

        <section>
            <h2>For Tenants</h2>
            <ul>
                <li>Easy Property Search</li>
                <li>Effortless Viewing Scheduling</li>
                <li>Secure Rental Application Process</li>
                <li>Real-time Notifications</li>
                <li>User-Friendly Dashboard</li>
            </ul>
        </section>

        <section>
            <h2>For Agents</h2>
            <ul>
                <li>Simple Property Listing Management</li>
                <li>Viewing Schedule Coordination</li>
                <li>Transparent Fee Structure</li>
                <li>Instant Notifications on Tenant Activity</li>
                <li>Detailed Analytics Dashboard</li>
            </ul>
        </section>

        <section>
            <h2>For Property Owners</h2>
            <ul>
                <li>Efficient Property Management</li>
                <li>Quick Tenant Approval Process</li>
                <li>Financial Transaction Tracking</li>
                <li>Customizable Property Listings</li>
                <li>24/7 Customer Support</li>
            </ul>
        </section>
    </div>
    </div>
@endsection
</body>
</html>