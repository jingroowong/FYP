<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report</title>
</head>

<body>
    @extends('layouts.adminApp')

    @section('content')
    <div class="ml-5 mt-2 container">
        <h2>Reports</h2>
        <div class="row">
            <div class="col-6">
                <form action="{{ route('reports.generate') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="report_type">Select Report Type:</label>
                        <select class="form-control" name="report_type">
                            <option value="rental_transaction">Rental Transaction</option>
                            <option value="agent_fees">Agent Fees</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="month">Select Month:</label>
                        <input type="month" class="form-control" name="month" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Show</button>
                </form>
            </div>
        </div>
    </div>
    @endsection
</body>

</html>