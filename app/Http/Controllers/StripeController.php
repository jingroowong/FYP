<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Agent;
use App\Models\Payment;
use App\Models\PropertyRental;
use App\Models\Notification;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class StripeController extends Controller
{

    public function session(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));

        $productname = $request->get('productname');
        $totalprice = $request->get('topUpAmount');
        $two0 = "00";
        $total = "$totalprice$two0";

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'MYR',
                        'product_data' => [
                            "name" => $productname,
                        ],
                        'unit_amount' => $total,
                    ],
                    'quantity' => 1,
                ],

            ],
            'mode' => 'payment',
            'success_url' => route('success', ['topUpAmount' => $totalprice]),
            'cancel_url' => route('topUpMoney'),
        ]);

        return redirect()->away($session->url);
    }

    public function success(Request $request)
    {
        $agentID = optional(session('agent'))->agentID;

        $agent = Agent::find($agentID);
        //Retrieve the agent's wallet
        $agentWallet = $agent->wallet;
        // Deduct the posting fee from the agent's wallet balance

        $topUpAmount = $request->get('topUpAmount');

        $agentWallet->balance += $topUpAmount;
        $agentWallet->save();

        // Create a walletTransaction record
        $walletTransaction = new WalletTransaction;
        $walletTransaction->transactionID = $this->generateUniqueTransactionID(); // Implement this function
        $walletTransaction->transactionType = 'Top Up';
        $walletTransaction->transactionDate = now()->toDateString();
        $walletTransaction->transactionTime = now()->toTimeString();
        $walletTransaction->transactionAmount = $topUpAmount;
        $walletTransaction->walletID = $agentWallet->walletID;

        // Create the notification content
        $notificationContent = 'Top Up RM ' . number_format($topUpAmount, 2) .
            ' on ' . $walletTransaction->transactionDate;

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
        $header = 'Notification: Wallet Top-Up';
        $emailContent = 'Top Up RM ' . number_format($topUpAmount, 2) .
            ' on ' . $walletTransaction->transactionDate . '. Thank you for using our wallet services.';
        $note = 'If you have not initiated this top-up, please contact customer support immediately.';
        $desc = 'You are receiving this email as a notification for a wallet top-up to your account.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Notification: Wallet Top-Up');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        return redirect()->route('agentWallet')
            ->with('success', 'Top Up RM ' . $topUpAmount . ' successful.');
    }

    public function sessionTenant(Request $request)
    {
        \Stripe\Stripe::setApiKey(config('stripe.sk'));
        // Get the selected rental start date option from the form
        $rentalStartDateOption = $request->get('rentalStartDate');

        $propertyRentalID = $request->get('propertyRentalID');
        $totalprice = $request->get('amount');
        $propertyName = $request->get('propertyName');
        $description = '#' . $propertyRentalID . '(' . $propertyName . ')';
        $two0 = "00";
        $total = "$totalprice$two0";

        $session = \Stripe\Checkout\Session::create([
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'MYR',
                        'product_data' => [
                            "name" => $description,
                        ],
                        'unit_amount' => $total,
                    ],
                    'quantity' => 1,
                ],

            ],
            'mode' => 'payment',
            'success_url' => route('successTenant', ['propertyRentalID' => $propertyRentalID, 'rentalStartDateOption' => $rentalStartDateOption]),
            'cancel_url' => route('paymentHistory'),
        ]);

        
        return redirect()->away($session->url);
    }

    public function successTenant(Request $request)
    {
        $id = $request->get('propertyRentalID');
        // Assuming you have relationships set up in your Property model
        $propertyRental = PropertyRental::find($id);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }
      
        $rentalStartDateOption = $request->get('rentalStartDateOption');
        $effectiveStartDate = ($rentalStartDateOption == 'next_month')
            ? now()->firstOfMonth()->addMonth()->toDateString()
            : now()->toDateString();
           
        $payment = new Payment();
        $payment->paymentID = $this->generateUniquePaymentID();
        $payment->paymentMethod = "Credit Card";
        $payment->paymentDate = now();
        $payment->paymentTime = now();
        $paymentAmount = $propertyRental->property->rentalAmount + $propertyRental->property->depositAmount;
        $payment->paymentAmount = $paymentAmount;
        $payment->save();

        $propertyRental->paymentID = $payment->paymentID;
        $propertyRental->rentStatus = "Paid";
        $propertyRental->effectiveDate = $effectiveStartDate;
        $propertyRental->save();

        $property = $propertyRental->property;
        $property->propertyAvailability = 0;
        $property->save();
        
        // Create the notification content
        $notificationContent = $propertyRental->tenant->tenantName . ' paid deposit RM' . $paymentAmount . ' for ' . $propertyRental->property->propertyName . '. ';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Property';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $propertyRental->property->agent->agentID;

        // Save  notification
        $notification->save();

        // Create the notification content
        $notificationContent = 'Paid deposit RM' . $paymentAmount . ' for ' . $propertyRental->property->propertyName . ' to ' . $propertyRental->property->agent->agentName . '.';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Property';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $propertyRental->tenant->tenantID;
       
        // Save  notification
        $notification->save();
        $propertyRental = PropertyRental::find($id);

          // Generate a success message for the tenant
    $successMessage = 'Congratulations! Your payment was successful. Your rental is protected, and you are eligible for a refund in the event of a scam. The advanced rental and security deposit will be held for 14 days to ensure the property is in good condition and meets your satisfaction.';

        return view('tenant/paymentReceipt', compact('propertyRental','successMessage'));
    }

    public function generateUniquePaymentID()
    {
        $latestPayment = Payment::orderBy('paymentID', 'desc')->first();

        if ($latestPayment) {
            $lastID = ltrim(substr($latestPayment->paymentID, 3), '0'); // Remove the "PAY" prefix and leading zeros
            $nextID = 'PAY' . str_pad($lastID + 1, 7, '0', STR_PAD_LEFT); // Increment and pad to 7 digits
        } else {
            $nextID = 'PAY0000001'; // Initial ID
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


}
