<?php

use App\PromocodeUsage;
use App\Staff;
function currency($value = '')
{
    if($value == ""){
        return Setting::get('currency')."0";
    }else{
        return Setting::get('currency').$value;
    }
}

function distance($value = '')
{
    if($value == ""){
        return "0".Setting::get('distance', 'Km');
    }else{
        return $value.Setting::get('distance', 'Km');
    }
}

function img($img){
    if($img == ""){
        return asset('avatar.jpg');
    }else if (strpos($img, 'http') !== false) {
        return $img;
    }else{
        $result = file_exists(public_path().'/storage'.'/'.$img);
       // print_r($result); die;
        if($result){
            $ext = pathinfo($img, PATHINFO_EXTENSION);
            if($ext == 'jpg' || $ext =='png' || $ext =='jpeg'){
                return asset('/storage'.'/'.$img);
            }else{
                return asset('img/document.png');
            }

        }else{
            return asset('avatar.jpg');
        }

       // return asset('/storage'.'/'.$img);
    }
}

function img_click($img){
    if($img == ""){
        return '#';
    }else if (strpos($img, 'http') !== false) {
        return $img;
    }else{
        $result = file_exists(public_path().'/storage'.'/'.$img);
       // print_r($result); die;
        if($result){
            return asset('/storage'.'/'.$img);
        }else{
            return asset('avatar.jpg');
        }

       // return asset('/storage'.'/'.$img);
    }
}
function image_base(){
    return asset('/storage'.'/');
}
function duration($duration)
{
    if($duration==0){
        return 0;
    }else {
        $temp = $duration / 60;
        return round($temp,2);
    }
}

function promo_used_count($promo_id)
{
    return PromocodeUsage::where('status','USED')->where('promocode_id',$promo_id)->count();
}

function curl($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);
    curl_close ($ch);
    return $return;
}
function arrayCount($selected){
    $temp=1;
    while(isset($selected[$temp])){
        $temp= $temp+1;
    }
    return $temp;

}

function get_schedule_status_list()
{
    $status = array(
        'not_booked'=> 'Not Booked',
        'booked'=>'Booked'
    );
    return $status;
}

function get_status_name_by_key($key,$type = false)
{
    $name = null;

    $statuses = array(
        'not_booked'=> 'Not Booked',
        'closed'=> 'Closed',
        'booked'=>'Booked',
        'confirmed' =>'Confirmed',
        'dropout' =>'Dropout',
        'not_sent'=> 'Not Sent',
        'paid'=> 'Paid',
        'unpaid'=> 'Un Paid',
        'hold'=> 'Hold',
        'partial'=>'Partial',
        'declined'=>'Declined',
        'incorrect_number'=>'InCorrect Number'
    );

    if($type == "sms")
    {
        $statuses['pending'] = "Sent";
    }
    else
    {
        $statuses['pending'] = "Pending";
    }

    $name =  (isset($statuses[$key])) ? $statuses[$key] : "";
    return $name;
}

function get_staff_schedule_status_list()
{
    $status = array(
        'pending'=> 'Pending',
        'confirmed' =>'Confirmed',
        'dropout' =>'Dropout'
    );
    return $status;
}

function get_sms_status_list()
{
    $status = array(
        'pending'=> 'Sent',
        'not_sent'=> 'Not Sent',
        'confirmed' =>'Confirmed',
        'incorrect_number'=> 'InCorrect Number'
    );
    return $status;
}

function get_label_class_by_key($key)
{
    $bg_class = "";
    $arr = array(
        'not_booked'=> 'bg-yellow',
        'closed'=> 'bg-red',
        'booked'=>'bg-green',
        'pending'=> 'bg-yellow',
        'confirmed' =>'bg-green',
        'dropout' =>'bg-red',
        'not_sent'=> 'bg-blue',
        'active'=> 'bg-green',
        'deactivate'=> 'bg-red',
        'paid'=> 'bg-green',
        'unpaid'=> 'bg-red',
        'hold'=> 'bg-red',
        'partial'=>'bg-yellow',
        'declined'=>'bg-red',
        'incorrect_number'=>'bg-red'
    );

    return (isset($arr[$key])) ? $arr[$key] : "";

}


