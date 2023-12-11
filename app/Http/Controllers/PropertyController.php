<?php
namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\Property;
use App\Models\PropertyFacility;
use App\Models\PropertyPhoto;
use App\Models\PropertyRental;
use App\Models\State;
use App\Models\Tenant;
use App\Models\Filter;
use App\Models\Review;
use App\Models\Notification;
use App\Models\Wallet;
use App\Models\Agent;
use App\Models\SearchHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use App\Models\WalletTransaction;

class PropertyController extends Controller
{
    /**
     * Display a listing of the properties.
     */
    public function index()
    {
        $agentID = optional(session('agent'))->agentID;
        $agent = Agent::find($agentID);

        $properties = $agent->properties()->orderBy('propertyID', 'desc')->paginate(5);
        $propertyRentals = $agent->propertyRentals()->paginate(5);
        return view('agent/propertyIndex', compact('properties', 'propertyRentals'));
    }

    public function indexAll()
    {
        $properties = Property::paginate(5);
        $propertyRentals = PropertyRental::paginate(5);

        $paymentHistory = PropertyRental::whereIn('rentStatus', ['Paid', 'Refund requested', 'Refund approved', 'Refund rejected', 'Completed'])
            ->paginate(5);
            


        return view('admin/propertyIndex', compact('properties', 'propertyRentals', 'paymentHistory'));
    }

    public function propertyList()
    {
        $filters = Filter::all();
        $properties = Property::where('propertyAvailability', 1)
            ->where('expiredDate', '>', now()) // Add this condition
            ->paginate(15);

        $initialMarkers = $this->AllPropertyMap();

        foreach ($properties as $property) {
            // Assuming there is a relationship between Property and Rating, and Rating has a 'rating' column
            $averageRating = Review::where('reviewItemID', $property->propertyID)->avg('rating');
            $totalRating = Review::where('reviewItemID', $property->propertyID)->count('reviewID');

            // Add the average rating and total rating to the property object
            $property->average_rating = $averageRating * 2;
            $property->total_reviews = $totalRating;
        }


        return view('tenant/propertyIndex', compact('properties', 'filters', 'initialMarkers'));
    }


    /**
     * Show the form for creating a new property.
     */
    public function create()
    {
        $agentID = optional(session('agent'))->agentID;
        $states = State::all();
        $facilities = Facility::all();
        $walletBalance = Wallet::where('agentID', $agentID)->value('balance');

        return view('agent/propertyCreate', compact('states', 'facilities', 'walletBalance'));
    }

