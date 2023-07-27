<html>
<table>
    <thead>
    <tr colspan="3"></tr>
    <tr >
    <td colspan="2" style="height:20px;width:100px;text-align:center;background-color:#eae116;font-size:16px;">Event Log Details</td>
    </tr>

    @if($eventData)
    <tr>
        <td style="font-size:12px;height:20px;font-weight:bold;background-color: #cecece;border:1px solid black">To</td>
        <td style="font-size:12px;height:20px;font-weight:400;width:50px;border:1px solid black">SIRA</td>
    </tr>

    <tr>

        <td style="font-size:12px;height:20px;font-weight:bold;background-color:#cecece;border:1px solid black">Event Company</td>
        <td style="font-size:12px;height:20px;font-weight:400;width:50px;border:1px solid black">{{ $eventData->client->property_name }}</td>
    </tr>
    <tr>

        <td style="font-size:12px;height:20px;font-weight:bold;background-color:#cecece;border:1px solid black">From</td>
        <td style="font-size:12px;height:20px;font-weight:400;width:50px;border:1px solid black">{{ $eventData->eventData_from }}</td>
    </tr>
    <tr>

        <td style="font-size:12px;height:20px;font-weight:bold;background-color:#cecece;border:1px solid black">Event Name	</td>
        <td style="font-size:12px;height:20px;font-weight:400;width:50px;border:1px solid black">{{ $eventData->event_name }}</td>
    </tr>
    <tr>

        <td style="font-size:12px;height:20px;font-weight:bold;background-color:#cecece;border:1px solid black">Date of Event	</td>
        <td style="font-size:12px;height:20px;font-weight:400;width:50px;border:1px solid black">{{\Carbon\Carbon::parse($eventData['start_date'])->format('M d ,Y').' To '.\Carbon\Carbon::parse($eventData['end_date'])->format('M d ,Y')}}</td>
    </tr>
    <tr>

        <td style="font-size:12px;height:20px;font-weight:bold;background-color:#cecece;border:1px solid black">Type of Event	</td>
        <td style="font-size:12px;height:20px;font-weight:400;width:50px;border:1px solid black">{{ $eventData->event_type }}</td>
    </tr>
    <tr>

        <td style="font-size:12px;height:20px;font-weight:bold;background-color:#cecece;border:1px solid black">Timmings </td>
        <td style="font-size:12px;height:20px;font-weight:400;width:50px;border:1px solid black">{{\Carbon\Carbon::parse($eventData['start_time'])->format('H:i a').' To '.\Carbon\Carbon::parse($eventData['end_time'])->format('H:i a')}}</td>
    </tr>
    <tr>

        <td style="font-size:12px;height:20px;font-weight:bold;background-color:#cecece;border:1px solid black">Security Required</td>
        <td style="font-size:12px;height:20px;font-weight:400;width:50px;border:1px solid black;text-align:left">{{ $eventData->total_staff }}</td>
    </tr>
    <tr>

        <td style="font-size:12px;height:20px;font-weight:bold;background-color:#cecece;border:1px solid black">Contact Person</td>
        <td style="font-size:12px;height:20px;font-weight:400;width:50px;border:1px solid black">{{ $eventData->contact_person }}</td>
    </tr>
        @endif
    </thead>
</table>


<table id="event_logs_table" class="table table-bordered m-b-30">
        <thead>
            <tr>
            <th style="background-color:#dddddd;font-size:12px;font-weight:bold;" width="24">Time.</th>
            <th style="background-color:#dddddd;font-size:12px;font-weight:bold;" width="35">Sender</th>
            <th style="background-color:#dddddd;font-size:12px;font-weight:bold;" width="20">To.</th>
            <th style="background-color:#dddddd;font-size:12px;font-weight:bold;" width="50">Message</th>
            <th style="background-color:#dddddd;font-size:12px;font-weight:bold;" width="50">Responded BY</th>
            <th style="background-color:#dddddd;font-size:12px;font-weight:bold;" width="50">Action Taken</th>
            </tr>
        </thead>
        <tbody>
            @if(sizeof($event_logs_list) > 0)
                @foreach($event_logs_list as $index => $obj)
                    <tr>
                        <td style="background-color:#{{ $obj->color_code }};font-size:12px;color:#000000;text-align:left" >{{ Carbon\Carbon::parse($obj->time)->format('H:i:s a') }}</td>
                        <td style="background-color:#{{ $obj->color_code }};font-size:12px;color:#000000;text-align:left" > {{ $obj->name }}</td>
                        <td style="background-color:#{{ $obj->color_code }};font-size:12px;color:#000000;text-align:left" > {{ $obj->to }} </td>
                        <td style="background-color:#{{ $obj->color_code }};font-size:12px;color:#000000;text-align:left" > {{ $obj->action }} </td>
                        <td style="background-color:#{{ $obj->color_code }};font-size:12px;color:#000000;text-align:left" > {{ $obj->responded_by_in }}  </td>
                        <td style="background-color:#{{ $obj->color_code }};font-size:12px;color:#000000;text-align:left" > {{ $obj->action_taken }} </td>
                    </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</html>
