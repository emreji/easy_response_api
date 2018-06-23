<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = "events";
    protected $fillable = [
        'name',
        'user_id',
        'expected_guest_count',
        'event_date', 'event_time',
        'duration',
        'location'
    ];
}
