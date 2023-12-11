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
        <h2>Pricing Model</h2>

        <p>Welcome to RentSpace! Our pricing model is designed to provide flexibility and options for both property owners and agents.</p>

        <h4>Transaction Fees</h4>
        <p>Rental transactions on RentSpace are free. You can receive your rental from potential tenants without any upfront charges.</p>

        <h4>Posting Fee Duration and Cost</h4>
        <p>If you choose to extend the duration of your active property listing, a posting fee is applied. The posting fee helps maintain the visibility of your property on the platform.</p>

        <ul>
            <li>7 days extension: RM 14.00</li>
            <li>14 days extension: RM 25.20 (-10% off)</li>
            <li>30 days extension: RM 48.00 (-20% off)</li>
        </ul>

        <p>Please note that all fees are subject to change, and RentSpace will notify users of any updates to the pricing model.</p>

        <p>If you have any questions or need further clarification, feel free to <a href="mailto:rentspace@gmail.com">contact our support team</a>
.</p>
    </div>
    </div>
    @endsection
</body>
</html>
