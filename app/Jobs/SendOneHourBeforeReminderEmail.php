<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Appointment;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class SendOneHourBeforeReminderEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function handle()
    {
        $header = 'One-Hour Before Appointment Reminder';
        $emailContent = 'You have an upcoming appointment with ' . $this->appointment->property->agent->agentName .
            ' for #' . $this->appointment->property->propertyID . ' (' . $this->appointment->property->propertyName . ')' .
            ' on ' . $this->appointment->timeslot->date . ' from ' . $this->appointment->timeslot->startTime . ' to ' . $this->appointment->timeslot->endTime . '.';
        $note = "If you are unable to attend the appointment, please log in to your account to contact your agent.";
        $desc = 'You are receiving this email as a reminder for your upcoming appointment.';

        $tenantEmail = $this->appointment->tenant->tenantEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('One-Hour Before Reminder');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        $header = 'One-Hour Before Appointment Reminder';
        $emailContent = 'You have an upcoming appointment with ' . $this->appointment->tenant->tenantName .
            ' for #' . $this->appointment->property->propertyID . ' (' . $this->appointment->property->propertyName . ')' .
            ' on ' . $this->appointment->timeslot->date . ' from ' . $this->appointment->timeslot->startTime . ' to ' . $this->appointment->timeslot->endTime . '.';
        $note = "If you are unable to attend the appointment,please log in to your account to contact your tenant.";
        $desc = 'You are receiving this email as a reminder for your upcoming appointment.';
    
        $agentEmail = $this->appointment->property->agent->agentEmail;
    
        try {
            $imagePath = public_path('storage/images/logo.png');
    
            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('One-Hour Before Reminder');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }
    }
}
