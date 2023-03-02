<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    public function BookedVenues()
    {
        return $this->hasMany(BookedVenues::class,'venue_id','id');
    }
    public function getMonthResults()
    {
        $month=date("m");
        $res=Venue::BookedVenues()->whereMonth('choose_date',$month);
        return $res;
    }
    
}
