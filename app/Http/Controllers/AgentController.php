<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Agent;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\Review;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Chatify\Facades\ChatifyMessenger as Chatify;
class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function ViewAgentLists()
    {
        $agentList = DB::table('agents')
        ->where('status', 'active')
        ->paginate(6);
        session(['isSearching' => 'No']);
        return view('tenant/AgentLists', ['agentList' => $agentList]);
    
    
    }

    public function showProfile(String $id)
{
    $user = null;
    $userRole = null;

    if (strpos($id, 'ADM') === 0) {
        // If id starts with 'AGT', treat it as an admin ID
        $user = Admin::where('adminID', $id)->first();
        $userRole = "admin";
        // Add the 'userRole' property to the user object
        $user->userRole = $userRole;

    return view('agent.agentProfile', ['user' => $user]);

    } else {
        // Otherwise, treat it as an agent ID
        $user = Agent::where('agentID', $id)->first();
        $userRole = "agent";
        // Add the 'userRole' property to the user object
    $user->userRole = $userRole;

    $reviews = Review::with('agent', 'tenant')
    ->where('reviewItemID', $id)
    ->orderBy('reviewID', 'desc')
    ->paginate(6);

    return view('agent.agentProfile', ['user' => $user,'reviews'=>$reviews]);
    }

 

    
}


   
    public function SearchAgent(Request $request)
    {
        $search = $request->input('search');
        

        $results = Agent::where('agentName', 'like', "%$search%")
                        ->where('status', 'active')
                        ->paginate(6);
                        
        session(['isSearching' => 'Yes']);

     return view('tenant/AgentLists', ['results' => $results]);
    }
  
    public function AgentDetails(Request $request, string $id)
    {
        $agent = Agent::where('agentID', $id)->first();

        $averageRating = Review::where('reviewItemID', $id)->avg('rating');

        $reviews = Review::where('ParentReviewID', null)
        ->with('agent', 'tenant') 
        ->where('reviewItemID', $id)
        ->orderBy('reviewID')
        ->get();
     
        $replies = Review::whereNotNull('ParentReviewID')
            ->with('agent', 'tenant')
            ->where('reviewItemID', $id)
            ->orderBy('ParentReviewID')
            ->get();


        $properties = Property::select(
            'properties.*',
            DB::raw('(SELECT propertyPath FROM property_photos WHERE propertyID = properties.propertyID ORDER BY created_at DESC LIMIT 1) as propertyPhotoPath')
        )
        ->withCount(['photos' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->where('agentID', $id)
        ->where('propertyAvailability', 1)
        ->orderBy('created_at', 'desc')
        ->orderBy('updated_at', 'desc')
        ->get();

    

        if ($agent) {
           
            return view('tenant.AgentDetails', ['agent' => $agent, 'averageRating' => $averageRating , 'reviews' => $reviews, 'replies' => $replies,'properties'=> $properties]);
        } else {
            
            return view('tenant.AgentDetails');
        }
    }

    public function AgentDetailsAdmin(Request $request, string $id)
    {
        $agent = Agent::where('agentID', $id)->first();

        $averageRating = Review::where('reviewItemID', $id)->avg('rating');

        $reviews = Review::where('ParentReviewID', null)
        ->with('agent', 'tenant') 
        ->where('reviewItemID', $id)
        ->orderBy('reviewID')
        ->get();
     
        $replies = Review::whereNotNull('ParentReviewID')
            ->with('agent', 'tenant')
            ->where('reviewItemID', $id)
            ->orderBy('ParentReviewID')
            ->get();


        $properties = Property::select(
            'properties.*',
            DB::raw('(SELECT propertyPath FROM property_photos WHERE propertyID = properties.propertyID ORDER BY created_at DESC LIMIT 1) as propertyPhotoPath')
        )
        ->withCount(['photos' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])
        ->where('agentID', $id)
        ->where('propertyAvailability', 1)
        ->orderBy('created_at', 'desc')
        ->orderBy('updated_at', 'desc')
        ->get();

    

        if ($agent) {
           
            return view('admin/agentViewProfile', ['agent' => $agent, 'averageRating' => $averageRating , 'reviews' => $reviews, 'replies' => $replies,'properties'=> $properties]);
        } else {
            
            return view('admin/agentIndex');
        }
    }


    public function uploadAgentPhoto(Request $request) {
        $file = $request->file('profile_image');
        $id = $request->get('id');
        $userRole = $request->get('userRole');
        
        if ($file) {
            $fileName = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            $update = User::where('id', Auth::user()->id)->update(['avatar' => $fileName]);
            $file->storeAs(config('chatify.user_avatar.folder'), $fileName, config('chatify.storage_disk_name'));

            if (Auth::user()->avatar != config('chatify.user_avatar.default')) {
                $avatar = Auth::user()->avatar;
                if (Chatify::storage()->exists($avatar)) {
                    Chatify::storage()->delete($avatar);
                }
            }
        
            if($userRole=='agent'){
                $agent = Agent::where('agentID', $id)->first();
                $agent->photo = 'users-avatar/profile_' . time() . '.' . $file->getClientOriginalExtension();
                $agent->updateAt = Carbon::now();
                $agent->save();
                $updatedAgent = Agent::where('agentID', $id)->first();
                session(['agent' => $updatedAgent]);

                return redirect()->back()->with('success', 'Photo Upload Successful');

            }if($userRole=='admin'){
                $admin = Admin::where('adminID', $id)->first();
                $admin->photo = 'users-avatar/profile_' . time() . '.' . $file->getClientOriginalExtension();
                $admin->save();
                $updatedAdmin = Admin::where('adminID', $id)->first();
                session(['admin' => $updatedAdmin]);

                return redirect()->back()->with('success', 'Photo Upload Successful');
            }else{
                return redirect()->back()->with('error', 'Photo Upload Error. Please Try Again');
            }
           

        }else{
            return redirect()->back()->with('error', 'Photo Upload Failed. Please try again.');
        }
        
    }

    public function updateAgentProfile(Request $request) {

        $id = $request->get('id');
        $userRole = $request->get('userRole');

        if($userRole=="agent"){
            $agent = Agent::where('agentID', $id)->first();

            $validatedData = $request->validate([
                'name' => 'required|max:20',
                'phone' => [
                    'required',
                    'regex:/^01\d{1}-\d{7,8}$/',
                    Rule::unique('tenants', 'tenantPhone'),
                    Rule::unique('agents', 'agentPhone')->ignore($id, 'agentID'),
                    Rule::unique('admins', 'adminPhone'),
                ],
                'licenseNum' => [
                    'nullable',
                    'regex:/^(?i)(ren|rea)\d{5}$/',
                    Rule::unique('agents', 'licenseNum')->ignore($id, 'agentID'),
                ],
            ]);
            try {
                User::where('id', Auth::user()->id)->update(['name' =>$request->input('name')]);
                $agent->agentName = $request->input('name');
                $agent->agentPhone = $request->input('phone');
                $agent->licenseNum = $request->input('licenseNum');
                $agent->updateAt = Carbon::now();
                $agent->save();
                
                $updatedAgent = Agent::where('agentID', $id)->first();
                session(['agent' => $updatedAgent]);

                return redirect()->back()->with('success', 'Information has been updated');

            }catch (\Exception $ex) {
          
                return back()->withErrors(['error' => $ex->getMessage()])->withInput();
            }
        }if($userRole=='admin'){
            $admin = Admin::where('adminID', $id)->first();

            $validatedData = $request->validate([
                'name' => 'required|max:20',
                'phone' => [
                    'required',
                    'regex:/^01\d{1}-\d{7,8}$/',
                    Rule::unique('tenants', 'tenantPhone'),
                    Rule::unique('agents', 'agentPhone'),
                    Rule::unique('admins', 'adminPhone')->ignore($id, 'adminID'),
                ],
            ]);

            try {
                User::where('id', Auth::user()->id)->update(['name' =>$request->input('name')]);
                $admin->adminName = $request->input('name');
                $admin->adminPhone = $request->input('phone');
                $admin->save();
                
                $updatedAdmin = Admin::where('adminID', $id)->first();
                session(['admin' => $updatedAdmin]);


                return redirect()->back()->with('success', 'Information has been updated');

            }catch (\Exception $ex) {
        
                return back()->withErrors(['error' => $ex->getMessage()])->withInput();
            }
        }
    }
    public function showChangePasswordForm(){
        return view('agent/changePassword');
    }
    public function updateNewPassword(Request $request){
        $id = $request->get('id');
        $userRole = $request->get('userRole');
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

        if($userRole=="agent"){
            $agent = Agent::where('agentID', $id)->first();

            if (!Hash::check($currentPassword, $agent->password)) {
                return redirect()->back()->withErrors(['currentPassword' => 'Incorrect current password.'])->withInput();
            }
            User::where('id', Auth::user()->id)->update(['password' =>Hash::make($password)]);
            $agent->password = Hash::make($password);
            $agent->updateAt = Carbon::now();
            $agent->save();
            $updatedAgent = Agent::where('agentID', $id)->first();
            session(['agent' => $updatedAgent]);
            return redirect()->back()->with('success', 'Password has been reset.');
        }else{
            $admin = Admin::where('adminID', $id)->first();

            if (!Hash::check($currentPassword, $admin->password)) {
                return redirect()->back()->withErrors(['currentPassword' => 'Incorrect current password.'])->withInput();
            }
            User::where('id', Auth::user()->id)->update(['password' =>Hash::make($password)]);
            $admin->password = Hash::make($password);
            $admin->save();
            $updatedAdmin = Admin::where('adminID', $id)->first();
            session(['admin' => $updatedAdmin]);

            return redirect()->back()->with('success', 'Password has been reset.');
        }
      
    }
    public function getPropertyState($propertyID) {
      
        $propertyState = Property::with('state')->find($propertyID);

        if ($propertyState) {
            // Check if the property has a state relationship
            if ($propertyState->state) {
                $stateName = $propertyState->state->stateName;
                return $stateName;
            } else {
                // The property does not have a state relationship
                return response()->json(['error' => 'Property does not have a state'], 404);
            }
        } else {
            // Property not found
            return response()->json(['error' => 'Property not found'], 404);
        }
        
    }
}
