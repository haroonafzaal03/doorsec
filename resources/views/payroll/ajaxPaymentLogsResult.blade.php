    @php

    //dd($EV_Data->property_name);
   // die;
    @endphp


    <div class="row">
        <div class="col-md-12 ">
        <div class="form-group row m-b-20">
            <div class="col-md-12 text-center">
                <h3 class="active_font bold m-t-0"> {{  ($client_type == 1) ? $EV_Data->property_name : $EV_Data->event_name }} </h3>
            </div>
            <div class="col-md-12 text-center m-b-10">
                <p class="bold m-t-0 small text-muted"> ( {{\Carbon\Carbon::parse($EV_Data->start_date)->format('M d, Y').' To '.\Carbon\Carbon::parse($EV_Data->end_date)->format('M d, Y')}}) </p>
            </div>
            <div class="col-md-12 ">
                <p class="bold m-t-0 "> Staff Name : <span class="text-muted">{{$StaffData->name}} <span class="active_font" > ( {{$StaffData->stafftypes->type}} )</span> </span> </p>
            </div>
            <div class="col-md-12 ">
                <p class="bold m-t-0"> Contact No : <span class="text-muted">{{$StaffData->contact_number}}</span> </p>
            </div>
        </div>
        <div class="form-group row m-t-0">
            <div class="col-md-12">
                <table id="payment_logs_table" class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Payment Date</th>
                            <th>Paid Amount</th>
                            <th>Pending Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(sizeof($payment_logs) > 0)
                        @foreach($payment_logs as $index => $obj)
                        <tr id="trow-{{$obj->id}}">
                            <td class=""> {{ $loop->iteration}}   </td>
                            <td class=""> {{ Carbon\Carbon::parse($obj->payment_date)->format('M d, Y') }}   </td>
                            <td class=""> {{ $obj->paid_amount}}   </td>
                            <td class=""> {{ $obj->pending_amount}}   </td>
                            <td class=""> <label class="label {{ get_label_class_by_key($obj->payment_status) }}"> {{ get_status_name_by_key($obj->payment_status) }}    </label> </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>

            </div>
        </div>
        <!-- /.row -->


        </div>
    </div>