    /**
     * Store a newly created property in the database.
     */
    public function store(Request $request)
    {
        // Validate the form inputs
        $request->validate([
            'propertyName' => 'required|string|max:40',
            'propertyDesc' => 'required|string|max:150',
            'propertyType' => 'required|string|max:50',
            'roomType' => 'required|string|max:40',
            'housingType' => 'required|string|max:40',
            'propertyAddress' => 'required|string|max:100',
            'bedroomNum' => 'required|integer|min:1', 
            'bathroomNum' => 'required|integer|min:0', 
            'furnishingType' => 'required',
            'squareFeet' => 'required|integer|min:1', 
            'buildYear' => 'required|integer|min:1800|max:' . date('Y'),
            'rentalAmount' => 'required|numeric|min:0.01',
            'depositAmount' => 'nullable|numeric|min:0.01', 
            'stateID' => 'required|exists:states,stateID',
            'facilities' => 'array',
            'facilities.*' => 'exists:facilities,facilityID', // Validate each facility
            'propertyPhotos' => 'required|array|min:1',
            'propertyPhotos.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $agentID = optional(session('agent'))->agentID;
        // Create a new property instance
        $property = new Property();
        $property->propertyID = $this->generateUniquePropertyID();
        $property->propertyName = $request->input('propertyName');
        $property->propertyDesc = $request->input('propertyDesc');
        $property->propertyType = $request->input('propertyType');
        $property->housingType = $request->input('housingType');
        $property->roomType = $request->input('roomType');
        $property->propertyAddress = $request->input('propertyAddress');
        $property->propertyAvailability = 1;
        $property->clicks = 0;
        $property->expiredDate = now()->addDays(7);
        $property->bedroomNum = $request->input('bedroomNum');
        $property->bathroomNum = $request->input('bathroomNum');
        $property->buildYear = $request->input('buildYear');
        $property->squareFeet = $request->input('squareFeet');
        $property->furnishingType = $request->input('furnishingType');
        $property->rentalAmount = $request->input('rentalAmount');
        $property->depositAmount = $request->input('depositAmount');
        $property->stateID = $request->input('stateID');
        $property->agentID = $agentID;
        // Save the property to the database
        $property->save();

        // Get the ID of the newly created property
        $propertyID = $property->propertyID;

        // Handle facilities
        if ($request->has('facilities')) {
            $selectedFacilities = $request->input('facilities');
            foreach ($selectedFacilities as $facilityID) {
                $propertyFacility = new PropertyFacility();
                $propertyFacility->propertyID = $propertyID;
                $propertyFacility->facilityID = $facilityID;
                $propertyFacility->save();
            }
        }

        // Handle property photos (assuming you have a photos input field)
        // Upload property photos
        if ($request->hasFile('propertyPhotos')) {
            foreach ($request->file('propertyPhotos') as $photo) {
                $path = $photo->store('property-photos', 'public');
                $propertyPhoto = new PropertyPhoto();
                $propertyPhoto->propertyID = $property->propertyID;
                $propertyPhoto->propertyPath = $path;
                $propertyPhoto->dateUpload = now();
                $propertyPhoto->save();
            }
        }

        $agent = Agent::find($agentID);
        $wallet = $agent->wallet;
        // Create a walletTransaction record
        $walletTransaction = new WalletTransaction;
        $walletTransaction->transactionID = $this->generateUniqueTransactionID(); // Implement this function
        $walletTransaction->transactionType = 'Payment';
        $walletTransaction->transactionDate = now()->toDateString();
        $walletTransaction->transactionTime = now()->toTimeString();
        $walletTransaction->transactionAmount = 10;
        $walletTransaction->walletID = $wallet->walletID;
        $walletTransaction->save();

        $wallet->balance -= 10;
        $wallet->save();

        // Create the notification content
        $notificationContent = 'Property #' . $property->propertyID . ' ' . $property->propertyName .
            ' created successfully. ';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Property';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $agentID;

        // Save  notification
        $notification->save();

        $header = 'Property Creation Notification';
        $emailContent = 'Your property post.' . $property->propertyName . ' ID #' . $property->propertyID . ' has been created successfully.';

        $note = "If you need to make any updates or modifications, please log in to your account.";
        $desc = 'You are receiving this email to inform you about the successful creation of your property post.';

        $agentEmail = $property->agent->agentEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Property Creation Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        if (session('agent')) {
            return redirect()->route('properties')->with('success', 'Property Added Successfully');
        } else {
            return redirect()->route('properties,all')->with('success', 'Property Added Successfully');
        }
    }

    public function AllPropertyMap()
    {
        $i = 1;
        $properties = Property::where('propertyAvailability', 1)->select('propertyAddress')->get();
        foreach ($properties as $addresses) {
            $address = urlencode(trim($addresses->propertyAddress));
            $client = new Client();
            $response = $client->get("https://nominatim.openstreetmap.org/search?format=json&q={$address}");
            $result = json_decode($response->getBody(), true);
            if (!empty($result)) {
                $initialMarkers[] = [
                    'position' => [
                        'lat' => (double) $result[0]['lat'],
                        'lng' => (double) $result[0]['lon'],
                    ],
                    'label' => ['color' => 'white', 'text' => 'P' . $i],
                    'draggable' => false,
                    'address' => trim($addresses->propertyAddress),
                ];
            }
            $i++;
        }
        return $initialMarkers;
    }

    public function show($propertyID)
    {

        $property = Property::where('propertyID', $propertyID)
            ->first();
        $propertyAddress = $property->propertyAddress;

        $address = urlencode($propertyAddress);

        $client = new Client();
        $response = $client->get("https://nominatim.openstreetmap.org/search?format=json&q={$address}");


        $result = json_decode($response->getBody(), true);

        if (!empty($result)) {

            $latitude = (double) $result[0]['lat'];
            $longitude = (double) $result[0]['lon'];

            $initialMarkers[] = [
                'position' => [
                    'lat' => $latitude,
                    'lng' => $longitude,
                ],
                'label' => ['color' => 'white', 'text' => 'P1'],
                'draggable' => false,
                'address' => trim($propertyAddress),
            ];


        } else {
            $initialMarkers[] = null;
        }


        // Get Property Reviews Rating Count
        $veryGoodCount = Review::where('rating', 5)->where('reviewItemID', $propertyID)->count();
        $goodCount = Review::where('rating', 4)->where('reviewItemID', $propertyID)->count();
        $averageCount = Review::where('rating', 3)->where('reviewItemID', $propertyID)->count();
        $badCount = Review::where('rating', 2)->where('reviewItemID', $propertyID)->count();
        $veryBadCount = Review::where('rating', 1)->where('reviewItemID', $propertyID)->count();
        $totalCount = $veryGoodCount + $goodCount + $averageCount + $badCount + $veryBadCount;
        // Get Property Reviews
        $reviews = Review::where('ParentReviewID', null)
            ->with('agent', 'tenant')
            ->where('reviewItemID', $propertyID)
            ->orderBy('reviewID')
            ->get();

        $replies = Review::whereNotNull('ParentReviewID')
            ->with('agent', 'tenant')
            ->where('reviewItemID', $propertyID)
            ->orderBy('ParentReviewID')
            ->get();

        // Assuming you have relationships set up in your Property model
        $property = Property::with(['propertyPhotos', 'propertyFacilities'])->find($propertyID);

        if (!$property) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property not found');
        }

        // Assuming you have an agent relationship in your Property model
        $agent = $property->agent;
        // Increment the clicks attribute
        $property->increment('clicks');

        if (Auth::user() && strpos(Auth::user()->id, 'TNT') === 0) {
            $haveHistory = DB::table('search_histories')
                ->where('tenantID', Auth::user()->id)
                ->where('propertyID', $propertyID)
                ->first();

            if ($haveHistory) {
                // If there's existing search history, update it
                DB::table('search_histories')
                    ->where('tenantID', Auth::user()->id)
                    ->where('propertyID', $propertyID)
                    ->update([
                        'clickTime' => $haveHistory->clickTime + 1,
                        'updated_at' => Carbon::now(),
                    ]);
            } else {
                $latestSearch = SearchHistory::orderBy('searchID', 'desc')->first();
                $latestSearchID = $latestSearch ? $latestSearch->searchID : 'SRC0000000';
                $newSearchID = 'SRC' . str_pad((int) substr($latestSearchID, 3) + 1, 7, '0', STR_PAD_LEFT);
                DB::table('search_histories')->insert([
                    'searchID' => $newSearchID,
                    'searchDate' => Carbon::now(),
                    'clickTime' => '1',
                    'tenantID' => Auth::user()->id,
                    'propertyID' => $propertyID,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

        }

        return view(
            'tenant/propertyDetail',
            compact(
                'property',
                'agent',
                'veryGoodCount',
                'goodCount',
                'averageCount',
                'badCount',
                'veryBadCount',
                'totalCount',
                'reviews',
                'replies',
                'initialMarkers'
            )
        );
    }

    public function showAgent($propertyID)
    {
        $property = Property::where('propertyID', $propertyID)
            ->first();
        $propertyAddress = $property->propertyAddress;

        $address = urlencode($propertyAddress);

        $client = new Client();
        $response = $client->get("https://nominatim.openstreetmap.org/search?format=json&q={$address}");


        $result = json_decode($response->getBody(), true);

        if (!empty($result)) {

            $latitude = (double) $result[0]['lat'];
            $longitude = (double) $result[0]['lon'];

            $initialMarkers[] = [
                'position' => [
                    'lat' => $latitude,
                    'lng' => $longitude,
                ],
                'label' => ['color' => 'white', 'text' => 'P1'],
                'draggable' => false,
                'address' => trim($propertyAddress),
            ];


        } else {
            $initialMarkers[] = null;
        }

        // Assuming you have relationships set up in your Property model
        $property = Property::with(['propertyPhotos', 'propertyFacilities'])->find($propertyID);

        if (!$property) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property not found');
        }

        // Assuming you have an agent relationship in your Property model
        $agent = $property->agent;

        // Get Property Reviews Rating Count
        $veryGoodCount = Review::where('rating', 5)->where('reviewItemID', $propertyID)->count();
        $goodCount = Review::where('rating', 4)->where('reviewItemID', $propertyID)->count();
        $averageCount = Review::where('rating', 3)->where('reviewItemID', $propertyID)->count();
        $badCount = Review::where('rating', 2)->where('reviewItemID', $propertyID)->count();
        $veryBadCount = Review::where('rating', 1)->where('reviewItemID', $propertyID)->count();

        $totalCount = $veryGoodCount + $goodCount + $averageCount + $badCount + $veryBadCount;
        // Get Property Reviews
        $reviews = Review::where('ParentReviewID', null)
            ->with('agent', 'tenant')
            ->where('reviewItemID', $propertyID)
            ->orderBy('reviewID')
            ->get();

        $replies = Review::whereNotNull('ParentReviewID')
            ->with('agent', 'tenant')
            ->where('reviewItemID', $propertyID)
            ->orderBy('ParentReviewID')
            ->get();

        return view(
            'agent/propertyDetail',
            compact(
                'property',
                'agent',
                'veryGoodCount',
                'goodCount',
                'averageCount',
                'badCount',
                'veryBadCount',
                'totalCount',
                'reviews',
                'replies',
                'initialMarkers'
            )
        );
    }

    public function edit($propertyID)
    {
        $states = State::all();
        $facilities = Facility::all();
        // Assuming you have relationships set up in your Property model
        $property = Property::with(['propertyPhotos', 'propertyFacilities'])->find($propertyID);

        if (!$property) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property not found');
        }

        // Assuming you have an agent relationship in your Property model
        $agent = $property->agent;

        return view('agent/propertyUpdate', compact('property', 'agent', 'states', 'facilities'));
    }

    public function update(Request $request, $propertyID)
    {

        // Validate the form inputs
        $request->validate([
            'propertyName' => 'required|string|max:40',
            'propertyDesc' => 'required|string|max:150',
            'propertyType' => 'required|string|max:40|in:Residential apartment,House,Condominium,Commercial spaces',
            'housingType' => 'required|string|max:40',
            'roomType' => 'required|string|max:40',
            'propertyAddress' => 'required|string|max:100',
            'bedroomNum' => 'required|integer|min:0',
            'bathroomNum' => 'required|integer|min:0',
            'furnishingType' => 'required|string|max:50|in:Fully Furnished,Partial Furnished,Unfurnished',
            'rentalAmount' => 'required|numeric|min:1',
            'depositAmount' => 'required|numeric|min:0',
            'propertyAvailability' => 'required|in:0,1',
            'stateID' => 'required|exists:states,stateID',
            'facilities' => 'nullable|array',
            'facilities.*' => 'exists:facilities,facilityID',
            'propertyPhotos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Find the property by ID
        $property = Property::findOrFail($propertyID);

        // Update property details
        $property->update([
            'propertyName' => $request->input('propertyName'),
            'propertyDesc' => $request->input('propertyDesc'),
            'propertyType' => $request->input('propertyType'),
            'housingType' => $request->input('housingType'),
            'roomType' => $request->input('roomType'),
            'propertyAddress' => $request->input('propertyAddress'),
            'bedroomNum' => $request->input('bedroomNum'),
            'bathroomNum' => $request->input('bathroomNum'),
            'buildYear' => $request->input('buildYear'),
            'squareFeet' => $request->input('squareFeet'),
            'furnishingType' => $request->input('furnishingType'),
            'rentalAmount' => $request->input('rentalAmount'),
            'depositAmount' => $request->input('depositAmount'),
            'propertyAvailability' => $request->input('propertyAvailability'),
            'stateID' => $request->input('stateID'),
        ]);



        // Sync facilities
        if ($request->has('facilities')) {
            $selectedFacilities = $request->input('facilities');
            $property->facilities()->sync($selectedFacilities);
        } else {
            // If no facilities are selected, detach all existing facilities
            $property->facilities()->detach();
        }


        // Handle property photos (assuming you have a photos input field)
        // Upload new property photos
        if ($request->hasFile('propertyPhotos')) {
            foreach ($request->file('propertyPhotos') as $photo) {
                $path = $photo->store('property-photos', 'public');
                $propertyPhoto = new PropertyPhoto();
                $propertyPhoto->propertyID = $property->propertyID;
                $propertyPhoto->propertyPath = $path;
                $propertyPhoto->dateUpload = now();
                $propertyPhoto->save();
            }
        }

        if (session('agent')) {
            // Create the notification content
            $notificationContent = 'Property #' . $property->propertyID .' '. $property->propertyName . ' updated successfully. ';

            // Create a notification record
            $notification = new Notification();
            $notification->notificationID = $this->generateUniqueNotificationID();
            $notification->subject = 'Property Update';
            $notification->content = $notificationContent;
            $notification->timestamp = now();
            $notification->status = 'Unread';
            $notification->userID = $property->agentID; // Assuming you have the agent's ID

            // Save notification
            $notification->save();

            $header = 'Property Update Notification';
            $emailContent = 'Your property post for ' . $property->propertyName . ' ID #' . $property->propertyID . ' has been updated successfully.';

            $note = "If you need to make any further updates or modifications, please log in to your account.";
            $desc = 'You are receiving this email to inform you about the successful update of your property post.';

            $agentEmail = $property->agent->agentEmail; // Assuming you have the agent's email

            try {
                $imagePath = public_path('storage/images/logo.png');

                Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                    $message->to($agentEmail);
                    $message->subject('Property Update Notification');
                });
            } catch (TransportExceptionInterface $e) {
                dd($e);
                // Handle the exception or log the error
            }

            return redirect()->route('properties')->with('success', 'Property Updated Successfully');
        } else {
            // Create the notification content
            $notificationContent = 'Property #' . $property->propertyID . ' ' . $property->propertyName . ' has been updated by admin. ';

            // Create a notification record
            $notification = new Notification();
            $notification->notificationID = $this->generateUniqueNotificationID();
            $notification->subject = 'Property Update';
            $notification->content = $notificationContent;
            $notification->timestamp = now();
            $notification->status = 'Unread';
            $notification->userID = $property->agentID; // Assuming you have the agent's ID

            // Save notification
            $notification->save();

            $header = 'Property Update Notification';
            $emailContent = 'Your property post for ' . $property->propertyName . ' ID #' . $property->propertyID . ' has been updated by administrator.';

            $note = "If you need to make any further updates or modifications, please log in to your account.";
            $desc = 'You are receiving this email to inform you about the successful update of your property post.';

            $agentEmail = $property->agent->agentEmail; // Assuming you have the agent's email

            try {
                $imagePath = public_path('storage/images/logo.png');

                Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                    $message->to($agentEmail);
                    $message->subject('Property Update Notification');
                });
            } catch (TransportExceptionInterface $e) {
                dd($e);
                // Handle the exception or log the error
            }

            return redirect()->route('properties.all')->with('success', 'Property Updated Successfully');

        }
    }



