<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Tenant;
use App\Models\Agent;
use App\Models\Property;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\ChMessage as Message;
use Session;
use GuzzleHttp\Client;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }

    public function checkMessages($userId)
    {
        // Assuming you're using the Auth facade to get the authenticated user
        $authenticatedUserId = Auth::id();
    
        // Check for messages where 'to_id' is the authenticated user's ID and 'seen' is 0
        $unseenMessagesCount = Message::where('to_id', $authenticatedUserId)->where('seen', 0)->count();
    
        return response()->json(['unseenMessagesCount' => $unseenMessagesCount]);
    }
    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('HomeLogin');
    }
    public function getCommonTypes()
{
    $searchHistories = SearchHistory::where('tenantID', Auth::user()->id)
        ->orderBy('updated_at', 'desc')
        ->take(10)
        ->get();

    $commonPropertyType = DB::table('search_histories')
        ->join('properties', 'search_histories.propertyID', '=', 'properties.propertyID')
        ->whereIn('search_histories.searchID', $searchHistories->pluck('searchID'))
        ->select(
            'properties.propertyType',
            DB::raw('COUNT(search_histories.searchID) as totalSearches')
        )
        ->groupBy('properties.propertyType')
        ->orderByDesc('totalSearches')
        ->first();

    $commonStateType = DB::table('search_histories')
        ->join('properties', 'search_histories.propertyID', '=', 'properties.propertyID')
        ->whereIn('search_histories.searchID', $searchHistories->pluck('searchID'))
        ->select(
            'properties.stateID',
            DB::raw('COUNT(search_histories.searchID) as totalSearches')
        )
        ->groupBy('properties.stateID')
        ->orderByDesc('totalSearches')
        ->first();

        $commonRoomType = DB::table('search_histories')
        ->join('properties', 'search_histories.propertyID', '=', 'properties.propertyID')
        ->whereIn('search_histories.searchID', $searchHistories->pluck('searchID'))
        ->select(
            'properties.roomType',
            DB::raw('COUNT(search_histories.searchID) as totalSearches')
        )
        ->groupBy('properties.roomType')
        ->orderByDesc('totalSearches')
        ->first();

    return [
        'commonPropertyType' => $commonPropertyType->propertyType,
        'commonStateType' => $commonStateType->stateID,
        'commonRoomType' => $commonRoomType->roomType,
    ];
}

public function generateRecommendation()
{
    $commonTypes = $this->getCommonTypes();

    $recommendedProperties = Property::where('propertyAvailability', 1)
        ->where('expiredDate', '>', now())
        ->where(function ($query) use ($commonTypes) {
            $query->where('propertyType', $commonTypes['commonPropertyType'])
                ->where('stateID', $commonTypes['commonStateType'])
                ->where('roomType', $commonTypes['commonRoomType']);
        })
        ->orWhere(function ($query) use ($commonTypes) {
            $query->where('stateID', $commonTypes['commonStateType'])
                ->where('roomType', $commonTypes['commonRoomType']);
        })
        ->orWhere(function ($query) use ($commonTypes) {
            $query->where('propertyType', $commonTypes['commonPropertyType'])
                ->where('roomType', $commonTypes['commonRoomType']);
        })
        ->orWhere(function ($query) use ($commonTypes) {
            $query->where('propertyType', $commonTypes['commonPropertyType'])
                ->where('stateID', $commonTypes['commonStateType']);
        })
        ->orWhere(function ($query) use ($commonTypes) {
            $query->where('propertyType', $commonTypes['commonPropertyType']);
        })
        ->inRandomOrder()
        ->take(10)
        ->get();

    // Ensure uniqueness of properties
    $recommendedProperties = $recommendedProperties->unique();

    // If less than 10 properties, add more based on additional criteria
    $remainingCount = 10 - $recommendedProperties->count();

    if ($remainingCount > 0) {
        $additionalProperties = Property::where('propertyAvailability', 1)
            ->where('expiredDate', '>', now())
            ->inRandomOrder()
            ->take($remainingCount)
            ->get();

        // Ensure uniqueness of additional properties
        $additionalProperties = $additionalProperties->diff($recommendedProperties);

        // Concatenate additional properties to the recommendation list
        $recommendedProperties = $recommendedProperties->concat($additionalProperties);
    }

    return $recommendedProperties;
}

