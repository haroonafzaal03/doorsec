
   @if($dbRecord)
    @if(sizeof($dbRecord) > 0)

    @foreach($dbRecord as $index => $obj)
    <tr id="trow-{{$obj->id}}">
        <td class=""> {{ $loop->iteration}}   </td>
        <td class=""> {!!staff_image($obj->staff_id,$obj->staff->name,$obj->staff->picture)!!}   </td>

<!--        <td class=""> {{ Carbon\Carbon::parse($obj->payment_date)->format('d M, Y') }}   </td> -->
        <td class=""> {{ ($obj->total_amount > 0) ?  number_format($obj->total_amount) : "$obj->total_amount" }}   </td>
        <td class=""> {{ ($obj->paid_amount > 0) ?  number_format($obj->paid_amount) : "$obj->paid_amount" }}   </td>
        <td class=""> {{ ($obj->pending_amount > 0) ?  number_format($obj->pending_amount) : "$obj->pending_amount" }}   </td>
        <td style="padding:.2em .6em .3em !important" class="label inline-block m-l-10 m-t-10 {{ get_label_class_by_key($obj->payment_status) }}"> {{ get_status_name_by_key($obj->payment_status) }}   </td>
        <td class="">
            @if($obj->payment_status != 'unpaid')
            <button class="btn btn-warning btn-sm"  id="view_payment_logs-{{ $obj->id }}" data-id="{{ $obj->id }}" data-total_amount="{{ $obj->total_amount }}" data-paid_amount="{{ ($obj->paid_amount) ? $obj->paid_amount : 0  }}" data-pending_amount="{{ ($obj->pending_amount) ? $obj->pending_amount : 0 }}" data-status="{{ $obj->payment_status }}"  data-toggle="modal" data-target="" onClick="ViewPaymentLogs({{ $obj->id }}, {{ ($obj->client_type_id == 1 &&  $obj->event_type_id ==  NULL ) ?   $obj->venue_id  :  $obj->event_id }}, {{ $obj->staff_id }} , '{{ ($obj->client_type_id == 1 &&  $obj->event_type_id ==  NULL ) ? $obj->client_type_id : $obj->event_type_id}}')" > <i class="fa fa-eye m-r-10 hide"></i>View Details</button>
            @endif

            <!-- @if($obj->payment_status != 'paid')
            <button class="btn btn-info btn-sm"  id="edit_payroll_btn-{{ $obj->id }}" data-id="{{ $obj->id }}" data-total_amount="{{ $obj->total_amount }}" data-paid_amount="{{ ($obj->paid_amount) ? $obj->paid_amount : 0  }}" data-pending_amount="{{ ($obj->pending_amount) ? $obj->pending_amount : 0 }}" data-status="{{ $obj->payment_status }}"  data-toggle="modal" data-target="" onClick="editPayroll(this.id)" >Edit</button>
            @endif -->
            @if($obj->payment_status != 'paid')
                <a class="btn btn-info btn-sm" href='{{route("payroll_details", $obj->staff_id)}}'>Edit</a>
            @endif

        </td>
    </tr>
    @endforeach
    @endif
    @endif



