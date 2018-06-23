<?php

namespace App\Http\Controllers;

use App\Attendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendeesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendees = Attendee::all();
        return response()->json($attendees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return Attendee::create($request->all());
    }

    public function getAttendeesForEventId($eventId, Request $request) {
        $sessionId = $request->header('SessionId');

        $attendees = DB::table('sessions')
            ->join('events', 'sessions.user_id', '=', 'events.user_id')
            ->join('attendees', 'events.id', '=', 'attendees.event_id')
            ->where('sessions.session_id', '=', $sessionId)
            ->where('attendees.event_id', '=', $eventId)
            ->select('attendees.*')
            ->get();


        return $attendees;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Attendee::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Attendee::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendee = Attendee::find($id);
        $attendee->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
