<?php

namespace App\Http\Controllers;

use App\Attendee;
use App\Mail\Email;
use App\SuccessReponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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
        if($request->response == "attending" || $request->response == null) {
            $this->sendConfirmationEmail($request);
        }

        Attendee::create($request->all());
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

    public function sendConfirmationEmail(Request $attendee) {

        $event = DB::table('events')
            ->where('id', $attendee->event_id)
            ->get();
        $data = [
            'event' => $event[0]
        ];

        Mail::to($attendee->email_id)->send(new Email($data));
    }

    public function sendReminderEmail(Request $request, $eventId) {

        $sessionId = $request->header('SessionId');
        $attendeesEmail = DB::table('sessions')
            ->join('events', 'sessions.user_id', '=', 'events.user_id')
            ->join('attendees', 'attendees.event_id', '=', 'events.id')
            ->where('sessions.session_id', '=', $sessionId)
            ->where('attendees.event_id', '=', $eventId)
            ->get();

        if(count($attendeesEmail) > 0) {
            $attendeeEmailArray = array();
            foreach ($attendeesEmail as $a) {
                array_push($attendeeEmailArray, $a->email_id);
            }

            $event = DB::table('events')
                ->where('id', $eventId)
                ->get();
            $data = [
                'event' => $event[0],
                'attendees' => $attendeeEmailArray
            ];

            Mail::bcc($attendeeEmailArray)->send(new Email($data));
            $response = new SuccessReponse("Email sent");
        } else {
            $response = new SuccessReponse("Permission Denied!");
        }
        return response()->json($response);
    }
}
