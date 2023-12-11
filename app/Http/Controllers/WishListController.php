<?php

namespace App\Http\Controllers;
use App\Models\Wishlist;
use App\Models\Property;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class WishListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function ViewWishList(String $id)
    {
        $wishlists = Property::join('wishlists', 'properties.propertyID', '=', 'wishlists.propertyID')
        ->leftJoin('states', 'properties.stateID', '=', 'states.stateID')
        ->leftJoin('agents', 'properties.agentID', '=', 'agents.agentID') // Left join with agents table
        ->where('wishlists.tenantID', $id)
        ->select(
            'properties.*',
            'wishlists.*',
            'states.stateName', // Select the stateName
            'agents.*', // Select all columns from agents table
            DB::raw('(SELECT propertyPath FROM property_photos WHERE propertyID = properties.propertyID ORDER BY created_at DESC LIMIT 1) as propertyPhotoPath'),
            DB::raw('(SELECT count(*) FROM property_photos WHERE properties.propertyID = property_photos.propertyID) as photos_count'),
            DB::raw('(SELECT ROUND(AVG(rating), 1) FROM reviews WHERE reviewItemID = agents.agentID) as agentAverageRating'),
            DB::raw('(SELECT ROUND(AVG(rating), 1) FROM reviews WHERE reviewItemID = properties.propertyID) as propertyAverageRating'),
        )
        ->orderBy('wishlists.dateAdded', 'desc')
        ->paginate(15);
     
        return view('tenant/WishList',['wishlists'=>$wishlists]);
    }

    public function deleteSelected(Request $request)
    {
        
        $selectedWishlists = $request->input('selectedItems');
       
       
        foreach ($selectedWishlists as $wishlist) {
            $wishlistData = json_decode($wishlist, true);

        
        Wishlist::where('propertyID', $wishlistData['propertyID'])
            ->where('tenantID', $wishlistData['tenantID'])
            ->delete();
        
        }

        return redirect()->back()->with('success', 'Selected wishlists deleted successfully.');
    }
    /**
     * Display the specified resource.
     */
    public function ViewCompareList()
    {
        return view('tenant/CompareWishList');
    }

    public function getWishlist(String $id)
{
    $result = Property::join('wishlists', 'properties.propertyID', '=', 'wishlists.propertyID')
        ->leftJoin('states', 'properties.stateID', '=', 'states.stateID')
        ->leftJoin('agents', 'properties.agentID', '=', 'agents.agentID')
        ->where('wishlists.tenantID', $id)
        ->select(
            'properties.*',
            'wishlists.*',
            'states.stateName',
            'agents.*',
            DB::raw('(SELECT propertyPath FROM property_photos WHERE propertyID = properties.propertyID ORDER BY created_at DESC LIMIT 1) as propertyPhotoPath'),
            DB::raw('(SELECT count(*) FROM property_photos WHERE properties.propertyID = property_photos.propertyID) as photos_count'),
            DB::raw('(SELECT ROUND(AVG(rating), 1) FROM reviews WHERE reviewItemID = agents.agentID) as agentAverageRating'),
            DB::raw('(SELECT ROUND(AVG(rating), 1) FROM reviews WHERE reviewItemID = properties.propertyID) as propertyAverageRating'),
        )
        ->orderBy('wishlists.dateAdded', 'desc')
        ->get();

    // Retrieve concatenated facilities for each property
    $propertyIDs = $result->pluck('propertyID');
    $facilities = Property::selectRaw('properties.propertyID, GROUP_CONCAT(facilities.facilityName) AS facilityName')
        ->join('property_facilities', 'properties.propertyID', '=', 'property_facilities.propertyID')
        ->join('facilities', 'property_facilities.facilityID', '=', 'facilities.facilityID')
        ->whereIn('properties.propertyID', $propertyIDs)
        ->groupBy('properties.propertyID')
        ->get();

    // Merge the facilities into the $result collection
    $result = $result->map(function ($item) use ($facilities) {
        $propertyFacilities = $facilities->where('propertyID', $item->propertyID)->first();
        $item->facilityName = $propertyFacilities ? $propertyFacilities->facilityName : "";
        return $item;
    });

    return response()->json($result);
}

        
    

    

    public function ToggleWishList(Request $request)
    {
      
       $tenantID = $request->input('tenantID');
       $propertyID = $request->input('propertyID');

      $wishlistItem = Wishlist::where('tenantID', $tenantID)
            ->where('propertyID', $propertyID)
            ->first();

       
        if ($wishlistItem) {

            Wishlist::where('propertyID', $propertyID)
            ->where('tenantID', $tenantID)
            ->delete();

            $message = 'You have successful removed the properties from your wishlist.';
        } else {
            Wishlist::create([
                'tenantID' => $tenantID,
                'propertyID' => $propertyID,
                'dateAdded' => Carbon::now(),
            ]);
            $message = 'You have successful added the properties to your wishlist.';
        }

        return redirect()->back()->with('message', $message);
    }

    

    public function getWishlistData($propertyID, $tenantID) {

       
        $wishlist = Wishlist::where('propertyID', $propertyID)
                            ->where('tenantID', $tenantID)
                            ->first();

        return $wishlist;
    }

}
