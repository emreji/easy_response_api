<?php

namespace App\Http\Controllers;

use App\Event;
use App\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = Event::all();
        return response()->json($events);
    }

    public function getEventsForUser(Request $request) {
        $sessionId = $request->header('SessionId');

        $events = DB::table('sessions')
            ->join('events', 'sessions.user_id', '=', 'events.user_id')
            ->where('sessions.session_id', '=', $sessionId)
            ->select('events.*')
            ->get();

        return $events;
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
        $sessionId = $request->header('SessionId');
        $results = DB::table('sessions')
            ->where('session_id', $sessionId)
            ->pluck('user_id');

        if(count($results) > 0) {
            $userId = $results->first();
        } else {
            return response("User not found!");
        }

        $request->request->add(['user_id' => $userId]);

        return Event::create(array_merge($request->all(), ['user_id' => $userId]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $sessionId = $request->header('SessionId');
        $event = DB::table('sessions')
            ->join('events', 'sessions.user_id', '=', 'events.user_id')
            ->where('sessions.session_id', '=', $sessionId)
            ->where('events.id', '=', $id)
            ->select('events.*')
            ->get();

        return response()->json($event);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Event::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $sessionId = $request->header('SessionId');
        $userId = DB::table('sessions')->where('session_id', $sessionId)->pluck('user_id');

//        $event = DB::table('events')
//            ->whereRaw("id = $id AND user_id = $userId")
//            ->update($request->all());

        return response($userId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        $sessionId = $request->header('SessionId');

        $user_id = DB::table('sessions')
            ->where('session_id', '=', $sessionId)
            ->pluck('user_id');

        DB::table('events')
            ->where('user_id', '=', $user_id)
            ->where('id', '=', $id)
            ->delete();

    }
}
