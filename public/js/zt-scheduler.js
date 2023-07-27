/**
 * Created by ZeeDev on 7/30/2019.
 */
function getMonths(number, string) {
    var ar = [];
    var start = moment(number, string);
    for (var end = moment(start).add(1, 'month'); start.isBefore(end); start.add(1, 'day')) {
        //ar.push(start.format('D-ddd-MMM-YYYY'));
        ar.push({
            format1: start.format('D-ddd-MMM-YYYY'),
            format2: start.format('YYYY-MM-D'),
            format3: start.format('MMM D'),
            format4: start.format('ddd'),
            format5: start.format('D'),
        });
    }
    return ar;
}

function getDates(start_date, end_date) {
    var ar = [];
    var start = moment(start_date);
    for (var end = moment(end_date).add(1, 'day'); start.isBefore(end); start.add(1, 'day')) {
        //ar.push(start.format('D-ddd-MMM-YYYY'));
        ar.push({
            format1: start.format('D-ddd-MMM-YYYY'),
            format2: start.format('YYYY-MM-D')
        });
    }
    console.log(ar);
    return ar;
}


function duration(start_date, val) {
    var ar = [];
    var start = moment(start_date);
    for (var end = moment(start_date).add(val, 'week'); start.isBefore(end); start.add(1, 'day')) {
        ar.push({
            format1: start.format('D-ddd-MMM-YYYY'),
            format2: start.format('YYYY-MM-D'),
            format3: start.format('MMM D'),
            format4: start.format('ddd'),
            format5: start.format('D'),
        });
    }
    return ar;
}

function getHours(start_time, end_time) {
    var start = moment.utc(start_time, "HH:mm");
    var end = moment.utc(end_time, "HH:mm");
    var hour = moment.utc(end.diff(start)).format("HH");
    var minute = moment.utc(end.diff(start)).format("mm");
    var duration = parseFloat(minute / 60) + parseFloat(hour) || 0;
    return parseFloat(duration);
}