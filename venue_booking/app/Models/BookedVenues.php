<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookedVenues extends Model
{
    use HasFactory;

    protected $table='booked_venues';
    protected $fillable=['choose_date','venue_id','slot_id','user_id'];

    public function slots()
    {
        return $this->belongsTo(Slot::class,'slot_id','id');
    }

    public function venues()
    {
        return $this->belongsTo(Venue::class,'venue_id','id');
    }
}
