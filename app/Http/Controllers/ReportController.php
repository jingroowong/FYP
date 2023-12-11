<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\PropertyRental;
use App\Models\Property;
use App\Models\Agent;
use App\Models\Refund;
use App\Models\Deactivation;
use App\Models\Wallet;
use App\Models\WalletTransaction;
use App\Factories\AgentFactory;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Illuminate\Support\Facades\Mail;

class ReportController extends Controller
{

    public function indexAgent()
    {
        $agents = Agent::paginate(10);

        return view('admin/agentIndex', compact('agents'));
    }
    public function createAgent()
    {
        return view('admin/agentCreate');
    }

    public function searchAgent(Request $request)
    {
        $searchTerm = $request->input('search');


        $agents = Agent::where('agentID', 'like', '%' . $searchTerm . '%')
            ->orWhere('agentName', 'like', '%' . $searchTerm . '%')
            ->orWhere('agentPhone', 'like', '%' . $searchTerm . '%')
            ->orWhere('agentEmail', 'like', '%' . $searchTerm . '%')
            ->paginate(10);

        return view('admin/agentIndex', compact('agents','searchTerm'));
    }

    public function deleteAgent(string $agentID, Request $request)
    {
        // Find the agent by ID
        $agent = Agent::findOrFail($agentID);
        // Update agent status to inactive
        $agent->update(['status' => 'inactive']);

        // Store the deactivation reason in the deactivations table
        $deactivation = new Deactivation();
        $deactivation->agentID = $agentID;
        $deactivation->deactivation_reason = $request->input('deactivationReason');
        $deactivation->save();

        // Send email to the tenant
        $agentEmail = $agent->agentEmail;
        $header = 'Notification: Deactivation';
        $emailContent = 'We regret to inform you that your account has been deactivated. If you have any questions or concerns,
        please contact our support team.';
        $note = 'You can log in to your account for more details.';
        $desc = 'You are receiving this emailas a notification for the deactivation of your account.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Notification: Account deactivation');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }
        // Redirect to the agent index page with a success message
        return redirect()->route('indexAgent')->with('success', 'Agent deactivated successfully.');
    }

    public function updateAgent($agentID)
    {
        // Find the agent by ID
        $agent = Agent::findOrFail($agentID);

        // Redirect to the agent index page with a success message
        return view('admin/agentUpdate', compact('agent'));
    }

    public function update(Request $request)
    {

        $agentID = $request->input('agentID');
        // Find the agent by ID
        $agent = Agent::findOrFail($agentID);

        // Validate the request data
        $request->validate([
            'agentName' => 'required|string|max:40',
            'agentPhone' => 'required|string|max:12',
            'agentEmail' => 'required|string|max:50',
            'agentStatus' => 'required|in:active,inactive',

        ]);

        // Update the agent data
        $agent->update([
            'agentName' => $request->input('agentName'),
            'agentPhone' => $request->input('agentPhone'),
            'agentEmail' => $request->input('agentEmail'),
            'licenseNum' => $request->input('licenseNum'),
           'status' => $request->input('agentStatus')
 
        ]);

        // Redirect to the agent index page with a success message
        return redirect()->route('indexAgent')->with('success', 'Agent updated successfully.');
    }


    public function storeAgent(Request $request)
    {
        try {
            $data = $request->all();
            $AgentFactory = new AgentFactory();
            $agent = $AgentFactory->create($data);
            $agent->save();

            // Create a walle record
            $agentWallet = new Wallet;
            $agentWallet->walletID = $this->generateUniqueWalletID(); // Implement this function
            $agentWallet->balance = 0;
            $agentWallet->pinNumber = 0;
            $agentWallet->agentID = $agent->agentID;
            $agentWallet->save();

            return redirect()->route('indexAgent')->with('success', 'Agent created successfully.');
        } catch (\InvalidArgumentException $ex) {
            $errors = json_decode($ex->getMessage(), true);
            return redirect()->back()->withErrors($errors)->withInput();
        }
    }

    public function generateUniqueWalletID()
    {
        $latestWallet = Wallet::orderBy('walletID', 'desc')->first();

        if ($latestWallet) {
            $lastID = ltrim(substr($latestWallet->walletID, 3), '0'); // Remove the "WAL" prefix and leading zeros
            $nextID = 'WAL' . str_pad($lastID + 1, 7, '0', STR_PAD_LEFT); // Increment and pad to 7 digits
        } else {
            $nextID = 'WAL0000001'; // Initial ID
        }

        return $nextID;
    }

    public function showReports()
    {
        // Logic to show the reports page
        return view('admin/reportIndex');
    }

    public function generateReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:rental_transaction,agent_fees',
            'month' => 'required|date_format:Y-m',
        ]);

        [$year, $month] = explode('-', $request->input('month'));
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $reportType = $request->input('report_type');

        switch ($reportType) {
            case 'rental_transaction':
                $data = $this->generateRentalTransactionReport($startDate, $endDate);
                return view('admin/reportShowRental', compact('data', 'startDate', 'endDate'));
            case 'agent_fees':
                // Add logic for agent fees report if needed
                $data = $this->generateAgentFeesReport($startDate, $endDate);
                return view('admin/reportShowAgent', compact('data', 'startDate', 'endDate'));
            default:
                abort(404); // Handle invalid report type
        }
    }



    private function generateRentalTransactionReport($startDate, $endDate)
    {
        // Fetch data from the database
        $transactionData = PropertyRental::whereBetween('date', [$startDate, $endDate])
            ->with(['property', 'payment'])
            ->get();

        // Calculate total transaction amount
        $totalTransactionAmount = $transactionData->sum('payment.paymentAmount');

        // Calculate the number of transactions
        $numberOfTransactions = $transactionData->count();

        // Calculate the number of refund cases
        $numberOfRefundCases = Refund::whereBetween('refundDate', [$startDate, $endDate])->count();

        // Calculate occupancy rate
        $totalOccupiedProperties = PropertyRental::whereBetween('date', [$startDate, $endDate])->where('rentStatus', 'Completed')->count();
        $totalProperties = Property::count();
        if ($totalProperties != 0) {
            $occupancyRate = number_format(($totalOccupiedProperties / $totalProperties) * 100, 2);
        } else {
            $occupancyRate = 0;
        }
        // Get unique property types
        $propertyTypes = $transactionData->pluck('property.propertyType')->unique();
        // Retrieve refund transactions
        $refundTransactions = Refund::whereBetween('created_at', [$startDate, $endDate])->get();
        $totalRefundAmount = $refundTransactions->sum('refundAmount');


        // Prepare transaction types data
        $transactionTypes = $propertyTypes->map(function ($propertyType) use ($transactionData, $refundTransactions) {
            $typeTransactions = $transactionData->where('property.propertyType', $propertyType);

            return [
                'type' => $propertyType,
                'numberOfTransactions' => $typeTransactions->count(),
                'amount' => $typeTransactions->sum('payment.paymentAmount'),
                'refundCount' => Refund::whereIn('propertyRentalID', $typeTransactions->pluck('propertyRentalID'))
                    ->count(),
                'refundAmount' => Refund::whereIn('propertyRentalID', $typeTransactions->pluck('propertyRentalID'))->sum('refundAmount'),
            ];
        })->toArray();

        // Sort the array based on the 'amount' field
        usort($transactionTypes, function ($a, $b) {
            return $b['amount'] - $a['amount'];
        });

        // Transform the data for display
        $reportData = [
            'totalTransactionAmount' => $totalTransactionAmount,
            'numberOfTransactions' => $numberOfTransactions,
            'numberOfDays' => $startDate->diffInDays($endDate),
            'numberOfRefundCases' => $numberOfRefundCases,
            'occupancyRate' => $occupancyRate,
            'numberOfProperties' => $totalProperties,
            'numberOfOccupancy' => $totalOccupiedProperties,
            'transactionTypes' => $transactionTypes,
            'totalRefundAmount' => $totalRefundAmount
        ];

        return $reportData;
    }

    private function generateAgentFeesReport($startDate, $endDate)
    {
        // Fetch data for agent fees and postings
        $walletTransactions = WalletTransaction::where('transactionType', 'Payment')
            ->whereBetween('transactionDate', [$startDate, $endDate])
            ->get();


        // Calculate totals
        $totalAgentFees = $walletTransactions->sum('transactionAmount');
        $numberOfAgents = Agent::count();
        $numberOfListings = Property::all()->count();

        $expiredProperties = Property::where('expiredDate', '<', now())->get();
        $nonExpiredProperties = Property::where('expiredDate', '>', now())->get();
        $numberOfCollected = $nonExpiredProperties->count();
        $numberOfPending = $expiredProperties->count();

        if ($numberOfCollected != 0) {
            // Calculate collection rate
            $collectionRate = number_format($numberOfCollected / ($numberOfCollected + $numberOfPending) * 100, 2);
        } else {
            $collectionRate = 0;
        }
        // Fetch top agents with counts of transactions

        $topAgents = Agent::select('agents.agentID', 'agents.agentName', 'agents.agentPhone', 'agents.agentEmail', 'agents.password', 'agents.photo', 'agents.licenseNum')
            ->selectRaw('SUM(wallet_transactions.transactionAmount) as totalTransactionAmount')
            ->leftJoin('wallets', 'agents.agentID', '=', 'wallets.agentID')
            ->leftJoin('wallet_transactions', 'wallets.walletID', '=', 'wallet_transactions.walletID')
            ->leftJoin('properties', 'agents.agentID', '=', 'properties.agentID')
            ->where('wallet_transactions.transactionType', 'Payment')
            ->groupBy('agents.agentID', 'agents.agentName', 'agents.agentPhone', 'agents.agentEmail', 'agents.password', 'agents.photo', 'agents.licenseNum')
            ->orderByDesc('totalTransactionAmount')
            ->limit(5)
            ->get();

        // Transform the data for display
        $reportData = [
            'numberOfDays' => $startDate->diffInDays($endDate),
            'totalAgentFees' => $totalAgentFees,
            'numberOfAgents' => $numberOfAgents,
            'numberOfListings' => $numberOfListings,
            'collectionRate' => $collectionRate,
            'numberOfCollected' => $numberOfCollected,
            'numberOfPending' => $numberOfPending,
            'topAgents' => $topAgents->map(function ($agent) use ($startDate, $endDate) {
                return [
                    'agentID' => $agent->agentID,
                    'agentName' => $agent->agentName,
                    'numberOfPostings' => $agent->properties->count(),
                    'amountPaid' => $agent->wallet->transactions()
                        ->where('transactionType', 'Payment')
                        ->whereBetween('transactionDate', [$startDate, $endDate])
                        ->sum('transactionAmount'),
                ];
            }),


        ];

        return $reportData;


    }


}
