<?php

namespace App\Http\Controllers;

use App\Whatsapp;
use Illuminate\Http\Request;
use App\StaffSchedule;
use App\Staff;
use  App\Helpers;
use Illuminate\Support\Facades\Auth;
use Clickatell\Rest;
use Clickatell\ClickatellException;
use Clickatell\Api\ClickatellHttp;
use Mail;
use App\EventConfirmation;
use App\StatusLogs;
class WhatsappController extends Controller
{
	// public function __construct()
    // {
        // $this->middleware('auth');
    // }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
	
		try{
			if(Auth::user()){
				
		$data = Whatsapp::where('receive','yes')->whereNotNull('message')->orderBy('id','DESC')->get();
		
		return view('WhatsappC.index',compact('data'));
			}else{
				return redirect('/login');
			}
		}catch(\Exception $e){
			return redirect('/login');
		}

    }
	public function response(Request $request){

		$json_data = json_encode($request->all());

		$json_fields = json_decode($json_data,true);
		//For WhatsApp Table
		$relatedMessageId			 	= (isset($json_fields['event']['moText'][0]['relatedMessageId']))?$json_fields['event']['moText'][0]['relatedMessageId']:'';
		$insertNewData['message_id'] 	= (isset($json_fields['event']['moText'][0]['messageId']))?$json_fields['event']['moText'][0]['messageId']:'';
		$insertNewData['timestamp'] 	= (isset($json_fields['event']['moText'][0]['timestamp']))?$json_fields['event']['moText'][0]['timestamp']:'';
		$insertNewData['message'] 		= (isset($json_fields['event']['moText'][0]['content']))?strtolower($json_fields['event']['moText'][0]['content']):'';
		$insertNewData['contact_number']= (isset($json_fields['event']['moText'][0]['from']))?$json_fields['event']['moText'][0]['from']:'';
		$insertNewData['related_id']    = $relatedMessageId;

		$insertNewData['sent']			= 'no';
		$insertNewData['receive']		= 'yes';

		//for Status Log
		$insertStatusLog['message_id']	= (isset($json_fields['event']['moText'][0]['messageId']))?$json_fields['event']['moText'][0]['messageId']:'';
		$insertStatusLog['modify_array']= $json_data;
		$insertStatusLog['timestamp']= (isset($json_fields['event']['moText'][0]['timestamp']))?$json_fields['event']['moText'][0]['timestamp']:'';

		$ph_number = (isset($json_fields['event']['moText'][0]['from']))?$json_fields['event']['moText'][0]['from']:'';
		$getData = Whatsapp::where(['message_id' => $relatedMessageId])->get()->toArray();
		$content = (isset($json_fields['event']['moText'][0]['content']))?strtolower($json_fields['event']['moText'][0]['content']):'';


		// if (!empty($json_fields['event']['messageStatusUpdate'][0]['messageId'])) {
		// 	$messageId = $json_fields['event']['messageStatusUpdate'][0]['messageId'];

		// 	$getWData = Whatsapp::where(['message_id' => $messageId])->get()->toArray();
		// 	$staff_id = $getWData[0]['staff_id'];
		// 	$event_id = $getWData[0]['event_id'];

		// 	$contentz['wa_response']  = $json_fields['event']['messageStatusUpdate'][0]['status'];
		// 	StaffSchedule::where(['staff_id' => $staff_id])->where(['event_id' => $event_id])->update($contentz);

		// }

	//Start Event WhatsApp Module
		if ($relatedMessageId != null && $getData[0]['event_id']!=null) {

			if (!empty($ph_number)) {

				$businessType = $getData[0]['message_type'];
				$staff_id = $getData[0]['staff_id'];
				$event_id = $getData[0]['event_id'];
				$event_startTime = $getData[0]['start_time'];
				$contentz['wa_response']  = $json_fields['event']['moText'][0]['content'];
				StaffSchedule::where(['staff_id' => $staff_id])->where(['event_id' => $event_id])->update($contentz);
			}else{ return false; }


			if ($businessType == 'business') {

				if (strtolower($content) == 'yes'|| strtolower($content) == 'confirm'|| strtolower($content) == 'ok' ) {
					$insertNewData['message_type']	= 'business';
					# update schedule table

					$upwsappArray['sms_status'] = 'confirmed';
					$upwsappArray['status'] 	= 'confirmed';
					$EventWappArray['status'] 	= 'confirmed';
					StaffSchedule::where(['staff_id' => $staff_id])->where(['event_id' => $event_id])->update($upwsappArray);
					EventConfirmation::where(['staff_id' => $staff_id])->where(['event_id' => $event_id])->where(['contact_number' => $ph_number])->update($EventWappArray);
					Whatsapp::create($insertNewData);
					StatusLogs::create($insertStatusLog);

				}elseif(strtolower($content) == 'no' || strtolower($content) == 'sorry' || strtolower($content) == 'not available'){
					$insertNewData['message_type']	= 'business';
					$upwsappArray['sms_status'] = 'declined';
					$upwsappArray['status'] 	= 'declined';
					$EventWappArray['status'] 	= 'dropout';
					StaffSchedule::where(['staff_id' => $staff_id])->where(['event_id' => $event_id])->update($upwsappArray);
					EventConfirmation::where(['staff_id' => $staff_id])->where(['event_id' => $event_id])->where(['contact_number' => $ph_number])->update($EventWappArray);
					Whatsapp::create($insertNewData);
					StatusLogs::create($insertStatusLog);
				}
				else{
					 $insertNewData['message_type']	= 'normal';
					 Whatsapp::create($insertNewData);
					StatusLogs::create($insertStatusLog);
				}
			}

		}
		//End Event WhatsApp Module
		//Start Venue WhatsApp Module
		else if($relatedMessageId != null && $getData[0]['venue_id']!=null){


			if (!empty($ph_number)) {

				$businessType = $getData[0]['message_type'];
				$staff_id = $getData[0]['staff_id'];
				$venue_id = $getData[0]['venue_id'];
				$start_dateV = $getData[0]['start_date'];

				$contentz['wa_response']  = $json_fields['event']['moText'][0]['content'];
				StaffSchedule::where(['staff_id' => $staff_id])->where(['venue_detail_id' => $venue_id])->update($contentz);
			}else{ return false; }


			if ($businessType == 'business') {

				if(strtolower($content) == 'yes'|| strtolower($content) == 'confirm'|| strtolower($content) == 'ok' ) {
					$insertNewData['message_type']	= 'business';
					# update schedule table

					$upwsappArray['sms_status'] = 'confirmed';
					$upwsappArray['status'] 	= 'confirmed';
					$EventWappArray['status'] 	= 'confirmed';
					StaffSchedule::where(['staff_id' => $staff_id])->where(['venue_detail_id' => $venue_id])->update($upwsappArray);
					EventConfirmation::where(['staff_id' => $staff_id])->where(['venue_id' => $venue_id])->update($EventWappArray);
					Whatsapp::create($insertNewData);
					StatusLogs::create($insertStatusLog);

				}elseif(strtolower($content) == 'no' || strtolower($content) == 'sorry' || strtolower($content) == 'not available'){
					$insertNewData['message_type']	= 'business';
					$upwsappArray['sms_status'] = 'declined';
					$upwsappArray['status'] 	= 'dropout';
					$EventWappArray['status'] 	= 'declined';
					StaffSchedule::where(['staff_id' => $staff_id])->where(['venue_detail_id' => $venue_id])->update($upwsappArray);
					EventConfirmation::where(['staff_id' => $staff_id])->where(['venue_id' => $venue_id])->update($EventWappArray);
					Whatsapp::create($insertNewData);
					StatusLogs::create($insertStatusLog);
				}
				else{
					 $insertNewData['message_type']	= 'normal';
					 Whatsapp::create($insertNewData);
					StatusLogs::create($insertStatusLog);
				}
			}


		}
		////END Venue WhatsApp Module
		else{
					$insertNewData['message_type']	= 'normal';
					Whatsapp::create($insertNewData);
					StatusLogs::create($insertStatusLog);
		}
			return;
		$data = [

              'response' 		=> $json_fields['event']['moText'][0]['messageId']
          ];
			$email= 'salman.cybus@gmail.com';
			Mail::send('WhatsappC.response',['data'=>$data], function ($message) use ($email) {
                  $message->from('info@iconicint.com', 'Iconic International');
                  $message->to($email)->subject('Hey! Visit this Agent');
              });

	}
	public function statusResponse(Request $request){
		$json_data = json_encode($request->all());

		$json_fields = json_decode($json_data,true);

		if (!empty($json_fields['event']['messageStatusUpdate'][0]['messageId']) && isset($json_fields['event']['messageStatusUpdate'][0]['status']) && $json_fields['event']['messageStatusUpdate'][0]['status'] == 'RECIPIENT_DOES_NOT_EXIST') {

			$messageId = (isset($json_fields['event']['messageStatusUpdate'][0]['messageId']))?$json_fields['event']['messageStatusUpdate'][0]['messageId']:'';

			$getWData = Whatsapp::where(['message_id' => $messageId])->get()->toArray();
			$staff_id = (!empty($getWData[0]['staff_id']) && isset($getWData[0]['staff_id']))?$getWData[0]['staff_id']:'';
			$event_id = (!empty($getWData[0]['event_id']) && isset($getWData[0]['event_id']))?$getWData[0]['event_id']:'';
			$venue_id = (!empty($getWData[0]['venue_id']) && isset($getWData[0]['venue_id']))?$getWData[0]['venue_id']:'';

			$contentz['wa_response']  = $json_fields['event']['messageStatusUpdate'][0]['status'];
			$contentz['updated_by'] = Auth::user()->id;//incorrect_number
			if($json_fields['event']['messageStatusUpdate'][0]['status'] == 'RECIPIENT_DOES_NOT_EXIST'){
				$contentz['sms_status'] = "incorrect_number";//incorrect_number
			}
			if (!empty($event_id)) {
				StaffSchedule::where(['staff_id' => $staff_id])->where(['event_id' => $event_id])->update($contentz);
			}else{
				StaffSchedule::where(['staff_id' => $staff_id])->where(['venue_id' => $venue_id])->update($contentz);
			}

		}

		$insertNewData['modify_array'] 	= $json_data;
		$insertNewData['message_id'] 	= $json_fields['event']['messageStatusUpdate'][0]['messageId'];
		$insertNewData['timestamp'] 	= $json_fields['event']['messageStatusUpdate'][0]['timestamp'];

		StatusLogs::create($insertNewData);

	}
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Outgoing traffic callbacks (MT callbacks)
    Rest::parseStatusCallback(function ($result) {
        var_dump($result);
        // This will execute if the request to the web page contains all the values
        // specified by Clickatell. Requests that omit these values will be ignored.
    });

    // Incoming traffic callbacks (MO/Two Way callbacks)
    Rest::parseReplyCallback(function ($result) {
        var_dump($result);
        // This will execute if the request to the web page contains all the values
        // specified by Clickatell. Requests that omit these values will be ignored.
    });
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
     * @param  \App\Whatsapp  $whatsapp
     * @return \Illuminate\Http\Response
     */
    public function show(Whatsapp $whatsapp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Whatsapp  $whatsapp
     * @return \Illuminate\Http\Response
     */
    public function edit(Whatsapp $whatsapp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Whatsapp  $whatsapp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Whatsapp $whatsapp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Whatsapp  $whatsapp
     * @return \Illuminate\Http\Response
     */
    public function destroy(Whatsapp $whatsapp)
    {
        //
    }
}
