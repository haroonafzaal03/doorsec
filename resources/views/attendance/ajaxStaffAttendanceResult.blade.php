    @if(sizeof($Data) > 0)
    @foreach($Data as $index => $obj)
    <tr id="trow-{{$obj->id}}">
        <td class=""> {{ $loop->iteration}}   </td>
        <td class="">  <span class="username" > {!!staff_image($obj->staff_id,$obj->name,$obj->picture)!!}  </span>   </td>
        <td class=""> {{ ($obj->client_type_id == 1) ? $obj->property_name : $obj->event_name}}   </td>
        <td class=""> {{ ($obj->client_type_id == 1) ? Carbon\Carbon::parse($obj->ven_st_date)->format('d M, Y')  : Carbon\Carbon::parse($obj->ev_st_date)->format('d M, Y') . ' To ' . Carbon\Carbon::parse($obj->ev_end_date)->format('d M, Y')  }}   </td>
        <td class="">
        <input type="checkbox" id="" name="array_staff_sch[{{$index}}][availability]"  class="attendance_box"  style="transform: scale(1.5);" {{ ($obj->availability) ? "checked" : "" }}   onchange="getTotalActiveCheckBoxes()"/>
        <input type="hidden" id="" value="{{$obj->id}}" name="array_staff_sch[{{$index}}][id]"  class=""  />
          </td>
        <td class="hide">

            <button class="btn btn-info btn-sm"  id="edit_payroll_btn-{{ $obj->id }}" data-id="{{ $obj->id }}"    data-toggle="modal" data-target="" onClick="editPayroll(this.id)" >Edit</button>

        </td>
    </tr>
    @endforeach
    @endif
