<table>
    <thead>
        <tr colspan="3"></tr>
        <tr>
            <td></td>
            <td colspan="2" style="text-align:center;background:#000000;color:#ffffff;;font-size:16px;">Event Schedule Details</td>
        </tr>

        @if($schedule)

        <tr>
            <td></td>
            <td style="text-align:center;font-size:14px;font-weight:bold;background-color:#cecece;border:1px solid black">Event Company</td>
            <td style="text-align:center;font-size:12px;font-weight:400;border:1px solid black">{{ $schedule->client->property_name }}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center;font-size:12px;font-weight:bold;background-color:#cecece;border:1px solid black">From</td>
            <td style="text-align:center;font-size:12px;font-weight:400;border:1px solid black">{{ $schedule->schedule_from }}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center;font-size:12px;font-weight:bold;background-color:#cecece;border:1px solid black">Event Name </td>
            <td style="text-align:center;font-size:12px;font-weight:400;border:1px solid black">{{ $schedule->event_name }}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center;font-size:12px;font-weight:bold;background-color:#cecece;border:1px solid black">Date of Event </td>
            <td style="text-align:center;font-size:12px;font-weight:400;border:1px solid black">{{\Carbon\Carbon::parse($schedule['start_date'])->format('M d ,Y').' To '.\Carbon\Carbon::parse($schedule['end_date'])->format('M d ,Y')}}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center;font-size:12px;font-weight:bold;background-color:#cecece;border:1px solid black">Type of Event </td>
            <td style="text-align:center;font-size:12px;font-weight:400;border:1px solid black">{{ $schedule->event_type }}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center;font-size:12px;font-weight:bold;background-color:#cecece;border:1px solid black">Timmings </td>
            <td style="text-align:center;font-size:12px;font-weight:400;border:1px solid black">{{\Carbon\Carbon::parse($schedule['start_time'])->format('H:i a').' To '.\Carbon\Carbon::parse($schedule['end_time'])->format('H:i a')}}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center;font-size:12px;font-weight:bold;background-color:#cecece;border:1px solid black">Security Required</td>
            <td style="text-align:center;font-size:12px;font-weight:400;border:1px solid black;">{{ $schedule->total_staff }}</td>
        </tr>
        <tr>
            <td></td>
            <td style="text-align:center;font-size:12px;font-weight:bold;background-color:#cecece;border:1px solid black">Contact Person</td>
            <td style="text-align:center;font-size:12px;font-weight:400;border:1px solid black">{{ $schedule->contact_person }}</td>
        </tr>
        @endif
    </thead>
</table>

<table>
    <thead>
        <tr></tr>
        <tr>
            <th style="font-size:14px;font-weight:bold;text-align:center;background:#000000;color:#ffffff;">No.</th>
            <th style="font-size:14px;font-weight:bold;text-align:center;background:#000000;color:#ffffff;">Name</th>
            <th style="font-size:14px;font-weight:bold;text-align:center;background:#000000;color:#ffffff;">SIRA No.</th>
            <th style="font-size:14px;font-weight:bold;text-align:center;background:#000000;color:#ffffff;">Contact No.</th>
            <th style="font-size:14px;font-weight:bold;text-align:center;background:#000000;color:#ffffff;">Start Time</th>
            <th style="font-size:14px;font-weight:bold;text-align:center;background:#000000;color:#ffffff;">End Time</th>
            <th style="font-size:14px;font-weight:bold;text-align:center;background:#000000;color:#ffffff;">Signature</th>
        </tr>
        <tr>
        </tr>
    </thead>

    <tbody>
        @if($staff_schedule_details)
        @foreach($staff_schedule_details as $key => $obj)
        <tr>
            <td style="font-size:12px;text-align:center;">{{$key+1}}</td>
            <td style="font-size:12px;text-align:center;">{{$obj->staff->name}}</td>
            <td style="font-size:12px;text-align:center;">{{$obj->staff->sira_id_number}}</td>
            <td style="font-size:12px;text-align:center;">{{$obj->staff->contact_number}}</td>
            <td style="font-size:12px;text-align:center;">{{$obj->start_time}}</td>
            <td style="font-size:12px;text-align:center;">{{$obj->end_time}}</td>
            <td style="font-size:12px;text-align:center;"></td>
        </tr>
        @endforeach
        @endif
    </tbody>
</table>