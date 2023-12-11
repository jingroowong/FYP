<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agentID = optional(session('agent'))->agentID;
        $notifications = Notification::where('userID', $agentID)->orderBy('timestamp', 'desc')->paginate(10);
        $count = Notification::where('userID', $agentID)->count();

        return view('agent/notificationIndex', compact('notifications', 'count'));
    }

    public function show($id)
    {
        $tenantID = optional(session('tenant'))->tenantID;
        $notifications = Notification::where('userID', $tenantID)->orderBy('timestamp', 'desc')->limit(5)->get();
        // Dump and die to inspect $notifications

        return response()->json($notifications);

    }

    public function getLatestNotifications()
    {
        $tenantID = optional(session('tenant'))->tenantID;
        $notifications = Notification::where('userID', $tenantID)->orderBy('timestamp', 'desc')->limit(5)->get();
        // Dump and die to inspect $notifications

        return response()->json($notifications);
    }


    public function tenantIndex()
    {
        $tenantID = optional(session('tenant'))->tenantID;
        $notifications = Notification::where('userID', $tenantID)->orderBy('timestamp', 'desc')->paginate(10);
        $count = Notification::where('userID', $tenantID)->count();

        return view('tenant/notificationIndex', compact('notifications', 'count'));
    }

    public function agentSearch(Request $request)
    {
        $agentID = optional(session('agent'))->agentID;

        $searchTerm = $request->input('search');

        // Perform the search query based on your criteria
        $notifications = Notification::where('userID', $agentID)
            ->where(function ($query) use ($searchTerm) {
                $query->where('subject', 'like', '%' . $searchTerm . '%')
                    ->orWhere('content', 'like', '%' . $searchTerm . '%');
            })
            ->orderBy('timestamp', 'desc')
            ->paginate(10);
        $count = $notifications->count();

        // Return the search results
        return view('agent/notificationIndex', compact('notifications', 'count', 'searchTerm'));
    }

    public function tenantSearch(Request $request)
    {
        $tenantID = optional(session('tenant'))->tenantID;

        $searchTerm = $request->input('search');

        // Perform the search query based on your criteria
        $notifications = Notification::where('userID', $tenantID)
            ->where(function ($query) use ($searchTerm) {
                $query->where('subject', 'like', '%' . $searchTerm . '%')
                    ->orWhere('content', 'like', '%' . $searchTerm . '%');
            })
            ->orderBy('timestamp', 'desc')
            ->paginate(10);

        $count = $notifications->count();

        // Return the search results
        return view('tenant/notificationIndex', compact('notifications', 'count', 'searchTerm'));
    }

    public function delete(Request $request)
    {
        $notificationIDs = $request->input('notification');
        if ($notificationIDs) {
            // Delete the selected notifications
            $deletedCount = Notification::whereIn('notificationID', $notificationIDs)->delete();
        } else {
            $deletedCount = 0;
        }
        $successMessage = ($deletedCount > 0)
            ? "Successfully deleted $deletedCount notification(s)."
            : "No notifications were deleted.";

        return redirect()->back()->with('success', $successMessage);
    }


    public function pricing()
    {
        return view('agent/footerPricing');
    }

    public function faq()
    {
        return view('agent/footerFAQ');
    }

    public function home()
    {
        return view('agent/footerHome');
    }

    public function about()
    {
        return view('agent/footerAboutUs');
    }

    public function feature()
    {
        return view('agent/footerFeature');
    }

}
