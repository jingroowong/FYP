<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Review;
use App\Models\Agent;
use App\Models\Property;
use App\Models\SearchHistory;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Chatify\Facades\ChatifyMessenger as Chatify;
use Illuminate\Support\Facades\DB;
class TenantAccountContoller extends Controller{
    public function showMyAccount(string $id)
    {
    
        $tenantID = $id;

    
        $userReviews = Review::where('reviewerID',$id )->get();

        foreach ($userReviews as $review) {

            $reviewItemID = $review->reviewItemID;
        
            if (Str::startsWith($reviewItemID, 'AGT')) {
                $agentName = Agent::where('agentID', $reviewItemID)->value('agentName');
                $review->itemName = 'Agent ' . $agentName;
            } elseif (Str::startsWith($reviewItemID, 'PRO')) {
                 $propertyInfo = Property::where('propertyID', $reviewItemID)->value('propertyName');
                 $review->itemName = 'Property ' . $propertyInfo;
            }
        }
       
    return view('tenant/ViewMyTenantAccount', ['userReviews' => $userReviews]);
      
    }

    public function UploadPhoto(Request $request) {

        
        $file = $request->file('profile_image');
        $tenantID = $request->get('tenantID');
        
        if ($file) {
            $fileName = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            $update = User::where('id', Auth::user()->id)->update(['avatar' => $fileName]);
            $file->storeAs(config('chatify.user_avatar.folder'), $fileName, config('chatify.storage_disk_name'));

            if ($tenantID) {
                $tenant = Tenant::where('tenantID', $tenantID)->first();
                $tenant->photo = 'users-avatar/profile_' . time() . '.' . $file->getClientOriginalExtension();
                $tenant->updateAt = Carbon::now();
                $tenant->save();
                $updatedTenant = Tenant::where('tenantID', $tenantID)->first();
                session(['tenant' => $updatedTenant]);


                if (Auth::user()->avatar != config('chatify.user_avatar.default')) {
                    $avatar = Auth::user()->avatar;
                    if (Chatify::storage()->exists($avatar)) {
                        Chatify::storage()->delete($avatar);
                    }
                }

            } else {
                
                return redirect()->back()->with('upload-error', 'No such tenant found.');
            }

            return redirect()->back()->with('upload-success', 'Photo Upload Successful');

        }else{
            return redirect()->back()->with('upload-error', 'Photo Upload Failed. Please try again.');
        }
        
       
    }
    

    public function updateProfile(Request $request)
    {   
        session(['dynamicContent' => 'profile']);
        $tenantID = $request->get('tenantID');
        $tenant = Tenant::where('tenantID', $tenantID)->first();
        
        if ($tenant) {
        $validatedData = $request->validate([
            'tenantName' => 'required|max:20',
            'tenantPhone' => [
                'required',
                'regex:/^01\d{1}-\d{7,8}$/',
                Rule::unique('tenants', 'tenantPhone')->ignore($tenantID, 'tenantID'),
                Rule::unique('agents', 'agentPhone'),
                Rule::unique('admins', 'adminPhone'),
            ],
            'tenantDOB' => [
                'required',
                function ($attribute, $value, $fail) {
                    $currentDate = date('d-m-Y');
                    if (strtotime($value) > strtotime($currentDate)) {
                        $fail('The selected Date of Birth must be a date before the current date.');
                    }
                },
            ],
            'gender' => 'required',
        ]);
        try {
            User::where('id', Auth::user()->id)->update(['name' =>$request->input('tenantName')]);
            $tenantDOB = date('d-m-Y', strtotime($request->input('tenantDOB')));
            $tenant->tenantName = $request->input('tenantName');
            $tenant->tenantPhone = $request->input('tenantPhone');
            $tenant->gender = $request->input('gender');
            $tenant->tenantDOB = $tenantDOB;
            $tenant->updateAt = Carbon::now();
            $tenant->save();
            
            $updatedTenant = Tenant::where('tenantID', $tenantID)->first();
            session(['tenant' => $updatedTenant]);
            
          
            return redirect()->back()->with('update-success', 'Information has been updated');


        } catch (\Exception $ex) {
          
            return back()->withErrors(['error' => $ex->getMessage()])->withInput();
    }
    }else{
        return redirect()->back()->with('update-error', 'No such tenant found.');
    }

    }
  
    public function updatePassword(Request $request)
    {   

        session(['dynamicContent' => 'reset-password']);
      
        $tenantID = $request->input('tenantID');
        $currentPassword = $request->input('currentPassword');
        $password = $request->input('password');
     

        $rules = [
            'currentPassword' => 'required',
            'password' => 'required|min:6|max:15|confirmed',
        ];

        $messages = [
            'currentPassword.required' => 'Please enter your current password.',
            'password.required' => 'Please enter a new password.',
            'password.min' => 'New password must be at least 6 characters.',
            'password.max' => 'New password cannot exceed 15 characters.',
            'password.confirmed' => 'New password and confirmation do not match.',
        ];

        $this->validate($request, $rules, $messages);

        $tenant = Tenant::where('tenantID', $tenantID)->first();

        if (!$tenant) {
            return redirect()->back()->with('reset-error', 'No such tenant found.');
        }

      
        if (!Hash::check($currentPassword, $tenant->password)) {
            return redirect()->back()->withErrors(['currentPassword' => 'Incorrect current password.'])->withInput();
        }

        User::where('id', Auth::user()->id)->update(['password' =>Hash::make($password)]);
        $tenant->password = Hash::make($password);
        $tenant->updateAt = Carbon::now();
        $tenant->save();
        $updatedTenant = Tenant::where('tenantID', $tenantID)->first();
        session(['tenant' => $updatedTenant]);

        return redirect()->back()->with('reset-success', 'Password has been reset.');
    }

    public function viewSearchList(String $id){

        $searchHistory = Property::join('search_histories', 'properties.propertyID', '=', 'search_histories.propertyID')
        ->leftJoin('states', 'properties.stateID', '=', 'states.stateID')
        ->leftJoin('agents', 'properties.agentID', '=', 'agents.agentID') // Left join with agents table
        ->where('search_histories.tenantID', $id)
        ->select(
            'properties.*',
            'search_histories.*',
            'states.stateName', // Select the stateName
            'agents.*', // Select all columns from agents table
            DB::raw('(SELECT propertyPath FROM property_photos WHERE propertyID = properties.propertyID ORDER BY created_at DESC LIMIT 1) as propertyPhotoPath'),
            DB::raw('(SELECT count(*) FROM property_photos WHERE properties.propertyID = property_photos.propertyID) as photos_count'),
            DB::raw('(SELECT ROUND(AVG(rating), 1) FROM reviews WHERE reviewItemID = agents.agentID) as agentAverageRating'),
            DB::raw('(SELECT ROUND(AVG(rating), 1) FROM reviews WHERE reviewItemID = properties.propertyID) as propertyAverageRating'),
        )
        ->orderBy('search_histories.updated_at', 'desc')
        ->paginate(15);
       

       return view('tenant/SearchHistory',['searchHistory'=>$searchHistory]);
    }

    public function RemoveSelected(Request $request){
        $selectedSearch = $request->input('selectedItems');
       
        foreach ($selectedSearch as $search) {
            $searchData = json_decode($search, true);

        
        SearchHistory::where('propertyID', $searchData['propertyID'])
            ->where('tenantID', $searchData['tenantID'])
            ->delete();
        
        }

        return redirect()->back()->with('success', 'Selected search history removed successfully.');
    }

}