<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendees', function (Blueprint $table) {
            $table->increments('id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email_id');
            $table->enum('response', ['attending', 'not attending']);
            $table->enum('food_preference', ['veg', 'non-veg']);
            $table->enum('age_category', ['adult', 'child']);
            $table->string('family_id');
            $table->unsignedInteger('event_id');
            $table->text('message');
            $table->timestamps();
        });

        Schema::table('attendees', function (Blueprint $table) {
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendees');
    }
}
