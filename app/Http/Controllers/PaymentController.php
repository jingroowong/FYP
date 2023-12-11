<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PropertyRental;
use App\Models\Payment;
use App\Models\Tenant;
use App\Models\Notification;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenantID = optional(session('tenant'))->tenantID;
        $tenant = Tenant::find($tenantID);

        if (!$tenant) {
            // Handle tenant not found, redirect or show an error view
            abort(404, 'Tenant not found');
        }

        // Retrieve property rentals for the tenant with rentStatus 'paid'
        $propertyRentals = $tenant->propertyRentals()->where('rentStatus', 'paid')->get();

        return view('paymentHistoryIndex', compact('propertyRentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        // Assuming you have relationships set up in your Property model
        $propertyRental = PropertyRental::find($id);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }

        return view('tenant/paymentCreate', compact('propertyRental'));

    }
    public function paymentReceipt(string $id)
    {
        // Assuming you have relationships set up in your Property model
        $propertyRental = PropertyRental::find($id);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }

        $successMessage=null;
        return view('tenant/paymentReceipt', compact('propertyRental','successMessage'));
    }

    public function paymentReceiptAdmin(string $id)
    {
        // Assuming you have relationships set up in your Property model
        $propertyRental = PropertyRental::find($id);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }

        $successMessage=null;
        return view('admin/paymentReceipt', compact('propertyRental','successMessage'));
    }

    public function releaseAdmin(string $id)
    {
        // Assuming you have relationships set up in your Property model
        $propertyRental = PropertyRental::find($id);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }

        // // Retrieve agent's wallet
        $agentWallet = $propertyRental->property->agent->wallet;

        $agentWallet->balance += $propertyRental->payment->paymentAmount;
        $agentWallet->save();

        // Create a walletTransaction record
        $walletTransaction = new WalletTransaction;
        $walletTransaction->transactionID = $this->generateUniqueTransactionID(); // Implement this function
        $walletTransaction->transactionType = 'Fund Release';
        $walletTransaction->transactionDate = now()->toDateString();
        $walletTransaction->transactionTime = now()->toTimeString();
        $walletTransaction->transactionAmount = $propertyRental->payment->paymentAmount;
        $walletTransaction->walletID = $agentWallet->walletID;
        $walletTransaction->save();


       // Create the notification content
        $notificationContent = 'Fund Release RM' . $walletTransaction->transactionAmount . ' to ' . $propertyRental->property->agent->agentName .
            '.';

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

        // Create the notification content
        $notificationContent = 'Fund Release RM' . $walletTransaction->transactionAmount . ' for #' . $propertyRental->propertyRentalID .' '. $propertyRental->property->propertyName .
            '.';

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

        // Notify Tenant
        $headerTenant = 'Fund Release Notification';
        $emailContentTenant = 'RentSpace had released RM' . $walletTransaction->transactionAmount . ' for your property rental ' . $propertyRental->propertyRentalID .'for '.$propertyRental->property->propertyName.' to the agent .';

        $noteTenant = "If you have any concerns or need further assistance, please contact the property manager.";
        $descTenant = 'You are receiving this email to inform you about the fund release for your property rental.';

        $tenantEmail = $propertyRental->tenant->tenantEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContentTenant, 'header' => $headerTenant, 'desc' => $descTenant, 'note' => $noteTenant], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Fund Release Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        // Notify Agent
        $headerAgent = 'Fund Release Notification';
        $emailContentAgent = 'RM' . $walletTransaction->transactionAmount . ' has been released from the tenant for property rental ' . $propertyRental->propertyRentalID .'for '.$propertyRental->property->propertyName.' .';

        $noteAgent = "If you have any concerns or need further assistance, please contact the tenant.";
        $descAgent = 'You are receiving this email to inform you about the fund release for the property rental.';

        $agentEmail = $propertyRental->property->agent->agentEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContentAgent, 'header' => $headerAgent, 'desc' => $descAgent, 'note' => $noteAgent], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Fund Release Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        $propertyRental->rentStatus = "Completed";
        $propertyRental->property->propertyAvailability = 0;
        $propertyRental->save();

        $property = $propertyRental->property;
        $property->propertyAvailability = 0;
        $property->save();
        return redirect()->back()->with('success', 'Fund released to agent successful.');
    }
    public function release(string $id)
    {
        // Assuming you have relationships set up in your Property model
        $propertyRental = PropertyRental::find($id);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }

        // // Retrieve agent's wallet
        $agentWallet = $propertyRental->property->agent->wallet;

        $agentWallet->balance += $propertyRental->payment->paymentAmount;
        $agentWallet->save();

        // Create a walletTransaction record
        $walletTransaction = new WalletTransaction;
        $walletTransaction->transactionID = $this->generateUniqueTransactionID(); // Implement this function
        $walletTransaction->transactionType = 'Fund Release';
        $walletTransaction->transactionDate = now()->toDateString();
        $walletTransaction->transactionTime = now()->toTimeString();
        $walletTransaction->transactionAmount = $propertyRental->payment->paymentAmount;
        $walletTransaction->walletID = $agentWallet->walletID;
        $walletTransaction->save();


        // Create the notification content
        $notificationContent = 'Fund Release RM' . $walletTransaction->transactionAmount . ' to ' . $propertyRental->property->agent->agentName .
            '.';

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

        // Create the notification content
        $notificationContent = 'Fund Release RM' . $walletTransaction->transactionAmount . ' for #' . $propertyRental->propertyRentalID .' '. $propertyRental->property->propertyName .
            '.';

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

        // Notify Tenant
        $headerTenant = 'Fund Release Notification';
        $emailContentTenant = 'You had released RM' . $walletTransaction->transactionAmount . ' for your property rental ' . $propertyRental->propertyRentalID .'for '.$propertyRental->property->propertyName.' to the agent .';

        $noteTenant = "If you have any concerns or need further assistance, please contact the property manager.";
        $descTenant = 'You are receiving this email to inform you about the fund release for your property rental.';

        $tenantEmail = $propertyRental->tenant->tenantEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContentTenant, 'header' => $headerTenant, 'desc' => $descTenant, 'note' => $noteTenant], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Fund Release Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        // Notify Agent
        $headerAgent = 'Fund Release Notification';
        $emailContentAgent = 'RM' . $walletTransaction->transactionAmount . ' has been released from the tenant for property rental ' . $propertyRental->propertyRentalID .'for '.$propertyRental->property->propertyName.' .';

        $noteAgent = "If you have any concerns or need further assistance, please contact the tenant.";
        $descAgent = 'You are receiving this email to inform you about the fund release for the property rental.';

        $agentEmail = $propertyRental->property->agent->agentEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContentAgent, 'header' => $headerAgent, 'desc' => $descAgent, 'note' => $noteAgent], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Fund Release Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        $propertyRental->rentStatus = "Completed";
        $propertyRental->property->propertyAvailability = 0;
        $propertyRental->save();

        $property = $propertyRental->property;
        $property->propertyAvailability = 0;
        $property->save();
        return redirect()->route('applicationIndex')->with('success', 'Fund released to agent successful.');
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
