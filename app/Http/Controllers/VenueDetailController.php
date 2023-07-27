<?php

namespace App\Http\Controllers;

use App\VenueDetail;
use App\Venue;
use App\Client;
use App\StaffSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use DB;
use Carbon\Carbon;
use App\Whatsapp;
use App\EventConfirmation;

class VenueDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ids = VenueDetail::all()->pluck('id');
        $schedule = VenueDetail::orderBy('id','DESC')->get();
       // dd($schedule);
    //    foreach($schedule as $vd){
    //        foreach($vd->venues as $vdv){
    //             foreach ($vdv->satff_schdedules as $vdvss){
    //                 echo "s <br>";
    //                 echo $vdvss->id;
    //         }
    //         }
    //    }
      // die;
       $venue_ids = Venue::whereIN('venue_detail_id',$ids)->pluck('id');
       $venue = StaffSchedule::whereIN('venue_id',$venue_ids)->first();
        //dd($venue->venue);
        return view('club_event.venue.index_detail',compact('schedule','venue'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VenueDetail  $venueDetail
     * @return \Illuminate\Http\Response
     */
    public function show(VenueDetail $venueDetail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VenueDetail  $venueDetail
     * @return \Illuminate\Http\Response
     */
    public function edit(VenueDetail $venueDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VenueDetail  $venueDetail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, VenueDetail $venueDetail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VenueDetail  $venueDetail
     * @return \Illuminate\Http\Response
     */
    public function destroy(VenueDetail $venueDetail)
    {
        //
    }
    public function send_sms_view($id){
        $schedule = VenueDetail::find($id) ;
        return view('club_event.venue.venue_message',compact('schedule'));
    }
    public function sendwhatsappmessage(Request $request ,$id){
      
      $schedule_data = VenueDetail::find($id);
       //dd($schedule_data);//->venues[0]->client->client_address);
    //   $information 		= $request->staff;
    //     foreach($information as $key=> $info){
    //         echo $key.'  '.$info ;
    //     }die;
        $venue_id 			= $schedule_data->venue_id;
        $venue_detail_id    = $id;
		$information 		= $request->staff;

		$location 			= $schedule_data->venues[0]->client->client_address;
		$location_guide 	= $request->location_guide;
		$start_date 		= $schedule_data->start_date;
        $end_date 			= $schedule_data->end_date;
        $event_loc_guideReq = $request->event_loc_guide;
        $signMeetPt         = $request->signMeetPt;

        // 1: "{{ isset($schedule_data)?$schedule_data['event_name']:'' }}",
        // 2: "{{ isset($schedule_data)?$schedule_data['location']:'' }}" +
        //     ' Location Guide ' + event_loc_guideReq,
        // 3: signMeetPt,
        // 4: "{{ isset($schedule_data)?$schedule_data['start_date']:'' }} To {{ isset($schedule_data)?$schedule_data['end_date']:'' }}",
        // 5: event_arr_timeReq,
        // 6: eventBrie_timeReq,
        // 7: strtTimeE,
        // 8: event_dress_codeReq
        $data[1]= $schedule_data->venues[0]->client->property_name;
        $data[2]= $location .' Location Guide '.$event_loc_guideReq;
        $data[3]= $signMeetPt;
        $data[4]= $start_date . ' To '. $end_date;
        $data[5]= $request->arrving_time;
        $data[6]= $request->briefing_time;
        $data[7]= $request->strtTimeE;
        $data[8]= $request->event_dress_code;
            //dd($data);
        $ch = curl_init();
		foreach($information as $key=> $info){

			//echo $key+1 ."  ".$info['number'];
            $staff_id 			= $key;
            $ph_number = $info;
			//$ph_number = 923324288876;
			$url = 'https://platform.clickatell.com/v1/message';
			$headers = array(
				"POST /v1/messages",
				"Content-Type: application/json",
				"Accept: application/json",
				"Authorization: 5dfJ3cO_Riiw62Gv4RmB_g=="
				);
			$curl_data = '{
						"messages": [
										{
										"channel": "whatsapp",
										"to": "'.$ph_number.'",
										"hsm" : {
													"template":"venue_event_confirmation",
													"parameters" : {
																	"1": "'.$data[1].'",
																	"2": "'.$data[2].'",
																	"3": "'.$data[3].'",
																	"4": "'.$data[4].'",
																	"5": "'.$data[5].'",
																	"6": "'.$data[6].'",
																	"7": "'.$data[7].'",
																	"8": "'.$data[8].'"
																	}
												}
										}
									]
					}';

			// $ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $curl_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($ch);
			$response_info = curl_getinfo($ch);
			if(($response_info["http_code"] >= 200) AND ($response_info["http_code"] <= 299)) {
				 // print_r($response);
				 $responses = json_decode($response,true);
				 //echo "  OK -->".$responses['messages'][0]['apiMessageId']."<br>";
					$submit_data['event_data']		=$data;
					$submit_data['curl_data']		=$responses;
					$submit_data['venue_id']		=$venue_id;
					$submit_data['ph_number']		=$ph_number;
					$submit_data['message_id']		=$responses['messages'][0]['apiMessageId'];
					$submit_data['staff_id']		=$staff_id;
					$submit_data['location']		=$location;
					$submit_data['location_guide']	=$event_loc_guideReq;
					$submit_data['start_date']		=$start_date;
                    $submit_data['end_date']		=$end_date;
                    $submit_data['venue_detail_id'] = $venue_detail_id;
					$done 							= array('color'=>'flash_success','message'=>$this->update_send_Message_data($submit_data));
			}else{
				$responses = json_decode($response,true);
				$done = array('color'=>'red','message'=>$responses['messages'][0]['error']['description']);
				//print_r($responses);
			}
		}
		curl_close($ch);
		return redirect()->back()->with($done['color'],$done['message']);


        }
		public function update_send_Message_data($data){
		//defined variables
		 // DB::enableQueryLog();
		//$date 									= Carbon::now();
		$venue_id 								= $data['venue_id'];
		$venue_detail_id 						= $data['venue_detail_id'];
		$phone_number							= $data['ph_number'];
		$MessageId								= $data['message_id'];
		$staff_id								= $data['staff_id'];

		//for Event_Confirmation
		$eventArray['staff_id']					= $data['staff_id'];
		$eventArray['venue_id']					= $venue_detail_id;
		$eventArray['contact_number']			= $phone_number;
		$eventArray['location']					= $data['location'];
		$eventArray['arrival_time']				= $data['event_data'][5];
		$eventArray['briefing']					= $data['event_data'][6];
		$eventArray['venue']					= $data['event_data'][2];
		$eventArray['location_guide']			= $data['location_guide'];
		$eventArray['dress_code']				= $data['event_data'][8];
		$eventArray['start_date']				= $data['start_date'];
		$eventArray['start_time']				= $data['end_date'];
		// $eventArray['date']						= now()->toDateTimeString('Y-m-d');
		$eventArray['signingMeetingPt']			= $data['event_data'][3];
		$eventArray['status']					= 'sent';
		//print_r($eventArray);return;

		//for whatsapp
        $wsappArray['message_id'] 				= $MessageId;
        $wsappArray['contact_number'] 			= $phone_number;
		$wsappArray['message_type'] 			= 'business';
		$wsappArray['sent'] 					= 'yes';
		$wsappArray['receive'] 					= 'no';

		if ($staff_id) {
			$wsappArray['staff_id']				= $staff_id;
			$parseArray['staff_id']				= $staff_id;
			$parseArray['start_time']			= date("H:i:s",strtotime($data['event_data'][7]));
		}
		//End bulk sms or single sms

		$wsappArray['venue_id'] 		= $venue_detail_id;
        $parseArray['updated_by']       = Auth::user()->id;
        $parseArray['message_id'] 		= $MessageId;

        $resp = null;
        if ($venue_detail_id) {
            $is_exist = StaffSchedule::where('venue_detail_id', '=', $venue_detail_id)->where('staff_id', '=', $staff_id)->first();


            $parseArray['availability'] = (isset($parseArray['availability']) && ($parseArray['availability'] == 'on')) ? 1 : 0;

            if ($is_exist) {
                unset($parseArray['staff_id']);

                $parseArray['status'] 					= "pending";
                $parseArray['sms_status'] 				= "pending";

				// save & update data
				$resp 									= StaffSchedule::where(['venue_detail_id' => $venue_detail_id])->where(['staff_id' => $staff_id])->update($parseArray);
				//print_r(DB::getQueryLog);
				Whatsapp::create($wsappArray);
                EventConfirmation::create($eventArray);
				$upwsappArray['message_id'] 			= $MessageId;
				$upwsappArray['contact_number'] 		= $phone_number;
                $status 								= "200";
				//tem will update content based upon messageID

            } else {
                $parseArray['venue_id'] = $venue_detail_id;
                $parseArray['venue_detail_id'] = $venue_detail_id;

                $parseArray['status'] 		= "pending";
                $parseArray['sms_status'] 	= "pending";

                 $resp 						= StaffSchedule::create($parseArray);
				//  //save data
                 Whatsapp::create($wsappArray);
                 EventConfirmation::create($eventArray);
                $status = "200";

            }
        } // if event_id found in request
        else {
            $status = "404"; // event Id not found
			return "ID not found";
        }
			return "sent successfully";
		}
}