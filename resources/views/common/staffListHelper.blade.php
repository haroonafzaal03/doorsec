@if(isset($staff_not_schedule_today))
    <option value=""> Select</option>
    @foreach($staff_not_schedule_today as $obj)
        <option value="{{$obj->id}}" data-image="{{img($obj->picture)}}">{{$obj->name}} </option>
    @endforeach
@endif