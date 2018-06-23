<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    protected $table = "attendees";
    protected $fillable = [
        'first_name',
        'last_name',
        'email_id',
        'response',
        'food_preference',
        'age_category',
        'family_id',
        'event_id',
        'message'
    ];
}
