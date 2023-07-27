<table>
    <thead>
    <tr colspan="3"></tr>
    <tr >
    <td colspan="2" style="height:20px;width:100px;text-align:center;background-color:#eae116;font-size:16px;">Event Schedule Details</td>
    </tr>

    @if($schedule)
    <tr>
                     <td style="font-size:14px;height:20px;font-weight:bold;width:30px;background-color: #cecece;border:1px solid black">To</td>
                     <td style="font-size:14px;height:20px;font-weight:400;width:50px;border:1px solid black">SIRA</td>
                    </tr>

                    <tr>

                     <td style="font-size:14px;height:20px;font-weight:bold;width:30px;background-color:#cecece;border:1px solid black">Event Company</td>
                     <td style="font-size:14px;height:20px;font-weight:400;width:50px;border:1px solid black">{{ $schedule->client->property_name }}</td>
                    </tr>
                    <tr>

                     <td style="font-size:14px;height:20px;font-weight:bold;width:30px;background-color:#cecece;border:1px solid black">From</td>
                     <td style="font-size:14px;height:20px;font-weight:400;width:50px;border:1px solid black">{{ $schedule->schedule_from }}</td>
                    </tr>
                    <tr>

                     <td style="font-size:14px;height:20px;font-weight:bold;width:30px;background-color:#cecece;border:1px solid black">Event Name	</td>
                     <td style="font-size:14px;height:20px;font-weight:400;width:50px;border:1px solid black">{{ $schedule->event_name }}</td>
                    </tr>
                    <tr>

                     <td style="font-size:14px;height:20px;font-weight:bold;width:30px;background-color:#cecece;border:1px solid black">Date of Event	</td>
                     <td style="font-size:14px;height:20px;font-weight:400;width:50px;border:1px solid black">{{\Carbon\Carbon::parse($schedule['start_date'])->format('M d ,Y').' To '.\Carbon\Carbon::parse($schedule['end_date'])->format('M d ,Y')}}</td>
                    </tr>
                    <tr>

                     <td style="font-size:14px;height:20px;font-weight:bold;width:30px;background-color:#cecece;border:1px solid black">Type of Event	</td>
                     <td style="font-size:14px;height:20px;font-weight:400;width:50px;border:1px solid black">{{ $schedule->event_type }}</td>
                    </tr>
                    <tr>

                     <td style="font-size:14px;height:20px;font-weight:bold;width:30px;background-color:#cecece;border:1px solid black">Timmings </td>
                     <td style="font-size:14px;height:20px;font-weight:400;width:50px;border:1px solid black">{{\Carbon\Carbon::parse($schedule['start_time'])->format('H:i a').' To '.\Carbon\Carbon::parse($schedule['end_time'])->format('H:i a')}}</td>
                    </tr>
                    <tr>

                     <td style="font-size:14px;height:20px;font-weight:bold;width:30px;background-color:#cecece;border:1px solid black">Security Required</td>
                     <td style="font-size:14px;height:20px;font-weight:400;width:50px;border:1px solid black;text-align:left">{{ $schedule->total_staff }}</td>
                    </tr>
                    <tr>

                     <td style="font-size:14px;height:20px;font-weight:bold;width:30px;background-color:#cecece;border:1px solid black">Contact Person</td>
                     <td style="font-size:14px;height:20px;font-weight:400;width:50px;border:1px solid black">{{ $schedule->contact_person }}</td>
                    </tr>
        @endif
    </thead>
</table>




<table>
    <thead>
    <tr ></tr>
    <tr>
    @if($tableColumns)
        @foreach($tableColumns as $key => $arr)
            <td style="font-size:14px;width:30px;height:20px;background-color:#eae116">{{ getStaffLabels($key) }}</td>
        @endforeach
    @endif
    </tr>
    </thead>

    <tbody>

    @if($tableColumns)
             @foreach($staff_schedule_details as $arr)
             <tr>
                @foreach($tableColumns as $key => $obj)
                       <td style="font-size:14px;width:50px;height:20px;">{{ $arr->staff->$key }}</td>
                @endforeach
             </tr>
            @endforeach
        @endif

    </tbody>
</table>











