<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Tenant;
use App\Models\Agent;
use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ForgetAndResetPasswordController extends Controller
{
   /**
       * Write code on Method
       *
       * @return \response()
       */
      public function submitForgetPasswordForm(Request $request){
        
        $email = trim($request->input('email'));
        $validator = Validator::make(['email' => $email], [
            'email' => [
                function ($attribute, $value, $fail) {
                   
                    if (!DB::table('tenants')->where('tenantEmail', $value)->exists() &&
                        !DB::table('agents')->where('agentEmail', $value)->exists() &&
                        !DB::table('admins')->where('adminEmail', $value)->exists()) {
                        $fail("The $attribute is invalid.");
                    }
                },
            ],
        ]);
    
        if ($validator->fails()) {
            $errors = $validator->errors();
            return redirect()->back()->with('fgtError', '*Email Address does not exist as a registered email in RentSpace....');
        }
    
        $token = Str::random(64);
    
        DB::table('password_reset_tokens')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
        ]);
    
        try {
       
        $imagePath = public_path('storage/images/logo.png');
        
       
       
        Mail::send('forgotPasswordContent', ['imagePath' => $imagePath, 'token' => $token], function($message) use($request){
         $message->to($request->email);
         $message->subject('Reset Password');
        });

        
    
            return back()->with('success', 'We have e-mailed your password reset link!');


        } catch (TransportExceptionInterface $e) {
            dd($e);
            return back()->withErrors(['error' => 'Sorry, there was an error sending the password reset link. Please try again later.']);
        }
    }

 /**
       * Write code on Method
       *
       * @return \response()
       */
    public function showResetPasswordForm($token) { 
        return view('UserResetPassword', ['token' => $token]);
     }

     /**
       * Write code on Method
       *
       * @return \response()
       */
      public function submitResetPasswordForm(Request $request){
       
        $request->validate([
            'email' => [
                function ($attribute, $value, $fail) {
                   
                    if (!DB::table('tenants')->where('tenantEmail', $value)->exists() &&
                        !DB::table('agents')->where('agentEmail', $value)->exists() &&
                        !DB::table('admins')->where('adminEmail', $value)->exists()) {
                        $fail("The $attribute is invalid.");
                    }
                },
            ],
            'password' => 'required|string|min:6|max:15|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_reset_tokens')
                            ->where([
                              'email' => $request->email, 
                              'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token! Please make sure the link you click is correct..');
        }

        $userType = DB::table('tenants')->where('tenantEmail', $request->email)->exists() ? 'tenants' :
        (DB::table('agents')->where('agentEmail', $request->email)->exists() ? 'agents' :
        (DB::table('admins')->where('adminEmail', $request->email)->exists() ? 'admins' : null));

        if ($userType) {

            if ($userType === 'tenants') {
                $user = Tenant::where('tenantEmail', $request->email)
                    ->update([
                        'password' => Hash::make($request->password),
                        'updateAt' => Carbon::now()
                    ]);
            } elseif ($userType === 'agents') {
                $user = Agent::where('agentEmail', $request->email)
                    ->update([
                        'password' => Hash::make($request->password),
                        'updateAt' => Carbon::now()
                    ]);
            } elseif ($userType === 'admins') {
                $user = Admin::where('adminEmail', $request->email)
                    ->update([
                        'password' => Hash::make($request->password)
                    ]);
            } else {
                return back()->withInput()->with('error', 'Something Error. Please make sure input email is correct');
            }
            
        }

        DB::table('password_reset_tokens')->where(['email'=> $request->email])->delete();

        return redirect('/HomeLogin')->with('reset_success', 'Your password has been changed. Please try to login again.');
    }
      
}