public function HomePage()
{
    if (Auth::user()) {
        // Check if there are search histories for the authenticated user
        $searchHistoryCount = SearchHistory::where('tenantID', Auth::id())->count();
    
        if ($searchHistoryCount > 0) {
            // Generate recommendations
            $result = $this->generateRecommendation();
    

        }else{
            $result = Property::where('propertyAvailability', 1)
                ->where('expiredDate', '>', now())
                ->take(10)
                ->get();
            
        }
    }


    return view('tenant/HomePage', ['result' => $result]);
}

    public function map()
    {
      //urlencode address
      $address = urlencode("Puchong");

      $client = new Client();
      $response = $client->get("https://nominatim.openstreetmap.org/search?format=json&q={$address}");

      $data = array(
          array(
              "lat" => 3,
              "lng" => 101
          ),
          array(
              "lat" => 2,
              "lng" => 101
          ),
      );

      $result = json_decode($response->getBody(), true);

      if (!empty($result)) {
          $i = 1;
          foreach ($data as $item) {
              $latitude = (int) $item['lat'];
              $longitude = (int) $item['lng'];

              $initialMarkers[] = [
                  'position' => [
                      'lat' => $latitude,
                      'lng' => $longitude,
                  ],
                  'label' => ['color' => 'white', 'text' => 'P'. $i],
                  'draggable' => true,
              ];
              $i++;
          }
      } else {
          //display error message
//            return response()->json(['error' => 'Address not found'], 404);
      }

      return view('map', compact('initialMarkers'));
  }
  
    public function TenantLogin(Request $request)
{   
  

    $request->session()->flush();
    $credentials = $request->only('tenantEmail', 'password');
    $tenant = Tenant::where('tenantEmail', $credentials['tenantEmail'])->first();

    if ($tenant && Hash::check($credentials['password'], $tenant->password)) {
        
        Auth::guard('tenant')->login($tenant);

        if ($request->has('remember')) {
            Auth::guard('tenant')->login($tenant, true);
        } else {
            Auth::guard('tenant')->login($tenant);
        }

        if (Auth::guard('tenant')->check()) {
            $request->session()->regenerate();
            $request->session()->put('tenant', $tenant);
            Auth::loginUsingID($tenant->tenantID);
            return redirect('HomePage');
        }
    } else {
       
        return redirect()->back()->with('tntError', 'Authentication failed, Incorrect Email or Password');


    }
}

public function AgentLogin(Request $request)
{   
  

    $request->session()->flush();
    $credentials = $request->only('agentEmail', 'password');
    $agent = Agent::where('agentEmail', $credentials['agentEmail'])->first();

    if ($agent && Hash::check($credentials['password'], $agent->password)) {
        
        Auth::guard('agent')->login($agent);

        if ($request->has('remember')) {
            Auth::guard('agent')->login($agent, true);
        } else {
            Auth::guard('agent')->login($agent);
        }

        if (Auth::guard('agent')->check()) {
            Auth::loginUsingID($agent->agentID);
            $request->session()->put('agent', $agent);
           
            return redirect()->route('MyAgentAccount', ['id' => $agent->agentID]);
        }
    } else {
       
        return redirect()->back()->with('agtError', 'Authentication failed, Incorrect Email or Password');


    }
}


public function AdminLogin(Request $request)
{   
  

    $request->session()->flush();
    $credentials = $request->only('adminEmail', 'password');
    $admin = Admin::where('adminEmail', $credentials['adminEmail'])->first();

    if ($admin && Hash::check($credentials['password'], $admin->password)) {
        
        Auth::guard('agent')->login($admin);

        if ($request->has('remember')) {
            Auth::guard('admin')->login($admin, true);
        } else {
            Auth::guard('admin')->login($admin);
        }

        if (Auth::guard('admin')->check()) {
            Auth::loginUsingID($admin->adminID);
            $request->session()->put('admin', $admin);
            
           
        return redirect()->route('MyAgentAccount', ['id' => $admin->adminID]);

        }
    } else {
       
        return redirect()->back()->with('admError', 'Authentication failed, Incorrect Email or Password');


    }
}

public function logout(Request $request)
{
    if (Auth::guard('tenant')->check()) {

        Auth::guard('tenant')->logout();

    } elseif (Auth::guard('agent')->check()) {

        Auth::guard('agent')->logout();

    } elseif (Auth::guard('admin')->check()) {

        Auth::guard('admin')->logout();
      

    }
    
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('HomeLogin');

}


}
