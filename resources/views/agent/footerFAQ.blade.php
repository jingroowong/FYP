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
            margin-top: 50px;
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
<div class="container text-center-container">
        <h2 class="mb-4 text-center">RentSpace FAQs</h2>

        <!-- General Questions Section -->
       

            <div class="faq-item">
                <h5>What is RentSpace?</h5>
                <p>RentSpace is your go-to online platform connecting property owners, agents, and tenants for seamless property rental and sales transactions.</p>
            </div>

            <div class="faq-item">
                <h5>How can I list my property on RentSpace?</h5>
                <p>To list your property, sign up as an agent, access your personalized dashboard, and follow the simple steps to add your property details.</p>
            </div>

            <div class="faq-item">
                <h5>Is RentSpace available in my city?</h5>
                <p>RentSpace is actively expanding to various cities. Please check our homepage or contact our support team to confirm if we are available in your city.</p>
            </div>

            <div class="faq-item">
                <h5>How secure is my personal information?</h5>
                <p>Rest assured, we take the security of your information seriously. RentSpace employs robust security measures to safeguard your data.</p>
            </div>

            <!-- Add more general questions and answers -->

   


        <!-- Agent FAQs Section -->
        <div>
            <h2 class="mb-3 text-center">Agent FAQs</h2>

            <div class="faq-item">
                <h5>How do I manage my property listings?</h5>
                <p>Effortlessly manage your property listings by accessing your agent dashboard. Add new properties, update existing listings, and handle viewing schedules seamlessly.</p>
            </div>

            <div class="faq-item">
                <h5>What fees are associated with using RentSpace?</h5>
                <p>RentSpace charges a nominal fee for property listings. Refer to our pricing page for detailed information on our transparent fee structure.</p>
            </div>

            <!-- Add more agent-specific questions and answers -->
  
        </div>

        <!-- Additional Sections for Specific User Roles or Features -->

        <div class="mt-4">
            <p>Still have questions? <a href="mailto:rentspace@gmail.com">Contact Us</a></p>
        </div>
    </div>
    </div>
@endsection
</body>
</html>