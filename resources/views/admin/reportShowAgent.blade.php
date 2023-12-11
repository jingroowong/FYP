<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
    .donut-inner {
        margin-top: 40%;
      
    }

    .donut-inner span { 
        font-size: 50px;
        margin-left:-50vh;
    }

    </style>
</head>

<body>
@extends('layouts.adminApp')

@section('content')
<div class="ml-5 mt-2 container">
<a href="{{ route('reports') }}" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back
        </a>
      
        <div class="row">
        <div class="col-6">
        <h2>Agent Fees Summary Report</h2>
        <p><strong>Period:</strong> {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }} </p>
    </div>
    <div class="col-3">
        <button class="btn btn-primary py-3 px-3 mt-2" onclick="printReceipt()">Print Report</button>
</div>
</div>
        <table class="table">
            <thead>
                <tr>
                    <th>Total Agent Fees Collected</th>
                    <th>Number of Days</th>
                    <th>Number of Agent(s)</th>
                    <th>Number of Property Posting(s)</th>
                    <th>Collection Rates</th>
                    <th>Collected</th>
                    <th>Pending</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $data['totalAgentFees'] }}</td>
                    <td>{{ $data['numberOfDays'] }}</td>
                    <td>{{ $data['numberOfAgents'] }}</td>
                    <td>{{ $data['numberOfListings'] }}</td>
                    <td>{{ $data['collectionRate'] }} %</td>
                    <td>{{ $data['numberOfCollected'] }}</td>
                    <td>{{ $data['numberOfPending'] }}</td>
                </tr>
            </tbody>
        </table>

        <h2>Collection Rate</h2>
        <div wire:ignore>
            <div class="chart-container d-flex justify-content-center" style="height: 50vh; width: 50vh;">
                <canvas id="donutChart"></canvas>
                <div class="donut-inner d-flex justify-content-center">
                    <span>{{ $data['collectionRate'] }} %</span>
                </div>
            </div>
        </div>

</br>
        <h3>Top 5 Agents</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Agent ID</th>
                    <th>Agent Name</th>
                    <th>Number of Postings</th>
                    <th>Amount Paid</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data['topAgents'] as $agent)
                <tr>
                    <td>{{ $loop->iteration}} </td>
                    <td>{{ $agent['agentID'] }}</td>
                    <td>{{ $agent['agentName'] }}</td>
                    <td>{{ $agent['numberOfPostings'] }}</td>
                    <td>{{  number_format($agent['amountPaid'],0) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3">Total</th>
                    <th>{{ $data['numberOfListings'] }}</th>
                    <th>{{ $data['totalAgentFees'] }}</th>
                  
                </tr>
            </tfoot>
        </table>

        <script>
document.addEventListener('DOMContentLoaded', function () {
    var ctx = document.getElementById('donutChart').getContext('2d');

    var data = {
        labels: ['Collected', 'Pending'],
        datasets: [{
            data: [{{ $data['numberOfCollected'] }}, {{ $data['numberOfPending'] }}],
            backgroundColor: ['rgba(255, 99, 132, 0.5)', 'rgba(255, 255, 255, 0.5)'],
            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(255, 255, 255, 1)'],
            borderWidth: 1
        }]
    };

    var collectionRate = {{ $data['collectionRate'] }};
    var collectionRateLabel = 'Collection Rate: ' + collectionRate.toFixed(2) + '%';

    var options = {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '70%',
        plugins: {
            legend: {
                display: false,
            },
            labels: {
                render: 'percentage',
                fontColor: 'black',
                fontSize: 14,
                fontStyle: 'bold',
                position: 'default',
                textMargin: 8,
                overlap: true,
            }
        },
    };

    // Destroy existing chart if it exists
    if (window.myDonutChart) {
        window.myDonutChart.destroy();
    }

    window.myDonutChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
    });

    // Draw label
    var fontSize = 20;
    ctx.font = fontSize + "px Arial";
    ctx.fillStyle = 'black';
    ctx.textAlign = 'center';
    ctx.fillText(collectionRateLabel, ctx.canvas.width / 2, ctx.canvas.height / 2);
});
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

<script>
function printReceipt() {
    window.print();
}
</script>
    </div>
 @endsection
</body>

</html>