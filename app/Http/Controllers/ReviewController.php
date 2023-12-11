<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function ViewPropertyDetails()
    {
        $veryGoodCount = Review::where('rating', 5)->count();
        $goodCount = Review::where('rating', 4)->count();
        $averageCount = Review::where('rating', 3)->count();
        $badCount = Review::where('rating', 2)->count();
        $veryBadCount = Review::where('rating', 1)->count();
        $reviews = Review::whereNull('ParentReviewID')
        ->orderBy('reviewDate', 'asc') 
        ->get();
        $replies = Review::whereNotNull('ParentReviewID')
        ->orderBy('ParentReviewID')
        ->get();
    
    
    
        $totalCount = $veryGoodCount + $goodCount + $averageCount + $badCount + $veryBadCount;


        return view('tenant.RentPropertiesDetails', compact('reviews','replies', 'veryGoodCount', 'goodCount', 'averageCount', 'badCount', 'veryBadCount', 'totalCount'));

    }
    
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function AddReview(Request $request)
    {
     
      if($request->input('reviewerID') == ''){
        return redirect()->back()->with('error', 'Enable to comment... Please login first');
      }
        $request->validate([
            'add_rating' => 'required|integer|between:1,5', 
            'comment' => 'required|max:200', 
        ]);
    
        
        $review = new Review;
    
        $latestReview = Review::orderBy('reviewID', 'desc')->first();
        $latestReviewID = $latestReview ? $latestReview->reviewID : 'RVW0000000';

        
        $newReviewID = 'RVW' . str_pad((int)substr($latestReviewID, 3) + 1, 7, '0', STR_PAD_LEFT);

        $review->reviewID =$newReviewID ;
        $review->rating = $request->input('add_rating');
        $review->comment = $request->input('comment');
        $review->reviewItemID =  $request->input('reviewItemID');
        $review->reviewerID = $request->input('reviewerID');
        $review->reviewDate = Carbon::now();
       
        $review->save();
    
        return redirect()->back()->with('success', 'Sucessful Added, the review has been added by you.');

    }

    public function ReplyReview(Request $request)
    {
        
        if($request->input('reviewerID') == ''){
            return redirect()->back()->with('error', 'Enable to comment... Please login first');
          }
      
        $request->validate([
            'rating' => 'required|integer|between:1,5', 
            'reply' => 'required|max:200', 
        ]);
    
        
        $review = new Review;
    
        $latestReview = Review::orderBy('reviewID', 'desc')->first();
        $latestReviewID = $latestReview ? $latestReview->reviewID : 'RVW0000000';

        
        $newReviewID = 'RVW' . str_pad((int)substr($latestReviewID, 3) + 1, 7, '0', STR_PAD_LEFT);

        $review->reviewID =$newReviewID ;
        $review->rating = $request->input('rating');
        $review->comment = $request->input('reply');
        $review->ParentReviewID = $request->input('ParentReviewID');
        $review->reviewItemID =  $request->input('reviewItemID');
        $review->reviewerID = $request->input('reviewerID');
        $review->reviewDate = Carbon::now();
       
        $review->save();
    
        return redirect()->back()->with('success', 'Sucessful Added, the review has been added by you.');


    }

    public function getUserReviews(Request $request)
{

    session(['dynamicContent' => 'reviews']);
    
    $tenantID = $request->input('tenantID');

    
    $userReviews = Review::where('reviewerID', '')->get();

    return view('tenant/ViewMyTenantAccount', ['userReviews' => $userReviews]);
}
    
public function DeleteReviews(Request $request){
    $request->validate([
        'reviewID' => 'required|string', 
    ]);

    $reviewID = $request->input('reviewID');

  
    Review::where('reviewID', $reviewID)
        ->orWhere('ParentReviewID', $reviewID)
        ->delete();

    

    return redirect()->back()->with('success', 'Review deleted successfully');
}

public function EditReviews(Request $request){
  
    $rules = [
        'editedComment' => 'required|max:200',
    ];

   
    $messages = [
        'editedComment.max' => 'Comment is too long. Please keep it under 200 characters.',
    ];

   
    $validator = Validator::make($request->all(), $rules, $messages);

   
    if ($validator->fails()) {
        return redirect()->back()->with(['update-review-error' => $validator]);
    }

   
    $reviewID = $request->input('editReviewID');
    $editedComment = $request->input('editedComment');

    $review = Review::where('reviewID', $reviewID)->first();

    
    $review->comment = $editedComment;
    $review->save();

    return redirect()->back()->with(['success' => 'Review edited successfully.']);
}
}
