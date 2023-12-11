<?php

namespace App\Http\Controllers;
use App\Models\Agent;
use App\Models\Timeslot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
class TimeslotController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $agentID = optional(session('agent'))->agentID;
        $agent = Agent::find($agentID);

        $timeslots = $agent->timeslots()->orderBy('timeslotID', 'desc')->paginate(10);
        return view('agent/timeslotIndex', compact('timeslots'));
    }

    public function calendar()
    {
        $agentID = optional(session('agent'))->agentID;
        $agent = Agent::find($agentID);

        $timeslots = $agent->timeslots()->orderBy('timeslotID', 'desc')->get();
        return view('agent/timeslotCalendar', compact('timeslots'));
    }

    public function getTimeslots(Request $request)
    {
        // Retrieve the week from the request
        $week = $request->input('week');

        // Parse the ISO week date to get the start and end dates of the week
        $startOfWeek = Carbon::parse($week)->startOfWeek();
        $endOfWeek = Carbon::parse($week)->endOfWeek();

        $agentID = optional(session('agent'))->agentID;
        $agent = Agent::find($agentID);

      
     // Fetch timeslots for the given week
     $timeslots = $agent->timeslots()
     ->whereBetween('date', [$startOfWeek, $endOfWeek])
     ->orderBy('date')
     ->orderBy('startTime')
     ->with('appointment') // Eager load the associated appointment
     ->get();

        return response()->json($timeslots);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('agent/timeslotCreate');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the form data
        $request->validate([
            'date' => 'required|date|after:now',
            'timeslots' => 'required|array',
            'timeslots.*' => 'in:08:00,08:30,09:00,09:30,10:00,10:30,11:00,11:30,12:00,12:30,13:00,13:30,14:00,14:30,15:00,15:30,16:00,16:30,17:00,17:30',
        ]);

        // Get the date from the form input
        $date = $request->input('date');

        // Get the selected timeslots from the form
        $selectedTimeslots = $request->input('timeslots');

        // Define a mapping of start times to their corresponding end times
        $endTimeMapping = [
            '08:00' => '08:30',
            '08:30' => '09:00',
            '09:00' => '09:30',
            '09:30' => '10:00',
            '10:00' => '10:30',
            '10:30' => '11:00',
            '11:00' => '11:30',
            '11:30' => '12:00',
            '12:00' => '12:30',
            '12:30' => '13:00',
            '13:00' => '13:30',
            '13:30' => '14:00',
            '14:00' => '14:30',
            '14:30' => '15:00',
            '15:00' => '15:30',
            '15:30' => '16:00',
            '16:00' => '16:30',
            '16:30' => '17:00',
            '17:00' => '17:30',
            '17:30' => '18:00'
        ];

        $agentID = optional(session('agent'))->agentID;

        // Save the selected timeslots to your database (e.g., the Timeslot model)
        foreach ($selectedTimeslots as $selectedStartTime) {
            // Calculate the end time based on the mapping
            $selectedEndTime = $endTimeMapping[$selectedStartTime];

            $newTimeslot = new TimeSlot;
            $newTimeslot->timeslotID = $this->generateUniqueTimeslotID();
            $newTimeslot->startTime = $selectedStartTime; // Replace with your start time
            $newTimeslot->endTime = $selectedEndTime; // Replace with your end time
            $newTimeslot->date = $date;
            $newTimeslot->agentID = $agentID; // Replace with your agent ID
            $newTimeslot->save();

          
        }

        // Redirect back with a success message or perform any other desired actions
        return redirect()->route('timeslots')->with('success', 'Timeslots saved successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $timeslot = Timeslot::find($id);
        return view('timeslots.show', compact('timeslot'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $timeslot = Timeslot::find($id);
        return view('timeslots.edit', compact('timeslot'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation rules for updating a timeslot
        $rules = [
            'start_time' => 'required',
            'end_time' => 'required',
            'date' => 'required|date',
        ];

        $this->validate($request, $rules);

        // Find the timeslot and update its attributes
        $timeslot = Timeslot::find($id);
        $timeslot->update([
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'date' => $request->input('date'),
        ]);

        return redirect()->route('timeslots.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $timeslot = Timeslot::find($id);
        $timeslot->delete();

        return redirect()->route('timeslots')->with('success', 'Timeslot '. $id .' deleted successfully!');
   
    }

    
    public function generateUniqueTimeslotID()
    {
        $latestTimeslot = Timeslot::orderBy('timeslotID', 'desc')->first();

        if ($latestTimeslot) {
            $lastID = ltrim(substr($latestTimeslot->timeslotID, 3), '0'); // Remove the "TMS" prefix and leading zeros
            $nextID = 'TMS' . str_pad($lastID + 1 , 7, '0', STR_PAD_LEFT); // Increment and pad to 7 digits
        } else {
            $nextID = 'TMS0000001'; // Initial ID
        }

        return $nextID;
    }

}