<?php

namespace App\Http\Controllers;

use App\Models\Timeslot;
use App\Models\Property;
use App\Models\Tenant;
use App\Models\Notification;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Carbon\Carbon;
use App\Jobs\SendOneHourBeforeReminderEmail;
use App\Jobs\SendSameDayReminderEmail;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenantID = optional(session('tenant'))->tenantID;

        $appointments = Appointment::where('tenantID', $tenantID)->paginate(10);

        foreach ($appointments as $appointment) {
            // Get the date and start time of the appointment
            $appointmentDate = Carbon::parse($appointment->timeslot->date);
            $appointmentStartTime = Carbon::parse($appointment->timeslot->startTime);

            // Get the current date and time
            $now = Carbon::now();

            // Check if the appointment date and start time have already passed
            if ($now->gte($appointmentDate) && $now->gte($appointmentStartTime) && $appointment->status == 'Pending') {
                // Update the appointment status to completed
                $appointment->status = 'Completed';
                $appointment->save();
            }
        }

        return view('tenant/appointmentIndex', compact('appointments'));
    }

    public function agentIndex()
    {
        $agentID = optional(session('agent'))->agentID;
        // Retrieve appointments for the agent along with related property information
        $appointments = Appointment::whereHas('property', function ($query) use ($agentID) {
            $query->where('agentID', $agentID);
        })->paginate(5);

        foreach ($appointments as $appointment) {
            // Get the date and start time of the appointment
            $appointmentDate = Carbon::parse($appointment->timeslot->date);
            $appointmentStartTime = Carbon::parse($appointment->timeslot->startTime);

            // Get the current date and time
            $now = Carbon::now();

            // Check if the appointment date and start time have already passed
            if ($now->gte($appointmentDate) && $now->gte($appointmentStartTime) && $appointment->status == 'Pending') {
                // Update the appointment status to completed
                $appointment->status = 'Completed';
                $appointment->save();
            }
        }
        return view('agent/appointmentIndex', compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($propertyID)
    {
        $tenantID = optional(session('tenant'))->tenantID;
        $tenant = Tenant::find($tenantID);
        // Retrieve the property details based on $propertyID

        $property = Property::with(['propertyPhotos'])->findOrFail($propertyID);


        // Retrieve the available timeslots
        $currentDateTime = Carbon::now();

        $availableTimeslots = Timeslot::where('agentID', $property->agentID)
        ->whereNotIn('timeslotID', function ($query) use ($propertyID, $currentDateTime) {
            $query->select('timeslotID')
                ->from('appointments')
                ->where('propertyID', $propertyID)
                ->where('status', '!=', 'Pending') // Exclude all statuses other than Pending and Cancelled
                ->orWhere('status', '!=', 'Cancelled');
        })
        ->where('date', '>=', $currentDateTime->toDateString())
        ->orWhere(function ($query) use ($currentDateTime) {
            $query->where('date', '=', $currentDateTime->toDateString())
                ->where('startTime', '>', $currentDateTime->toTimeString());
        })
        ->orderBy('date', 'asc')
        ->orderBy('startTime', 'asc')
        ->get();
    

        // Extract distinct dates from timeslots
        $availableDates = $availableTimeslots->pluck('date')->unique()->values()->all();

        return view('tenant/appointmentCreate', compact('property', 'availableTimeslots', 'availableDates', 'tenant'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'timeslot' => 'required', // Ensure the selected timeslot exists
            'name' => 'required|string',
            'email' => 'required|email',
            'contact_number' => 'required|string',
            'num_of_viewers' => 'required|integer|min:1',
            'message' => 'nullable|string',
        ]);


        $tenantID = optional(session('tenant'))->tenantID;
        // Create a new appointment
        $appointment = new Appointment();
        $appointment->appID = $this->generateUniqueAppointmentID();
        $appointment->timeslotID = $request->timeslotID;
        $appointment->tenantID = $tenantID;
        $appointment->status = "Pending";
        $appointment->propertyID = $request->propertyID;
        $appointment->name = $request->name;
        $appointment->email = $request->email;
        $appointment->contactNo = $request->contact_number;
        $appointment->headcount = $request->num_of_viewers;
        $appointment->message = $request->message;

        $appointment->save();

        // You may want to send a confirmation email, etc.
        // Create the notification content
        $notificationContent = 'Appointment Booked for #' . $appointment->property->propertyID . $appointment->property->propertyName .
            ' on ' . $appointment->timeslot->date .' ('. $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime .') successful! ';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Appointment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $appointment->tenant->tenantID;

        // Save  notification
        $notification->save();

        $header = 'Appointment Confirmation';
        $emailContent = 'Appointment Booked with ' . $appointment->property->agent->agentName . ' for  #' . $appointment->property->propertyID . ' ' . $appointment->property->propertyName . ' ' .
            ' on ' . $appointment->timeslot->date .' ('. $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime .') successful! ';
        $note = "If you didn't able to attend the appointment, please login to your account to modify the appointment time.";
        $desc = 'You are receiving this email because your RentSpace Account have an upcoming appointment.';

        $tenantEmail = $appointment->tenant->tenantEmail;
        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Appointment Booked.');
            });


        } catch (TransportExceptionInterface $e) {
            dd($e);
            return back()->withErrors(['error' => 'Sorry, there was an error sending the notification. Please try again later.']);
        }


        // Create the notification content
        $notificationContent = 'Tenant has made appointment on your #' . $appointment->property->propertyID .' '. $appointment->property->propertyName .
            ' on ' . $appointment->timeslot->date.' ('. $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime .') successful! ';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Appointment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $appointment->property->agent->agentID;
        // Save  notification
        $notification->save();


        $header = 'Appointment Confirmation';
        $emailContent = 'Tenant [' . $appointment->tenant->tenantName . '] has made appointment on your #' . $appointment->property->propertyID . ' ' . $appointment->property->propertyName . ' ' .
            ' on ' . $appointment->timeslot->date .' ('. $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime .') . ';
        $note = "If you didn't able to attend the appointment, please login to your account to modify the appointment time.";
        $desc = 'You are receiving this email because your RentSpace Account have an upcoming appointment.';

        $agentEmail = $appointment->property->agent->agentEmail;


        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Appointment Booked.');
            });


        } catch (TransportExceptionInterface $e) {
            dd($e);
            return back()->withErrors(['error' => 'Sorry, there was an error sending the notification. Please try again later.']);
        }

        return redirect()->route('appointments')
            ->with('success', 'Appointment ' . $appointment->appID . ' booked successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($id);

        // Check if the appointment exists
        if (!$appointment) {
            abort(404, 'Appointment not found');
        }

        // Pass the appointment data to the view
        return view('agent/appointmentDelete', compact('appointment'));
    }

    public function showTenant($id)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($id);

        // Check if the appointment exists
        if (!$appointment) {
            abort(404, 'Appointment not found');
        }

        // Pass the appointment data to the view
        return view('tenant/appointmentDelete', compact('appointment'));
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($id);

        // Check if the appointment exists
        if (!$appointment) {
            abort(404, 'Appointment not found');
        }

        $currentDateTime = Carbon::now();
        $propertyID = $appointment->propertyID;

        $availableTimeslots = Timeslot::where('agentID', $appointment->property->agentID)
            ->where(function ($query) use ($currentDateTime, $propertyID) {
                $query->where('date', '>', $currentDateTime->toDateString())
                    ->orWhere(function ($query) use ($currentDateTime, $propertyID) {
                        $query->where('date', '=', $currentDateTime->toDateString())
                            ->where('startTime', '>', $currentDateTime->toTimeString());
                    });
            })
            ->whereNotIn('timeslotID', function ($query) use ($currentDateTime, $propertyID, $appointment) {
                $query->select('timeslotID')
                    ->from('appointments')
                    ->join('properties', 'appointments.propertyID', '=', 'properties.propertyID')
                    ->where('properties.agentID', $appointment->property->agentID)
                    ->where('appointments.status', '!=', 'Pending') // Exclude all statuses other than Pending and Cancelled
                ->orWhere('appointments.status', '!=', 'Cancelled')
                    ->where('appointments.propertyID', $propertyID)
                    ->where(function ($query) use ($currentDateTime) {
                        $query->where('date', '>=', $currentDateTime->toDateString())
                            ->orWhere(function ($query) use ($currentDateTime) {
                                $query->where('date', '=', $currentDateTime->toDateString())
                                    ->where('startTime', '>', $currentDateTime->toTimeString());
                            });
                    });
            })
            ->orderBy('date', 'asc')
            ->orderBy('startTime', 'asc')
            ->get();


        // Extract distinct dates from timeslots
        $availableDates = $availableTimeslots->pluck('date')->unique()->values()->all();

        // Pass the appointment data to the view
        return view('agent/appointmentUpdate', compact('appointment', 'availableTimeslots', 'availableDates'));

    }

    public function editTenant(string $id)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($id);

        // Check if the appointment exists
        if (!$appointment) {
            abort(404, 'Appointment not found');
        }

        // Retrieve the available timeslots
        $currentDateTime = Carbon::now();
        $propertyID = $appointment->property->propertyID;
        $availableTimeslots = Timeslot::where('agentID', $appointment->property->agentID)
            ->where(function ($query) use ($currentDateTime, $propertyID) {
                $query->where('date', '>', $currentDateTime->toDateString())
                    ->orWhere(function ($query) use ($currentDateTime, $propertyID) {
                        $query->where('date', '=', $currentDateTime->toDateString())
                            ->where('startTime', '>', $currentDateTime->toTimeString());
                    });
            })
            ->whereNotIn('timeslotID', function ($query) use ($currentDateTime, $propertyID, $appointment) {
                $query->select('timeslotID')
                    ->from('appointments')
                    ->join('properties', 'appointments.propertyID', '=', 'properties.propertyID')
                    ->where('properties.agentID', $appointment->property->agentID)
                    ->where('appointments.propertyID', $propertyID)
                    ->where('appointments.status', '!=', 'Pending') // Exclude all statuses other than Pending and Cancelled
                    ->orWhere('appointments.status', '!=', 'Cancelled')
                    ->where(function ($query) use ($currentDateTime) {
                        $query->where('date', '>=', $currentDateTime->toDateString())
                            ->orWhere(function ($query) use ($currentDateTime) {
                                $query->where('date', '=', $currentDateTime->toDateString())
                                    ->where('startTime', '>', $currentDateTime->toTimeString());
                            });
                    });
            })
            ->orderBy('date', 'asc')
            ->orderBy('startTime', 'asc')
            ->get();

        // Extract distinct dates from timeslots
        $availableDates = $availableTimeslots->pluck('date')->unique()->values()->all();


        // Pass the appointment data to the view
        return view('tenant/appointmentUpdate', compact('appointment', 'availableTimeslots', 'availableDates'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($request->appID);

        // Check if the appointment exists
        if (!$appointment) {
            abort(404, 'Appointment not found');
        }

        // Update appointment fields with the new values
        $appointment->timeslotID = $request->timeslotID;

        // Save the updated appointment
        $appointment->save();


        // Create the notification content
        $notificationContent = 'Appointment for ' . $appointment->property->propertyID . $appointment->property->propertyName .
            ' on ' . $appointment->timeslot->date . 'have been successfully updated to ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . '.';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Appointment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $appointment->tenant->tenantID;

        // Save  notification
        $notification->save();

        $header = 'Appointment Update Confirmation';
        $emailContent = 'Your appointment details for property viewing #' . $appointment->property->propertyID . ' (' . $appointment->property->propertyName . ')' .
            ' on ' . $appointment->timeslot->date . ' have been successfully updated to ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . '.';
        $note = "If you have any questions or need further assistance, please feel free to contact us.";
        $desc = 'You are receiving this email to confirm that the appointment details have been updated successfully.';

        $tenantEmail = $appointment->tenant->tenantEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Appointment Update Confirmation');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            return back()->withErrors(['error' => 'Sorry, there was an error sending the confirmation. Please try again later.']);
        }

        // Create the notification content
        $notificationContent = 'Tenant [' . $appointment->tenant->tenantName . '] has updated the appointment timeslot for your property viewing  #' . $appointment->property->propertyID . ' (' . $appointment->property->propertyName . ')' .
            ' on ' . $appointment->timeslot->date . ' to ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . '.';


        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Appointment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $appointment->property->agent->agentID;

        // Save  notification
        $notification->save();


        $header = 'Appointment Update Notification';
        $emailContent = 'Tenant [' . $appointment->tenant->tenantName . '] has updated the appointment timeslot for your property viewing  #' . $appointment->property->propertyID . ' (' . $appointment->property->propertyName . ')' .
            ' on ' . $appointment->timeslot->date . ' to ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . '.';
        $note = "If you need to review or manage the appointment, please log in to your account.";
        $desc = 'You are receiving this email because the appointment for your property has been updated by the tenant.';

        $agentEmail = $appointment->property->agent->agentEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Appointment Update Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            return back()->withErrors(['error' => 'Sorry, there was an error sending the notification. Please try again later.']);
        }

        // Redirect back with a success message
        return redirect()->route('appointments')->with('success', 'Appointment updated successfully');
    }


    public function updateByAgent(Request $request)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($request->appID);

        // Check if the appointment exists
        if (!$appointment) {
            abort(404, 'Appointment not found');
        }

        // Update appointment fields with the new values
        $appointment->timeslotID = $request->timeslotID;

        // Save the updated appointment
        $appointment->save();

        // Create the notification content
        $notificationContent = 'Agent [' . $appointment->property->agent->agentName . '] has updated the appointment details for your property viewing for #' . $appointment->property->propertyID . ' (' . $appointment->property->propertyName . ')' .
            ' on ' . $appointment->timeslot->date . ' to ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . '.';


        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Appointment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $appointment->tenant->tenantID;

        // Save  notification
        $notification->save();

        // Create the notification content
        $notificationContent = 'Appointment for ' . $appointment->property->propertyID . $appointment->property->propertyName .
            ' on ' . $appointment->timeslot->date . 'have been successfully updated to ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . '.';


        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Appointment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $appointment->property->agent->agentID;
        $notification->save();

        // Notify Tenant
        $headerTenant = 'Appointment Modified';

        $emailContentTenant = 'Agent [' . $appointment->property->agent->agentName . '] has updated the appointment details for your property viewing for #' . $appointment->property->propertyID . ' (' . $appointment->property->propertyName . ')' .
            ' on ' . $appointment->timeslot->date . ' to ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . '.';

        $noteTenant = "If you have any concerns or need further assistance, please contact your agent.";
        $descTenant = 'You are receiving this email to inform you about the modified appointment.';

        $tenantEmail = $appointment->tenant->tenantEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContentTenant, 'header' => $headerTenant, 'desc' => $descTenant, 'note' => $noteTenant], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Appointment Modified');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        // Notify Agent
        $headerAgent = 'Appointment Modification Notification';
        $emailContentAgent = 'Your appointment timeslot for property #' . $appointment->property->propertyID . ' (' . $appointment->property->propertyName . ')' .
            ' on ' . $appointment->timeslot->date . ' have been successfully updated to ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . '.';


        $noteAgent = "If you have any concerns or need further assistance, please contact the tenant.";
        $descAgent = 'You are receiving this email to inform you about the modified appointment.';

        $agentEmail = $appointment->property->agent->agentEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContentAgent, 'header' => $headerAgent, 'desc' => $descAgent, 'note' => $noteAgent], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Appointment Modification Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        // Redirect back with a success message
        return redirect()->route('appointments.agentIndex')->with('success', 'Appointment updated successfully');
    }

    public function agentCancel(string $id)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($id);

        // Check if the appointment exists
        if (!$appointment) {
            abort(404, 'Appointment not found');
        }

        // Update appointment fields with the new values
        $appointment->status = "Cancelled";
       

        // Save the updated appointment
        $appointment->save();

        // Create the notification content
        $notificationContent = 'Appointment for #' . $appointment->property->propertyID . $appointment->property->propertyName .
            ' on ' . $appointment->timeslot->date .' ('. $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime .') had been cancelled by agent';



        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Appointment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $appointment->tenant->tenantID;

        // Save  notification
        $notification->save();

        // Create the notification content
        $notificationContent = 'Appointment Cancelled for #' . $appointment->property->propertyID . $appointment->property->propertyName .
            ' on ' . $appointment->timeslot->date.' ('. $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime .') .! ';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Appointment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $appointment->property->agent->agentID;

        $notification->save();

        // Notify Tenant
        $headerTenant = 'Appointment Cancellation';

        $emailContentTenant = 'Agent [' . $appointment->property->agent->agentName . '] has cancel the appointment for your property viewing for #' . $appointment->property->propertyID . ' (' . $appointment->property->propertyName . ')' .
            ' on ' . $appointment->timeslot->date . ' ( ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . ' ).';

        $noteTenant = "If you have any concerns or need further assistance, please contact your agent.";
        $descTenant = 'You are receiving this email to inform you about the canceled appointment.';

        $tenantEmail = $appointment->tenant->tenantEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContentTenant, 'header' => $headerTenant, 'desc' => $descTenant, 'note' => $noteTenant], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Appointment Canceled');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        // Notify Agent
        $headerAgent = 'Appointment Cancellation Notification';
        $emailContentAgent = 'Your appointment for property #' . $appointment->property->propertyID . ' (' . $appointment->property->propertyName . ')' .
            ' on ' . $appointment->timeslot->date . ' ( ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . ') have been cancelled successfully.';
        $noteAgent = "If you have any concerns or need further assistance, please contact the tenant.";
        $descAgent = 'You are receiving this email to inform you about the canceled appointment.';

        $agentEmail = $appointment->property->agent->agentEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContentAgent, 'header' => $headerAgent, 'desc' => $descAgent, 'note' => $noteAgent], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Appointment Cancellation Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }


        // Redirect back with a success message
        return redirect()->route('appointments.agentIndex')->with('success', 'Appointment cancel successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function cancel(string $id)
    {
        // Find the appointment by ID
        $appointment = Appointment::find($id);

        // Check if the appointment exists
        if (!$appointment) {
            abort(404, 'Appointment not found');
        }

        // Update appointment fields with the new values
        $appointment->status = "Cancelled";

        // Save the updated appointment
        $appointment->save();


        // Create the notification content

        $notificationContent = 'Appointment Cancelled for #' . $appointment->property->propertyID . $appointment->property->propertyName .
            ' on ' . $appointment->timeslot->date.' ('. $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime .') . ';
        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Appointment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $appointment->tenant->tenantID;

        // Save  notification
        $notification->save();

        // Create the notification content
        $notificationContent = 'Appointment for #' . $appointment->property->propertyID . $appointment->property->propertyName .
            ' on ' . $appointment->timeslot->date .' ('. $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime .') had been cancelled by agent.';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Appointment';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $appointment->property->agent->agentID;
        $notification->save();

        // Notify Tenant
        $headerTenant = 'Appointment Cancellation';
        $emailContentTenant = 'Your appointment for property #' . $appointment->property->propertyID . ' (' . $appointment->property->propertyName . ')' .
            ' on ' . $appointment->timeslot->date . ' ( ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . ') have been cancelled successfully.';

        $noteTenant = "If you have any concerns or need further assistance, please contact your agent.";
        $descTenant = 'You are receiving this email to inform you about the canceled appointment.';

        $tenantEmail = $appointment->tenant->tenantEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContentTenant, 'header' => $headerTenant, 'desc' => $descTenant, 'note' => $noteTenant], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Appointment Canceled');
            });
        } catch (TransportExceptionInterface $e) {
            //dd($e);
            // Handle the exception or log the error
        }

        // Notify Agent
        $headerAgent = 'Appointment Cancellation Notification';

        $emailContentAgent = 'Tenant [' . $appointment->tenant->tenantName . '] has cancel the appointment for your property viewing for #' . $appointment->property->propertyID . ' (' . $appointment->property->propertyName . ')' .
            ' on ' . $appointment->timeslot->date . ' ( ' . $appointment->timeslot->startTime . ' - ' . $appointment->timeslot->endTime . ' ).';

        $noteAgent = "If you have any concerns or need further assistance, please contact the tenant.";
        $descAgent = 'You are receiving this email to inform you about the canceled appointment.';

        $agentEmail = $appointment->property->agent->agentEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContentAgent, 'header' => $headerAgent, 'desc' => $descAgent, 'note' => $noteAgent], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Appointment Cancellation Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        // Redirect back with a success message
        return redirect()->route('appointments')->with('success', 'Appointment cancel successfully');
    }


    public function setReminder(string $appID)
    {
        $appointment = Appointment::find($appID);

        // Calculate the reminder times
        $oneHourBeforeReminderTime = Carbon::parse($appointment->timeslot->date . ' ' . $appointment->timeslot->startTime)->subHour();

        $sameDayReminderTime = Carbon::parse($appointment->timeslot->date . ' 00:00:00');


        // Dispatch the jobs to send reminders
        SendOneHourBeforeReminderEmail::dispatch($appointment)
            ->delay($oneHourBeforeReminderTime);

        SendSameDayReminderEmail::dispatch($appointment)
            ->delay($sameDayReminderTime);

        $appointment->reminder = 1;
        $appointment->save();

        // Redirect back with a success message
        return redirect()->back()->with('success', 'Reminders set successfully!');
    }




    public function generateUniqueAppointmentID()
    {
        $latestAppointment = Appointment::orderBy('appID', 'desc')->first();

        if ($latestAppointment) {
            $lastID = ltrim(substr($latestAppointment->appID, 3), '0'); // Remove the "WTR" prefix and leading zeros
            $nextID = 'APP' . str_pad($lastID + 1, 7, '0', STR_PAD_LEFT); // Increment and pad to 7 digits
        } else {
            $nextID = 'APP0000001'; // Initial ID
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