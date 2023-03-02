<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\BookedVenues;
use App\Models\Venue;
use Illuminate\Support\Facades\DB;

class VenueApiController extends Controller
{
    
    //login
    public function login()
    {
        $phone=request('phone');
        $password=request('password');

        $user=User::where('phone',$phone)->first();

        if(md5($password)){
            //Generate token
            $token=$user->createToken('mobile-app')->plainTextToken;

            $out=array(
                'status' =>200,
                'token' =>$token,
                'data' =>$user,
                'message' =>'Login Success'
            );
            return response()->json($out);
        }else {

            $out=array(
                'status' =>406,
                'data' =>'',
                'message' =>'Invalid credentials'
            );
            return response()->json($out);
        }
    }

    //book to venue
    public function book_venue()
    {
        $user_id=auth()->user()->id;
        if(!empty($user_id))
        {
            $day=date('Y-m-d');
            $chooseDate=request('choose_date');
            $venue=request('venue');
            $slots=request('slots');

            if($chooseDate >='2023-03-01' && $chooseDate <='2023-03-31')
            {
                
                for($i=0;$i<count($slots);$i++)
                {
                     $check_already=BookedVenues::where([
                        'venue_id'=>$venue,
                        'slot_id'=>$slots[$i],
                        'choose_date'=>$chooseDate
                    ])->get();

                    if($check_already->count() == 0)
                    {
                        //return response()->json(['message'=>1]);
                        $bookedVenue=new BookedVenues; 
                        $bookedVenue->user_id =$user_id;
                        $bookedVenue->choose_date=$chooseDate;
                        $bookedVenue->venue_id =$venue;
                        $bookedVenue->slot_id=$slots[$i];  
                        $bookedVenue->save();
                       
                    }else{
                        $booked_slots=array();
                        foreach($check_already as $slot)
                        {
                            $booked_slots['slot_id']=$slot->slot_id;
                            $booked_slots['slot_name']=$slot->slots->slot_name;
                        }
                        $out=array(
                            'status'=>0,
                            'booked_slots'=>$booked_slots,
                            'message'=>'Some slots are already booked on this date'
                        );
                        return response()->json($out);
                    }

                    
                }

                $out=array(
                    'status'=>1,
                    'message'=>'slots are booked successfully'
                );
                return response()->json($out);
                
            }else{
                $out=array(
                    'status'=>0,
                    'message'=>'Please choose date from march month only'
                );
                return response()->json($out);
            }
        }else{
            $out=array(
                'status'=>401,
                'message'=>'User not authorized'
            );
            return response()->json($out);
        }
    }

    //categorize based on bookings
    public function list_venues()
    {
        $list=Venue::withCount('getMonthResults');
        $out=array();
        $goldlist=array();
        $silverlist=array();
        $bronzelist=array();
        $normlist=array();

        foreach($list as $item)
        {
            if($item->booked_venues_count >=15)
            {
                $goldlist['venue']=$item->venues_name;
                $goldlist['booking_count']=$item->booked_venues_count;
            }
            elseif($item->booked_venues_count >=10)
            {
                $silverlist['venue']=$item->venues_name;
                $silverlist['booking_count']=$item->booked_venues_count;
            }
            elseif($item->booked_venues_count >=5)
            {
                $bronzelist['vennue']=$item->venues_name;
                $bronzelist['booking_count']=$item->booked_venues_count;
            }
            else
            {
                $normlist['venue']=$item->venues_name;
                $normlist['booking_count']=$item->booked_venues_count;
            }

            
        }

        $out=array(
            'status'=>1,
            'message'=>'Categorized vennues list',
            'data'=>array(
                'gold'=>$goldlist,
                'silver'=>$silverlist,
                'bronze'=>$bronzelist,
                'normal'=>$normlist
            ),
            
        );
        
        return response()->json(['mess'=>$out]);
    }
}
