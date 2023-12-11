<?php

namespace App\Http\Controllers;

use App\Models\PropertyRental;
use App\Models\Refund;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class RefundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $refunds = Refund::all();
        return view('admin/refundIndex', compact('refunds'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $id)
    {
        $propertyRental = PropertyRental::find($id);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }
        return view('tenant/refundCreate', compact('propertyRental'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $propertyRentalID = $request->propertyRentalID;
        // Assuming you have relationships set up in your Property model
        $propertyRental = PropertyRental::find($propertyRentalID);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }


      

        $refund = new Refund();
        $refund->refundID = $this->generateUniqueRefundID();
        $refund->refundDate = now();
        $refund->refundReason = $request->reason;
        $refund->refundAmount = $propertyRental->payment->paymentAmount;
        $refund->refundStatus = "Pending";
        $refund->propertyRentalID = $propertyRentalID;
// Save uploaded photos
if ($request->hasFile('photoUpload')) {
    // Store the uploaded file
    $photoPath = $request->file('photoUpload')->store('photos', 'public');
    $refund->refundPhoto = $photoPath; 
}
        $refund->save();
        $propertyRental->rentStatus = "Refund requested";
        $propertyRental->save();

        // Create the notification content
        $notificationContent = 'Refund requested for ' . $propertyRentalID .
            ' on ' . $refund->refundDate;

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Refund';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $propertyRental->tenant->tenantID;

        // Save  notification
        $notification->save();

        // Send email to the tenant
        $tenantEmail = $propertyRental->tenant->tenantEmail;
        $header = 'Notification: Refund Request';
        $emailContent = 'Your refund request for your rental application #' . $propertyRentalID . ' with ' . $propertyRental->property->propertyName .
            ' on ' . $refund->refundDate . ' has been submitted to RentSpace administrator. Please wait for further updates.';
        $note = 'If you have any questions or concerns, feel free to contact us.';
        $desc = 'You are receiving this email as a notification for a refund request related to your rental application.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Notification: Refund Request');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }


        // Create the notification content
        $notificationContent = 'Refund requested for ' . $propertyRentalID .' with ' . $propertyRental->property->propertyName .
            ' on ' . $refund->refundDate.'.';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Refund';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $propertyRental->property->agentID;

        // Save the notification
        $notification->save();
        $emailContent = 'A refund has been requested for your rental application #' . $propertyRentalID . ' with ' . $propertyRental->property->propertyName .
            'by ' . $propertyRental->tenant->tenantName . ' on ' . $refund->refundDate . '. Please wait for further updates.';
        // Send email to the agent
        $agentEmail = $propertyRental->property->agent->agentEmail;
        $header = 'Notification: Refund Request';
        $emailContent = 'Refund requested for rental application #' . $propertyRentalID .
            ' on ' . $refund->refundDate;
        $note = 'Please review and process the refund request.';
        $desc = 'You are receiving this email as a notification for a refund request related to a rental application.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Notification: Refund Request');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }


        return redirect()->route('applicationIndex')->with('success', 'Refund request submitted to admin successful.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $refund = Refund::find($id);

        if (!$refund) {
            // Handle Refund not found, redirect or show an error view
            abort(404, 'Refund not found');
        }

        return view('admin/refundProcess', compact('refund'));
    }

    public function approve(Request $request)
    {
        $refund = Refund::find($request->refundID);
       

        if (!$refund) {
            // Handle Refund not found, redirect or show an error view
            abort(404, 'Refund not found');
        }

        $refund->refundStatus = 'Approved';
        $refund->approvalDate = now();
        $refund->save();

        $propertyRental = $refund->propertyRental;
        $propertyRental->rentStatus = "Refund approved";
        $propertyRental->save();

        $property = $propertyRental->property;
        $property->propertyAvailability = 1;
        $property->save();

        // Create the notification content
        $notificationContent = 'Refund approved for ' . $propertyRental->propertyRentalID . ' with ' . $propertyRental->property->propertyName .
            ' on ' . $refund->approvalDate.'.';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Refund';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $propertyRental->tenant->tenantID;

        // Save  notification
        $notification->save();

        // Send email to the tenant
        $tenantEmail = $propertyRental->tenant->tenantEmail;
        $header = 'Notification: Refund Approved';
        $emailContent = 'Your refund for the rental application #' . $propertyRental->propertyRentalID . ' with ' . $propertyRental->property->propertyName .
            ' has been approved on ' . $refund->approvalDate . '. The refunded amount will be processed soon.';
        $note = 'If you have any questions or concerns, feel free to contact us.';
        $desc = 'You are receiving this email as a notification for the approval of your refund request related to your rental application.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Notification: Refund Approved');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
        }

        // Create the notification content
        $notificationContent = 'Refund requested by '.$propertyRental->tenant->tenantName.' approved by admin for ' . $propertyRental->propertyRentalID . ' with ' . $propertyRental->property->propertyName .
            ' on ' . $refund->approvalDate;

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Refund';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $propertyRental->property->agentID; // Assuming you have the agent's ID

        // Save notification
        $notification->save();

        // Send email to the agent
        $agentEmail = $propertyRental->property->agent->agentEmail;
        $header = 'Notification: Refund Approved';
        $emailContent = 'Refund requested by'.$propertyRental->tenant->tenantName. 'for the rental application #' . $propertyRental->propertyRentalID . ' with ' . $propertyRental->property->propertyName .
            ' has been approved by admin on ' . $refund->approvalDate . '. The refund reason is '.$refund->refundReason.'.';
        $note = 'If you have any questions or concerns, feel free to contact us.';
        $desc = 'You are receiving this email as a notification for the approval of the refund request related to the rental application.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Notification: Refund Approved');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
        }


        return redirect()->route('refunds.index')->with('success', 'Refund approved.');

    }

    public function reject(Request $request)
    {
        $refund = Refund::find($request->refundID);

        if (!$refund) {
            // Handle Refund not found, redirect or show an error view
            abort(404, 'Refund not found');
        }

        $refund->refundStatus = 'Rejected';
        $refund->rejectReason = $request->rejectReason;
        $refund->save();

        $propertyRental = $refund->propertyRental;
        $propertyRental->rentStatus = "Refund rejected";
        $propertyRental->save();

        // Create the notification content
        $notificationContent = 'Refund rejected for ' . $propertyRental->propertyRentalID .'' . $propertyRental->property->propertyName .
            ' because ' . $refund->rejectReason.'.';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Refund';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $propertyRental->tenant->tenantID;

        // Save  notification
        $notification->save();

        // Send email to the tenant
        $tenantEmail = $propertyRental->tenant->tenantEmail;
        $header = 'Notification: Refund Rejected';
        $emailContent = 'Your refund request for the rental application #' . $propertyRental->propertyRentalID .
            ' has been rejected because ' . $refund->rejectReason . '. If you have any questions or concerns, please contact us.';
        $note = 'You can log in to your account for more details.';
        $desc = 'You are receiving this email as a notification for the rejection of your refund request related to your rental application.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Notification: Refund Rejected');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        return redirect()->route('refunds.index')->with('success', 'Refund rejected.');

    }

    public function generateUniqueRefundID()
    {
        $latestRefund = Refund::orderBy('refundID', 'desc')->first();

        if ($latestRefund) {
            $lastID = ltrim(substr($latestRefund->refundID, 3), '0'); // Remove the "RFD" prefix and leading zeros
            $nextID = 'RFD' . str_pad($lastID + 1, 7, '0', STR_PAD_LEFT); // Increment and pad to 7 digits
        } else {
            $nextID = 'RFD0000001'; // Initial ID
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
