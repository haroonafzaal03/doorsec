<style type="text/css" media="all">
    table,tr,td,th{
        border:2px solid #000;
        border-collapse: collapse;
    }
    table{
        width:100%;
    }
    td,th{
        border:2px solid #000;
        border-collapse: collapse;
        padding:5px 10px;
    }
    th{
        background:#f7f7f7;
    }
    p,h3{
        margin-bottom:0px;
        background:#fff;
    }
    div{
        margin-bottom:5px;
    }
    .mb-10{
        margin-bottom:10px;
    }
    .mb-20{
        margin-bottom:10px;
    }
    .text-center{
        text-align:center;
    }
    .active_font {
        color: #367fa9;
    }
    .bold{
        font-weight:bold;
    }
    .text-muted {
        color: #777;
        font-weight:400;
    }
    .small{
        font-size:12px;
    }
    .bg-yellow-off{
        background-color: #f39c12 !important;
        padding:8px 8px;
        color:#f39c12;
        display:inline-block;
    }
    .bg-yellow{
        color:#f39c12;
        display:inline-block;
    }
    .bg-red{
        color: #dd4b39 !important;
        display:inline-block;
    }
    .bg-green{
        color: #00a65a !important;
        display:inline-block;
    }
    .event_name{
        font-size:36px;
    }
</style>

<h3 class="event_name text-center active_font">  DoorSec  </h3>
<p class="bold mb-0 small text-muted text-center">  {{ \Carbon\Carbon::parse()->format('M d, Y')}}</p>
<p class="bold mb-0 "> Staff Name : <span class="text-muted">{{$staff->name}} <span class="active_font" > ( {{$staff->stafftypes->type}} )</span> </span> </p>
<p class="bold mb-20"> Contact No : <span class="text-muted">{{$staff->contact_number}}</span> </p>

    <table id="print_slip_table" class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Event / Venue Name</th>
                <th>Payment Date</th>
                <th>Paid Amount</th>
                <th>Pending Amount</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($staff->payroll as $index => $obj)
            @foreach($obj->payroll_log as $key=>  $data)
            <tr id="trow-{{$key+1}}">
                <td>{{$key+1}}</td>
                <td class=""> {{($obj->event_id)?$obj->event->event_name:$obj->venue->client->property_name}}  </td>
                <td class=""> {{ Carbon\Carbon::parse($data->payment_date)->format('M d, Y') }}   </td>
                <td class=""> {{ $data->paid_amount}}   </td>
                <td class=""> {{ $data->pending_amount}}   </td>
                <td class=""> <label class="label bold {{ get_label_class_by_key($data->payment_status) }}"> {{ get_status_name_by_key($data->payment_status) }}    </label> </td>
            </tr>
            @endforeach
            @endforeach

        </tbody>
    </table>