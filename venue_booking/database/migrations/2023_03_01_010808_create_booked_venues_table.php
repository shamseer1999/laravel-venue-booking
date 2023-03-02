<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookedVenuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booked_venues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->date('choose_date');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('venue_id');
            $table->foreign('venue_id')->references('id')->on('venues');
            $table->unsignedBigInteger('slot_id');
            $table->foreign('slot_id')->references('id')->on('slots');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booked_venues');
    }
}