    public function destroy(string $id)
    {
        // Find the property by ID
        $property = Property::findOrFail($id);

        // Check if there are transactions associated with the property
        if ($property->propertyRental()->exists()) {
            return redirect()->back()->with('error', 'Cannot delete property with associated transactions.');
        }
        // Create the notification content
        $notificationContent = 'Property ' . $property->propertyID . ' ' . $property->propertyName . ' deleted successfully. ';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Property Deletion';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $property->agentID; // Assuming you have the agent's ID

        // Save notification
        $notification->save();

        $header = 'Property Deletion Notification';
        $emailContent = 'Your property post for ' . $property->propertyName . ' ID #' . $property->propertyID . ' has been deleted successfully.';

        $note = "If you have any concerns or need assistance, please log in to your account.";
        $desc = 'You are receiving this email to inform you about the successful deletion of your property post.';

        $agentEmail = $property->agent->agentEmail; // Assuming you have the agent's email

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Property Deletion Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }
        // Delete related photos
        $property->propertyPhotos()->delete();

        // Delete related facilities
        $property->propertyFacilities()->delete();

        // Finally, delete the property
        $property->delete();

        if (session('agent')) {
            return redirect()->route('properties')->with('success', 'Property Added Successfully');
        } else {
            return redirect()->route('properties.all')->with('success', 'Property Deleted Successfully');
        }
    }

    public function apply(string $id)
    {
        // Find the property by ID
        $property = Property::find($id);

        // Check if the property exists
        if (!$property) {
            abort(404, 'Property not found');
        }

        $tenantID = optional(session('tenant'))->tenantID;
        $tenant = Tenant::find($tenantID);
        return view('tenant/propertyApply', compact('tenant', 'property'));
    }

    public function submitApplication(string $id)
    {
        // Find the property by ID
        $property = Property::find($id);

        // Check if the property exists
        if (!$property) {
            abort(404, 'Property not found');
        }
        $tenantID = optional(session('tenant'))->tenantID;
        $propertyRental = new PropertyRental();
        $propertyRental->propertyRentalID = $this->generateUniquePropertyRentalID();
        $propertyRental->propertyID = $id;
        $propertyRental->tenantID = $tenantID;
        $propertyRental->date = now();
        $propertyRental->rentStatus = "Applied";

        // Save the propertyRental
        $propertyRental->save();

        // Create the notification content
        $notificationContent = 'Property #' . $propertyRental->property->propertyID . ' ' . $propertyRental->property->propertyName .
            ' application created successfully. ';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Property';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $tenantID;

        // Save  notification
        $notification->save();

        // Create the notification content
        $notificationContent = 'Tenant' . $propertyRental->tenant->tenantName . ' [ #' . $tenantID . ' ] had submitted a rental application for your property ' . $propertyRental->property->propertyName.'.';

        // Create a notification record
        $notification = new Notification();
        $notification->notificationID = $this->generateUniqueNotificationID();
        $notification->subject = 'Rental Application';
        $notification->content = $notificationContent;
        $notification->timestamp = now();
        $notification->status = 'Unread';
        $notification->userID = $propertyRental->property->agent->agentID;

        // Save the notification
        $notification->save();

        // Send email to the tenant
        $tenantEmail = $propertyRental->tenant->tenantEmail;
        $header = 'Notification for Rental Application';
        $emailContent = 'Your rental application for property ' . $propertyRental->property->propertyName . ' [ ' . $propertyRental->property->propertyID . ' ] has been submitted successfully.';
        $note = 'Thank you for using our platform.';
        $desc = 'You are receiving this email as a notification for your rental application.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Notification for Rental Application');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        $header = 'Rental Application Notification';
        $emailContent = 'A rental application for your property .' . $propertyRental->property->propertyName . ' with ID #' . $propertyRental->property->propertyID . ' has been submitted by tenant.' . $propertyRental->tenant->tenantName . ' . ';

        $note = "Please log in to your account to review and respond to the application.";
        $desc = 'You are receiving this email to inform you about the new rental application for your property.';

        $agentEmail = $propertyRental->property->agent->agentEmail;

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($agentEmail) {
                $message->to($agentEmail);
                $message->subject('Rental Application Notification');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }

        return redirect()->route('applicationIndex')
        ->with('success', ' Your application was submitted successfully. Your agent will approach you soon.');
       
    }


    public function approve($id)
    {
        // Assuming you have relationships set up in your Property model
        $propertyRental = PropertyRental::find($id);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }

        // Assuming you have an agent relationship in your Property model
        $propertyRental->rentStatus = "Approved";
        $propertyRental->save();

        // Create the notification content
        $notificationContent = 'Property #' . $propertyRental->property->propertyID . ' ' . $propertyRental->property->propertyName .
            ' application approved. ';

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

        // Send email to the tenant
        $tenantEmail = $propertyRental->tenant->tenantEmail;
        $header = 'Notification: Rental Application Approved';
        $emailContent = 'Congratulations! Your rental application for property [ ' . $propertyRental->property->propertyID . '] ' . $propertyRental->property->propertyName . ' has been approved on ' . now() . '.';
        $note = 'Thank you for choosing our platform.';
        $desc = 'You are receiving this email as a notification for the approval of your rental application.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Notification: Rental Application Approved');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }
        if (session('agent')) {
            return redirect()->route('properties')
                ->with('success', 'Tenant application approved successfully!');
        } else {
            return redirect()->route('properties.all')
                ->with('success', 'Tenant application approved successfully!');

        }

    }


    public function reject($id)
    {
        // Assuming you have relationships set up in your Property model
        $propertyRental = PropertyRental::find($id);

        if (!$propertyRental) {
            // Handle property not found, redirect or show an error view
            abort(404, 'Property Rental not found');
        }

        // Assuming you have an agent relationship in your Property model
        $propertyRental->rentStatus = "Rejected";
        $propertyRental->save();
        // Create the notification content
        $notificationContent = 'Property #' . $propertyRental->property->propertyID . ' ' . $propertyRental->property->propertyName .
            ' application rejected. ';

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

        // Send email to the tenant
        $tenantEmail = $propertyRental->tenant->tenantEmail;
        $header = 'Notification: Rental Application Rejected';
        $emailContent = 'We regret to inform you that your rental application for property [ ' . $propertyRental->property->propertyID . '] ' . $propertyRental->property->propertyName . ' has been rejected.';
        $note = 'Thank you for considering our platform.';
        $desc = 'You are receiving this email as a notification for the rejection of your rental application.';

        try {
            $imagePath = public_path('storage/images/logo.png');

            Mail::send('emailContent', ['imagePath' => $imagePath, 'emailContent' => $emailContent, 'header' => $header, 'desc' => $desc, 'note' => $note], function ($message) use ($tenantEmail) {
                $message->to($tenantEmail);
                $message->subject('Notification: Rental Application Rejected');
            });
        } catch (TransportExceptionInterface $e) {
            dd($e);
            // Handle the exception or log the error
        }
        if (session('agent')) {
            return redirect()->route('properties')
                ->with('success', 'Tenant application rejected successfully!');
        } else {
            return redirect()->route('properties.all')
                ->with('success', 'Tenant application rejected successfully!');
        }
    }



    public function applicationIndex()
    {

        $tenantID = optional(session('tenant'))->tenantID;
        $tenant = Tenant::find($tenantID);

        if (!$tenant) {
            // Handle tenant not found, redirect or show an error view
            abort(404, 'Tenant not found');
        }

        $propertyRentals = $tenant->propertyRentals()->paginate(10);

        // Retrieve property rentals with the desired rentStatus values
        $propertyApplications = $tenant->propertyRentals()
            ->whereIn('rentStatus', ['Approved', 'Applied'])
            ->paginate(10);

        // Retrieve property rentals with the desired rentStatus values
        $paymentHistory = $tenant->propertyRentals()
            ->whereIn('rentStatus', ['Paid', 'Refund requested', 'Refund approved', 'Refund rejected', 'Completed'])
            ->paginate(10);

        return view('tenant/propertyRentApplication', compact('propertyRentals', 'propertyApplications', 'paymentHistory'));
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');

        $agentID = optional(session('agent'))->agentID;


        $properties = Property::where(function ($query) use ($searchTerm) {
            $query->where('propertyName', 'like', '%' . $searchTerm . '%')
                ->orWhere('propertyType', 'like', '%' . $searchTerm . '%')
                ->orWhere('propertyAddress', 'like', '%' . $searchTerm . '%');
        })
            ->whereHas('agent', function ($query) use ($agentID) {
                // This ensures that only properties with an associated agent matching $agentID will be retrieved
                $query->where('agentID', $agentID);
            })
            ->with('propertyRental') // Eager load property rentals
            ->paginate(5);

        $propertyRentals = PropertyRental::whereHas('property', function ($query) use ($searchTerm, $agentID) {
            $query->where('agentID', $agentID)
                ->where(function ($innerQuery) use ($searchTerm) {
                    $innerQuery->where('propertyName', 'like', '%' . $searchTerm . '%')
                        ->orWhere('propertyType', 'like', '%' . $searchTerm . '%')
                        ->orWhere('propertyAddress', 'like', '%' . $searchTerm . '%');
                });
        })
            ->paginate(5);

        return view('agent/propertyIndex', compact('propertyRentals', 'properties','searchTerm'));
    }

    public function searchAll(Request $request)
    {
        // Retrieve the search term from the request
        $searchTerm = $request->input('search');

        // Perform the search on the properties table
        $properties = Property::where(function ($query) use ($searchTerm) {
            $query->where('propertyName', 'like', '%' . $searchTerm . '%')
                ->orWhere('propertyType', 'like', '%' . $searchTerm . '%')
                ->orWhere('propertyAddress', 'like', '%' . $searchTerm . '%');
        })->paginate(5);

        $propertyRentals = PropertyRental::whereHas('property', function ($query) use ($searchTerm) {
            $query->where('propertyName', 'like', '%' . $searchTerm . '%')
                ->orWhere('propertyType', 'like', '%' . $searchTerm . '%')
                ->orWhere('propertyAddress', 'like', '%' . $searchTerm . '%');
        })
            ->paginate(5);

        
            $paymentHistory = PropertyRental::whereIn('rentStatus', ['Paid', 'Refund requested', 'Refund approved', 'Refund rejected', 'Completed'])
                ->whereHas('property', function ($propertyQuery) use ($searchTerm) {
                    $propertyQuery->where('propertyName', 'like', '%' . $searchTerm . '%')
                        ->orWhere('propertyType', 'like', '%' . $searchTerm . '%')
                        ->orWhere('propertyAddress', 'like', '%' . $searchTerm . '%');
                })
                ->paginate(5);
            

            return view('admin/propertyIndex', compact('propertyRentals', 'properties','paymentHistory','searchTerm'));
    }

    function generateUniquePropertyID()
    {
        // Get the latest property ID from the database
        $latestProperty = Property::orderBy('propertyID', 'desc')->first();

        // Extract the numeric part and increment it
        if ($latestProperty) {
            $lastID = ltrim(substr($latestProperty->propertyID, 3), '0'); // Remove the "R" prefix and leading zeros
            $nextID = 'PRO' . str_pad($lastID + 1, 7, '0', STR_PAD_LEFT); // Increment and pad to 7 digits
        } else {
            // If no property rental exists yet, start with R0000001
            $nextID = 'PRO0000001'; // Initial ID
        }
        return $nextID;
    }

    function generateUniquePropertyRentalID()
    {
        // Get the latest property ID from the database
        $latestPropertyRental = PropertyRental::orderBy('propertyRentalID', 'desc')->first();

        // Extract the numeric part and increment it
        // Generate the new property ID with leading zeros
        if ($latestPropertyRental) {
            $lastID = ltrim(substr($latestPropertyRental->propertyRentalID, 1), '0'); // Remove the "R" prefix and leading zeros
            $nextID = 'R' . str_pad($lastID + 1, 9, '0', STR_PAD_LEFT); // Increment and pad to 7 digits
        } else {
            // If no property rental exists yet, start with R0000001
            $nextID = 'R000000001'; // Initial ID
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

    public function HomeSearch(Request $request)
    {

        // Get all properties by default
        $properties = Property::query()->where('propertyAvailability', 1)
            ->where('expiredDate', '>', now());

        $initialMarkers = $this->AllPropertyMap();
        // Check if search parameters are present
        if ($request->filled('location')) {
            $properties->where('propertyAddress', 'like', '%' . $request->input('location') . '%');
        }

        if ($request->filled('propertyType')) {
            $properties->where('propertyType', $request->input('propertyType'));
        }

        if ($request->filled('pricing')) {
            // Adjust this part based on how your pricing is stored in the database
            // For example, assuming there is a 'price' column in the 'properties' table
            switch ($request->input('pricing')) {
                case 'low':
                    $properties->whereBetween('rentalAmount', [200, 400]);
                    break;
                case 'medium':
                    $properties->whereBetween('rentalAmount', [401, 600]);
                    break;
                case 'high':
                    $properties->whereBetween('rentalAmount', [601, 800]);
                    break;
                case 'very high':
                    $properties->where('rentalAmount', '>', 800);
                    break;
            }
        }

        if ($request->filled('state')) {
            $properties->where('stateID', $request->input('state'));
        }

        if ($request->filled('bedrooms')) {
            $properties->where('bedroomNum', $request->input('bedrooms'));
        }

        $properties= $properties->paginate(15);
        
      
      
        foreach ($properties as $property) {
            // Assuming there is a relationship between Property and Rating, and Rating has a 'rating' column
            $averageRating = Review::where('reviewItemID', $property->propertyID)->avg('rating');
            $totalRating = Review::where('reviewItemID', $property->propertyID)->count('reviewID');

            // Add the average rating and total rating to the property object
            $property->average_rating = $averageRating * 2;
            $property->total_reviews = $totalRating;
        }

        $filters = Filter::all();

        return view('tenant/propertyIndex', compact('properties', 'filters', 'initialMarkers'));
    }
    public function AdvancedFilter(Request $request)
    {


        $filteredResults = Property::where('propertyAvailability', 1);

        $location = $request->input('location');
        $propertyType = $request->input('propertyType');
        $minPrice = $request->input('minPrice');
        $maxPrice = $request->input('maxPrice');
        $roomType = $request->input('roomType');
        $minBeds = $request->input('minBeds');
        $maxBeds = $request->input('maxBeds');
        $bathroomCount = $request->input('bathroomCount');
        $minFloorSize = $request->input('minFloorSize');
        $maxFloorSize = $request->input('maxFloorSize');
        $minBuildYear = $request->input('minBuildYear');
        $maxBuildYear = $request->input('maxBuildYear');
        $furnishingType = $request->input('Furnishing');
        $postedDate = $request->input('postedDate');
        $selectedPropertyTypes = $request->input('selectedPropertyType');

        if (!empty($selectedPropertyTypes)) {
            $filteredResults->whereIn('housingType', $selectedPropertyTypes);
        }
        if ($request->filled('location')) {
            $filteredResults->where('propertyAddress', 'like', '%' . $request->input('location') . '%');
        }


        if (!empty($propertyType) && $propertyType != 'All') {
            $filteredResults->where('propertyType', $propertyType);
        }


        if (!empty($roomType)) {
            $filteredResults->where('roomType', $roomType);
        }


        if (!empty($bathroomCount)) {
            $filteredResults->where('bathroomNum', $bathroomCount);
        }

        if (!empty($minPrice)) {
            $filteredResults->where('rentalAmount', '>=', $minPrice);
        }

        if (!empty($maxPrice)) {
            $filteredResults->where('rentalAmount', '<=', $maxPrice);
        }

        // Build Year
        if (!empty($minBuildYear)) {
            $filteredResults->where('buildYear', '>=', $minBuildYear);
        }

        if (!empty($maxBuildYear)) {
            $filteredResults->where('buildYear', '<=', $maxBuildYear);
        }

        // Square Feet
        if (!empty($minFloorSize)) {
            $filteredResults->where('squareFeet', '>=', $minFloorSize);
        }

        if (!empty($maxFloorSize)) {
            $filteredResults->where('squareFeet', '<=', $maxFloorSize);
        }

        // Bedroom Number
        if (!empty($minBeds)) {
            $filteredResults->where('bedroomNum', '>=', $minBeds);
        }

        if (!empty($maxBeds)) {
            $filteredResults->where('bedroomNum', '<=', $maxBeds);
        }


        if (!empty($furnishingType)) {
            $filteredResults->where('furnishingType', $furnishingType);
        }


        // Handle posted date filter
        if (!empty($postedDate) && $postedDate != 'All') {
            if ($postedDate === 'Within 3 Days') {
                $filteredResults->where('created_at', '>=', Carbon::now()->subDays(3));
            } elseif ($postedDate === 'Within 1 Week') {
                $filteredResults->where('created_at', '>=', Carbon::now()->subWeek());
            } elseif ($postedDate === 'Within 2 Weeks') {
                $filteredResults->where('created_at', '>=', Carbon::now()->subWeeks(2));
            } elseif ($postedDate === 'Within 1 month') {
                $filteredResults->where('created_at', '>=', Carbon::now()->subMonth());
            }
        }

        $properties = $filteredResults->paginate(15);

        foreach ($properties as $property) {
            // Assuming there is a relationship between Property and Rating, and Rating has a 'rating' column
            $averageRating = Review::where('reviewItemID', $property->propertyID)->avg('rating');
            $totalRating = Review::where('reviewItemID', $property->propertyID)->count('reviewID');

            // Add the average rating and total rating to the property object
            $property->average_rating = $averageRating * 2;
            $property->total_reviews = $totalRating;
        }
        $filters = Filter::all();
        $initialMarkers = $this->AllPropertyMap();
        return view('tenant/propertyIndex', compact('properties', 'filters', 'initialMarkers'));

    }

    public function SortProperty(Request $request)
    {
        $selectedSort = $request->input('sort_by');
        $properties = $request->input('properties');
        $decodedProperties = json_decode($properties, true);
        $data = $decodedProperties['data'];
        $collection = collect($data);
        $initialMarkers = $this->AllPropertyMap();
        // Perform sorting based on the selected sort option
        if ($selectedSort == 'latest') {

            // Sort by latest
            $sortedCollection = $collection->sortByDesc('created_at')->values();

        } elseif ($selectedSort == 'pricing') {
            // Sort by pricing
            $sortedCollection = $collection->sortBy('rentalAmount')->values();
        } elseif ($selectedSort == 'Epricing') {
            // Sort by pricing
            $sortedCollection = $collection->sortByDesc('rentalAmount')->values();
        } elseif ($selectedSort == 'oldest') {
            // Sort by pricing
            $sortedCollection = $collection->sortBy('created_at')->values();
        } elseif ($selectedSort == 'Hrating') {

            $sortedCollection = $collection->sortByDesc('average_rating')->values();
        } elseif ($selectedSort == 'Lrating') {

            $sortedCollection = $collection->sortBy('average_rating')->values();
        }

        // Convert sorted collection to Property models
        $propertyModels = $sortedCollection->map(function ($property) {
            return new Property($property);
        });

        // Get property IDs
        $propertyIds = $propertyModels->pluck('propertyID')->toArray();

        // Eager load the propertyPhotos relationship for the existing models
        Property::whereIn('propertyID', $propertyIds)->with('propertyPhotos')->get()->each(function ($property) use ($propertyModels) {
            // Find the corresponding model in the sorted collection
            $model = $propertyModels->where('propertyID', $property->propertyID)->first();

            // Load the propertyPhotos relationship on the model
            $model->setRelation('propertyPhotos', $property->propertyPhotos);
        });

        // Paginate the Property models
        $perPage = 15;
        $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1;
        $currentPageItems = $propertyModels->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $propertiesPaginated = new LengthAwarePaginator($currentPageItems, count($propertyModels), $perPage, $currentPage);

        foreach ($propertiesPaginated as $property) {
            // Assuming there is a relationship between Property and Rating, and Rating has a 'rating' column
            $averageRating = Review::where('reviewItemID', $property->propertyID)->avg('rating');
            $totalRating = Review::where('reviewItemID', $property->propertyID)->count('reviewID');

            // Add the average rating and total rating to the property object
            $property->average_rating = $averageRating * 2;
            $property->total_reviews = $totalRating;
        }
        $filters = Filter::all();

        // Pass $propertiesPaginated to your view
        return view('tenant/propertyIndex', ['properties' => $propertiesPaginated, 'filters' => $filters , 'initialMarkers' => $initialMarkers]);
    }
}