function getStaffLabels($key)
{
    $bg_class = "";
    $arr = array(
        'name'=> 'Name',
        'sira_id_number'=>'SIRA No.',
        'contact_number'=> 'Contact Number',
        'other_contact_number' =>'Other Contact Number',
        'emitrates_id' =>'Emirated ID',
        'uid_number' =>'UID No.',
        'nationality' =>'Nationality',
        'height' =>'Height',
        'contact_number_home' =>'Contact Number (Home)',
        'weight' =>'Weight',
        'passport_number'=> 'Passport No.'
    );

    return (isset($arr[$key])) ? $arr[$key] : NULL;

}

function getStaffDocumentLabels($key)
{
    $bg_class = "";
    $arr = array(
        'noc_attach'=>'NOC',
        'passport_attach'=>'Passport Copy',
        'sira_id_attach'=>'SIRA Copy',
        'emirated_id_attach'=>'Emirates ID Copy',
        'visa_attach'=> 'VISA Copy',
        'picture'=> 'Picture'
    );

    return (isset($arr[$key])) ? $arr[$key] : NULL;

}

function getExcludedColumsforDocuments()
{
    $exclude_cols_array   = array(
        'id',
        'name',
        'sira_id_number',
        'contact_number',
        'other_contact_number',
        'emitrates_id',
        'uid_number',
        'nationality',
        'height',
        'contact_number_home',
        'weight',
        'passport_number',
        'staff_type_id',
        'edu_document',
        'next_to_kin',
        'passport_expiry',
        'visa_expiry',
        'sponsor_details',
        'status',
        'basic_salary',
        'gender',
        'emirates_expiry',
        'sira_type_id',
        'noc_expiry',
        'sira_type_id',
        'is_super_staff',
        'created_at',
        'updated_at',
        'block_for_clients',
        'nk_address',
        'nk_phone',
        'nk_relation',
        'nk_name',
        'nk_nationality'
    );

    return $exclude_cols_array;
}
function getExcludedColums()
{
    $exclude_cols_array   = array(
        'id',
        'picture',
        'staff_type_id',
        'edu_document',
        'next_to_kin',
        'emirated_id_attach',
        'sira_id_attach',
        'passport_expiry',
        'visa_expiry',
        'sponsor_details',
        'passport_attach',
        'visa_attach',
        'status',
        'basic_salary',
        'gender',
        'emirates_expiry',
        'noc_attach',
        'sira_type_id',
        'noc_expiry',
        'sira_type_id',
        'is_super_staff',
        'created_at',
        'updated_at',
        'block_for_clients',
        'nk_address',
        'nk_phone',
        'nk_relation',
        'nk_name',
        'nk_nationality'
    );

    return $exclude_cols_array;
}
function staff_image($id,$name,$src){
  $return = '<a href="#" class="inline-block img_lightbox m-r-10" data-toggle="" data-target=""><img src="'.img($src).'" class="custom profile-user-img img-responsive img-circle" /></a> <a href="'.route("staff_show",$id).'" class="bold active_font inline">'.$name.'</a>';
  return $return;
}

function venueCard($time,$staff_name,$staff_pic){
    $time_ = $time;
    $staff_name = $staff_name;
    $staff_pic = $staff_pic;

    return view('common.venue_cardHelper',compact('time_','staff_name','staff_pic'));
}
 function get_staff_by_number($number){
    $staff = Staff::where('contact_number','LIKE','%'.$number.'%')->first();
    if($staff){
        return $staff['name'];
    }else{
    return $number;
    }
}

function phone_number_format($number) {
  // Allow only Digits, remove all other characters.
  $number = preg_replace("/[^\d]/","",$number);

  // get number length.
  $length = strlen($number);

 // if number = 10
 if($length == 15) {
  $number = preg_replace("/^1?(\d{3})(\d{4})(\d{7})(\d{1})$/", "$1-$2-$3-$4", $number);
 }

  return $number;

}