<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class CheckPropertyExpiration extends Command
{
    protected $signature = 'property:check-expiration';
    protected $description = 'Check property expiration and notify agents';

    public function handle()
    {
        $propertiesExpiringSoon = Property::where('expiredDate', '>', now())
            ->where('expiredDate', '<=', now()->addDays(3))
            ->get();

        foreach ($propertiesExpiringSoon as $property) {
            // Send email reminder to agent
            $header = 'Property Expiration Reminder';
            $emailContent = 'The property with ID #' . $property->propertyID . ' (' . $property->propertyName . ')' .
                ' is expiring soon. Please renew the subscription to avoid any service interruptions.';
            $note = 'If you have already renewed, please ignore this message.';
            $desc = 'You are receiving this email as a reminder for your expiring property subscription.';

            $agentEmail = $property->agent->agentEmail;

            try {
                $imagePath = public_path('storage/images/logo.png');

                Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                    $message->to($agentEmail);
                    $message->subject('Property Expiration Reminder');
                });
            } catch (TransportExceptionInterface $e) {
                dd($e);
                // Handle the exception or log the error
            }
        }
        // Logic to check properties expired today
        $propertiesExpiredToday = Property::whereDate('expiredDate', now())->get();

        foreach ($propertiesExpiredToday as $property) {

            // Send email reminder to agent
            $header = 'Property Expiration Reminder';
            $emailContent = 'The property with ID #' . $property->propertyID . ' (' . $property->propertyName . ')' .
                ' has expired today. Please renew the subscription to avoid any service interruptions.';
            $note = 'If you have already renewed, please ignore this message.';
            $desc = 'You are receiving this email as a reminder for your expired property subscription.';

            $agentEmail = $property->agent->agentEmail;

            try {
                $imagePath = public_path('storage/images/logo.png');

                Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                    $message->to($agentEmail);
                    $message->subject('Property Expiration Reminder');
                });
            } catch (TransportExceptionInterface $e) {
                dd($e);
                // Handle the exception or log the error
            }
        }

        // Logic to check properties already expired
        $propertiesAlreadyExpired = Property::whereDate('expiredDate', '<', now())->get();

        foreach ($propertiesAlreadyExpired as $property) {
            // Send email reminder to agent
            $header = 'Property Expired Reminder';
            $emailContent = 'The property with ID #' . $property->propertyID . ' (' . $property->propertyName . ')' .
                ' has already expired. Please renew the subscription to avoid any service interruptions.';
            $note = 'If you have already renewed, please ignore this message.';
            $desc = 'You are receiving this email as a reminder for your expired property subscription.';

            $agentEmail = $property->agent->agentEmail;

            try {
                $imagePath = public_path('storage/images/logo.png');

                Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                    $message->to($agentEmail);
                    $message->subject('Property Expired Reminder');
                });
            } catch (TransportExceptionInterface $e) {
                dd($e);
                // Handle the exception or log the error
            }
        }

        $this->info('Property expiration check completed.');
    }
}
