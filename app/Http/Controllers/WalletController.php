<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Agent;
use App\Models\Wallet;
use App\Models\Property;
use App\Models\WalletTransaction;
use App\Models\PropertyRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class WalletController extends Controller
{
    /**
     * Display the agent's wallet page.
     */
    public function index()
    {
        $agentID = optional(session('agent'))->agentID;
        //Retrieve the agent's wallet
        $agentWallet = Agent::find($agentID)->wallet;
        if (!$agentWallet) {
            // Create a walle record
            $agentWallet = new Wallet;
            $agentWallet->walletID = $this->generateUniqueWalletID(); // Implement this function
            $agentWallet->balance = 0;
            $agentWallet->pinNumber = 0;
            $agentWallet->agentID = $agentID;
            $agentWallet->save();
        }

        $walletID = $agentWallet->walletID;
        $walletBalance = $agentWallet->balance;

        // Retrieve agent's financial transactions
        $agentTransactions = WalletTransaction::where('walletID', $walletID)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('agent/walletIndex', compact('walletID', 'walletBalance', 'agentTransactions'));
    }


    public function request(string $id)
    {

        // Assuming you have relationships set up in your Property model
        $propertyRental = PropertyRental::find($id);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }

        // Assuming you have a relationship set up between PropertyRental and Agent
        $agentName = $propertyRental->property->agent->agentName;

        // Create the notification content
        $notificationContent = 'Request for payment release RM' . $propertyRental->payment->paymentAmount . ' for #' . $propertyRental->propertyRentalID . ' ' . $propertyRental->property->propertyName .
            ' sucessfully submit to admin.';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Request for Payment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $propertyRental->property->agent->agentID;

        // Save  notification
        $notification->save();

        // Send email to the admin
        $adminEmail = "larahon3@gmail.com"; // Replace with the actual field holding the agent's email
        $header = 'Request for Payment Release';
        $emailContent = 'Payment Release Request: Agent ' . $agentName . ' had requests the release of the payment for the property with ID ' .
            $propertyRental->propertyID . ' for amount of' . $propertyRental->payment->paymentAmount . '.';
        $note = 'Please review and process accordingly.';
        $desc = 'You are receiving this email as a notification for the payment release request related to an agent.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($adminEmail) {
                $message->to($adminEmail);
                $message->subject('Notification: Request for Payment Release');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }
        return redirect()->back()->with('success', 'Fund requested submit to admin successful.');
    }


    public function walletPayment(Request $request)
    {
        $agentID = optional(session('agent'))->agentID;
        $agent = Agent::find($agentID);

        // Retrieve agent's balance

        $walletBalance = Wallet::where('agentID', $agentID)->value('balance');

        // Retrieve activeProperty posting with availability equal to 1
        $activeProperty = $agent->properties()->where('propertyAvailability', 1)->get();

        return view('agent/walletPayment', compact('walletBalance', 'activeProperty'));
    }

    /**
     * Handle the process of making a payment for a rental posting.
     */
    public function payment(Request $request)
    {
        // Validate the form data
        $request->validate([
            'property' => 'required|array',
            'duration' => 'required|array',
            'amount' => 'required|numeric',
        ]);

        $agentID = optional(session('agent'))->agentID;
        $agent = Agent::find($agentID);
        // Retrieve the agent's wallet
        $agentWallet = $agent->wallet;

        // Deduct the posting fee from the agent's wallet balance
        $deductAmount = $request->input('amount');

        if ($agentWallet->balance < $deductAmount) {
            // Handle insufficient balance
            return redirect()->route('topUpMoney')->with('error', 'Insufficient balance. Please Top Up Wallet');
        } else {
            $agentWallet->balance -= $deductAmount;
            $agentWallet->save();
        }

        // Update the property expiration date based on the chosen duration
        foreach ($request->input('property') as $propertyID) {
            $property = Property::find($propertyID);

            if ($property) {
                $duration = $request->input('duration')[$propertyID];
                // Retrieve the current expiredDate from the database
                $currentExpirationDate = $property->expiredDate;

                // Calculate the new expiration date by adding days
                $newExpirationDate = now()->parse($currentExpirationDate)->addDays($duration);


                // Update the property expiration date
                $property->expiredDate = $newExpirationDate;
                $property->save();

                // Create the notification content
                $notificationContent = 'Pay Posting Fee for ' . $property->propertyID . ' ' . $property->propertyName .
                    ' to extend posting for ' . $duration . ' days until ' . $newExpirationDate->format('Y-m-d') . '.';

                // Create a notification record
                $notification = new Notification();
                $notification->notificationID = $this->generateUniqueNotificationID();
                $notification->subject = 'Posting Fee';
                $notification->status = 'Unread';
                $notification->content = $notificationContent;
                $notification->timestamp = now();
                $notification->userID = $agentID;

                // Save both walletTransaction and notification

                $notification->save();
            }

            // Create the notification content
            $notificationContent = 'Wallet had been deducted RM' . $deductAmount .
                ' for property posting.';

            // Create a notification record
            $notification = new Notification();
            $notification->notificationID = $this->generateUniqueNotificationID();
            $notification->subject = 'Wallet';
            $notification->status = 'Unread';
            $notification->content = $notificationContent;
            $notification->timestamp = now();
            $notification->userID = $agentID;

            // Save both walletTransaction and notification

            $notification->save();
        }

        // Create a walletTransaction record
        $walletTransaction = new WalletTransaction;
        $walletTransaction->transactionID = $this->generateUniqueTransactionID(); // Implement this function
        $walletTransaction->transactionType = 'Payment';
        $walletTransaction->transactionDate = now()->toDateString();
        $walletTransaction->transactionTime = now()->toTimeString();
        $walletTransaction->transactionAmount = $deductAmount;
        $walletTransaction->walletID = $agentWallet->walletID;
        $walletTransaction->save();

        // Send email to the agent
        $agentEmail = $agent->agentEmail; // Replace with the actual field holding the agent's email
        $header = 'Notification: Pay Posting Fee';
        $emailContent = 'Payment Confirmation: We had received your posting Fee for RM' . $deductAmount .
            ' on ' . now() . '.';
        $note = 'Thank you for choosing RentSpace :>';
        $desc = 'You are receiving this email as a notification for the posting fee related to your property.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Notification: Pay Posting Fee');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }
        // Return the confirmation message
        return redirect()->route('agentWallet')
            ->with('success', 'Payment successful.');
    }

    public function walletTopUp(Request $request)
    {
        $agentID = optional(session('agent'))->agentID;
        // Retrieve agent's balance
        $agentWallet = Agent::find($agentID)->wallet;
        if (!$agentWallet) {
            // Create a walle record
            $agentWallet = new Wallet;
            $agentWallet->walletID = $this->generateUniqueWalletID(); // Implement this function
            $agentWallet->balance = 0;
            $agentWallet->pinNumber = 0;
            $agentWallet->agentID = $agentID;
            $agentWallet->save();
        }
        return view('agent/walletTopUp', compact('agentWallet'));
    }




    public function walletWithdraw(Request $request)
    {
        $agentID = optional(session('agent'))->agentID;
        // Retrieve agent's balance
        $agentBalance = Wallet::where('agentID', $agentID)->value('balance');
        return view('agent/walletWithdraw', compact('agentBalance'));
    }

    /**
     * Handle the process of withdrawing money to a bank.
     */

    public function withdraw(Request $request)
    {
  

        $withdrawAmountSelect = $request->input('withdrawAmountSelect');
        $customWithdrawAmount = $request->input('customWithdrawAmount');
        
        // Determine the final withdrawal amount
        $amount = $withdrawAmountSelect ?? $customWithdrawAmount;
      
        $bank = $request->input('bank');
        $accountNumber = $request->input('accountNumber');

        $agentID = optional(session('agent'))->agentID;
        $agent = Agent::find($agentID);
        // // Retrieve agent's wallet
        $agentWallet = $agent->wallet;
        if ($agentWallet->balance >= $amount) {
            // Subtract the withdrawn amount from the wallet's balance
            $agentWallet->balance -= $amount;
            $agentWallet->save();
        } else {
            return redirect()->route('agentWallet')->with('error', ' Your wallet amount is insufficent for withdrawal');
        }
        // Create a walletTransaction record
        $walletTransaction = new WalletTransaction;
        $walletTransaction->transactionID = $this->generateUniqueTransactionID(); // Implement this function
        $walletTransaction->transactionType = 'Withdrawal';
        $walletTransaction->transactionDate = now()->toDateString();
        $walletTransaction->transactionTime = now()->toTimeString();
        $walletTransaction->transactionAmount = $amount;
        $walletTransaction->walletID = $agentWallet->walletID;

        // Create the notification content
        $notificationContent = 'Withdraw RM ' . number_format($amount, 2) .
            ' to ' . $bank . ' (' . $accountNumber . ')';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Wallet';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $agentID;

        // Save both walletTransaction and notification
        $walletTransaction->save();
        $notification->save();

        // Send email to the agent
        $agentEmail = $agent->agentEmail; // Replace with the actual field holding the agent's email
        $header = 'Notification: Wallet Withdrawal';
        $emailContent = 'Withdraw RM ' . number_format($amount, 2) .
            ' to ' . $bank . ' (' . $accountNumber . '). Please log in to your account to review the transaction details.';
        $note = 'If you have not initiated this withdrawal, please contact customer support immediately.';
        $desc = 'You are receiving this email as a notification for a wallet withdrawal from your account.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Notification: Wallet Withdrawal');
            });
        } catch (TransportExceptionInterface $e) {
       
            // Handle the exception or log the error
        }
        // Redirect to a success page or handle the response as needed
        return redirect()->route('agentWallet')->with('success', 'Withdrawal RM' . $amount . ' successful.');
    }

    public function walletPending(Request $request)
    {
        $agentID = optional(session('agent'))->agentID;
        //Retrieve the agent's wallet
        $agent = Agent::find($agentID);

        // Retrieve all property rentals with rentStatus "Paid" associated with the agent
        $pendingRentals = PropertyRental::where('rentStatus', 'Paid')
            ->whereIn('propertyID', $agent->properties->pluck('propertyID')) // Assuming 'id' is the primary key in Property
            ->get();
        return view('agent/walletPending', compact('pendingRentals', 'agent'));
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
    public function generateUniqueTransactionID()
    {
        $latestTransaction = WalletTransaction::orderBy('transactionID', 'desc')->first();

        if ($latestTransaction) {
            $lastID = ltrim(substr($latestTransaction->transactionID, 3), '0'); // Remove the "WTR" prefix and leading zeros
            $nextID = 'WTR' . str_pad($lastID + 1, 7, '0', STR_PAD_LEFT); // Increment and pad to 7 digits
        } else {
            $nextID = 'WTR0000001'; // Initial ID
        }

        return $nextID;
    }

    public function generateUniqueNotificationID()
    {
        $latestNotification = Notification::orderBy('notificationID', 'desc')->first();

        if ($latestNotification) {
            $lastID = ltrim(substr($latestNotification->notificationID, 3), '0'); // Remove the "NOT" prefix and leading zeros
            $nextID = 'NOT' . str_pad($lastID + 1, 7, '0', STR_PAD_LEFT); // Increment and pad to 7 digits
        } else {
            $nextID = 'NOT0000001'; // Initial ID
        }

        return $nextID;
    }

    function calculateSubscriptionPrice($isPremium, $numberOfActiveListings)
    {
        $standardPrice = 10.00;
        $premiumDiscount = 0.85; // 15% discount for premium subscription

        if ($isPremium) {
            return $standardPrice * $premiumDiscount;
        } elseif ($numberOfActiveListings >= 10) {
            return $standardPrice * $premiumDiscount; // Auto-upgrade to premium with a 15% discount
        } else {
            return $standardPrice; // Standard price for a regular subscription
        }
    }
}