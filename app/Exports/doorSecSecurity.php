<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Guarding;
use \Carbon;

class doorSecSecurity implements WithEvents 
{
    /**
    * @return \Illuminate\Support\Collection
    */
	
	 function __construct($id) {
			$this->id = $id;
	 }
	public function registerEvents(): array
    {
		  // dd($this->id);
		  
        $guarding_id = $this->id;
        $data = Guarding::find($guarding_id);
		// $date_today 		= new Date('Y-m-d');
		$export_date 		= Carbon\Carbon::now()->format('l jS \\of F Y h:i:s A');
		// dd($export_date);
		 // day 
		$working_days		= $data->require_staff_day;
		$day_start 			=  Carbon\Carbon::parse($data->day_start_time);
		$day_end 			= Carbon\Carbon::parse($data->day_end_time);
		$working_day_time	= Carbon\Carbon::parse($data->day_start_time)->format('H:i A') .' TO '. Carbon\Carbon::parse($data->day_end_time)->format('H:i A');
		$working_day_diff  	= gmdate('h:i',$day_start->diffInSeconds($day_end));
		//night 
		$working_nigts 		= $data->require_staff_night;
		$working_night_time	= Carbon\Carbon::parse($data->night_start_time)->format('H:i A') .' TO '. Carbon\Carbon::parse($data->night_end_time)->format('H:i A');
		$night_start 		=  Carbon\Carbon::parse($data->night_start_time);
		$night_end 			= Carbon\Carbon::parse($data->night_end_time);
		$working_night_diff = gmdate('h:i',$night_start->diffInSeconds($night_end));
		// $working_night_diff = $night_start->diffInHours($night_end);
		
		
		$schedule_start = Carbon\Carbon::parse($data->start_date);
		$start_month    = $schedule_start->format('F') . ' ' .$schedule_start->year ;
		$schedule_end = Carbon\Carbon::parse($data->end_date);
		$end_month 	  = $schedule_end->format('F') . ' '. $schedule_end->year;
		
		$headline = $start_month . ' To '. $end_month;
		$total_days = $schedule_end->diffInDays($schedule_start);
		$middleSideBarHeader = array();
		$leftSideBarData = array();
		$c1 = 'A';
		//$last_excel_column = +$total_days
		$str = '';
		$first_column = 'F';
		$male=0;
		$female=0;
		$sec_officer = 0;
		$day_column = array();
		if($total_days){
			foreach($data->guarding_schedule as $key=> $gss){
				$in_night = $gss->night?json_decode($gss->night):'';
				$in_day = $gss->day?json_decode($gss->day):'';
				$in_afternon = $gss->afternoon?json_decode($gss->afternoon):'';
				$in_lateday = $gss->late_day?json_decode($gss->late_day):'';
				$in_evening = $gss->evening?json_decode($gss->evening):'';
				$in_absent 	= $gss->absent?json_decode($gss->absent):'';
				$in_sickleave = $gss->sick_leave?json_decode($gss->sick_leave):'';
				$in_annual_leave = $gss->annual_leave?json_decode($gss->annual_leave):'';
				$in_emergency_leave = $gss->emergency_leave?json_decode($gss->emergency_leave):'';
				$in_day_off 	= $gss->day_off?json_decode($gss->day_off):'';
				$in_off_working_night = $gss->off_working_night?json_decode($gss->off_working_night):'';
				$in_off_working_day   = $gss->off_working_day?json_decode($gss->off_working_day):'';
				$in_training 	= $gss->training?json_decode($gss->training):'';
				$in_overtime	= $gss->overtime?json_decode($gss->overtime):'';
				$in_event_day 	= $gss->event_day?json_decode($gss->event_day):'';
				$in_public_holiday 	= $gss->public_holiday?json_decode($gss->	public_holiday):'';
				$in_unpaid_leave 	= $gss->unpaid_leave?json_decode($gss->unpaid_leave):'';
				
				// dd($in_night);
				$str = 'F';
				
				if($gss->staffschedule->staff->gender == 'female' && $gss->staffschedule->staff->sira_type_id == 6){
					$female = $female + 1;
				}else if($gss->staffschedule->staff->gender == 'male'|| $gss->staffschedule->staff->sira_type_id == 5 ||$gss->staffschedule->staff->sira_type_id == 4 ||$gss->staffschedule->staff->sira_type_id == 1 ){
					$male = $male+1;
				}
				if($gss->staffschedule->staff->sira_type_id == 1 || $gss->staffschedule->staff->sira_type_id == 4 || $gss->staffschedule->staff->sira_type_id == 6 || $gss->staffschedule->staff->sira_type_id == 5){
					$sec_officer = $sec_officer+1;
				}
				$left_data = array(
								"SN" => $key+1,
								"Assignment" => $gss->staffschedule->sira_type->type, 
								"Name" => $gss->staffschedule->staff->name, 
								"Id_no" => "G-00".$gss->staffschedule->staff->id);
				array_push($leftSideBarData,$left_data);
				for($i = 0;$i<=$total_days;$i++){
					$today = Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('d-m-Y');
					$day = Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('D');
					$date = Carbon\Carbon::parse($data->start_date)->add($i,'days')->format('d');
					$attend = 'N';
					// dd($in_night);
					if(is_array($in_night)==true && in_array($today,$in_night)==true){
						$attend = 'N';
					}elseif(is_array($in_day)==true && in_array($today,$in_day)==true){
						$attend = 'D';
					}if(is_array($in_afternon)==true && in_array($today,$in_afternon)==true){
						$attend = 'A';
					}if(is_array($in_lateday)==true && in_array($today,$in_lateday)==true){
						$attend = 'Ld';
					}if(is_array($in_evening)==true && in_array($today,$in_evening)==true){
						$attend = 'E';
					}if(is_array($in_absent)==true && in_array($today,$in_absent)==true){
						$attend = 'ABS';
					}if(is_array($in_sickleave)==true && in_array($today,$in_sickleave)==true){
						$attend = 'SL';
					}if(is_array($in_annual_leave)==true && in_array($today,$in_annual_leave)==true){
						$attend = 'AL';
					}if(is_array($in_emergency_leave)==true && in_array($today,$in_emergency_leave)==true){
						$attend = 'EL';
					}if(is_array($in_day_off)==true && in_array($today,$in_day_off)==true){
						$attend = 'OFF';
					}if(is_array($in_off_working_night)==true && in_array($today,$in_off_working_night)==true){
						$attend = 'OWN';
					}if(is_array($in_off_working_day)==true && in_array($today,$in_off_working_day)==true){
						$attend = 'OWD';
					}if(is_array($in_training)==true && in_array($today,$in_training)==true){
						$attend = 'T';
					}if(is_array($in_overtime)==true && in_array($today,$in_overtime)==true){
						$attend = 'OT';
					}if(is_array($in_public_holiday)==true && in_array($today,$in_public_holiday)==true){
						$attend = 'PH';
					}if(is_array($in_event_day)==true && in_array($today,$in_event_day)==true){
						$attend = 'ED';
					}if(is_array($in_unpaid_leave)==true && in_array($today,$in_unpaid_leave)==true){
						$attend = 'UL';
					}
					//echo $gss->id."___ ".$attend."<br>";
					$middleSideBarHeader[$gss->id][$i] = array(
										"week_days" => $day, 
										'column_name' => $str,
										"atendence_day" => $attend,
										'date'=>$date
									);
					//array_push($middleSideBarHeader,$middle_data);
					array_push($day_column,$str);
					$str = ++$str;
				}
			}
		}
		// echo "<pre>";
			// foreach ($middleSideBarHeader as $y => $z){
				// dd($z);
					// foreach ($z as $k => $v){
					// echo $v['column_name'].'<br>';	
					// }
			// }
			//die;
		 // dd($middleSideBarHeader);
		$abs_column 	= $str;
		$ot_column 		= ++$str;
		$sl_column 		= ++$str;
		$off_column 	= ++$str;
		$last_column 	= $str;
		// dd($data->guarding_schedule);
		// Styling STARTS
		$styleArray = array(
			'fill' => [
					'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
					'color' => [
					'argb' => 'ff1944',
					]
	        ],
			'font' => [
                'name'      =>  'Calibri',
                'size'      =>  13,
                'bold'      =>  true,
                'color' => ['argb' => 'FFFFFF'],
            ],
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ]
            ]
			);

		$styleArrayBlueClr = array(
						'fill' => [
	        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
	        'color' => [
	        'argb' => '5151af',
	        ]
	        ],
			'font' => [
                'name'      =>  'Calibri',
                'size'      =>  10,
                'bold'      =>  true,
                'color' => ['argb' => 'FFFFFF'],
            ],
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ]
            ]
			);

		$styleArrayLightBlueClr = array(
						'fill' => [
	        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
	        'color' => [
	        'argb' => '649dcf',
	        ]
	        ],
			'font' => [
                'name'      =>  'Calibri',
                'size'      =>  10,
                'bold'      =>  true,
                'color' => ['argb' => 'FFFFFF'],
            ],
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ]
            ]
			);

		$styleArrayGrey = array(
			'fill' => [
		        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
		        'color' => [
		        'argb' => 'cfcfdd',
		        ]
	        ]
			);

		$styleArrayBlueColor = array(
						'fill' => [
	        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
	        'color' => [
	        'argb' => '5151af',
	        ]
	        ],
			'font' => [
                'name'      =>  'Calibri',
                'size'      =>  11,
                'bold'      =>  true,
                'color' => ['argb' => 'FFFFFF'],
            ],
			'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                    'color' => ['argb' => '000000'],
                ]
            ]
			);

		$styleArrayFont = array(
			'font' => [
                'name'      =>  'Calibri',
                'size'      =>  11,
                'bold'      =>  true,
                'color' => ['argb' => 'ff1944'],
            ]
		);

		$styleArrayFontBlack = array(
			'font' => [
                'name'      =>  'Calibri',
                'size'      =>  11,
                'bold'      =>  true,
                'color' => ['argb' => '000000'],
            ]
		);
// Styling ENDS

	// $leftSideBarData = array(
			// array("SN" => '0', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '1', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '2', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '3', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '4', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '5', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '6', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '7', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '8', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '9', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '10', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '11', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
			// array("SN" => '12', "Assignment" => "Doosec Coordinator", "Name" => "Mirza Waqas Baig", "Id_no" => "CCA-01"),
		// );

	$leftSideBarData1 = array(
			array("days" => 'Total day off per Day'),
			array("days" => 'Total Sick Leave per Day'),
			array("days" => 'Total Absent per day'),
			array("days" => 'Total of security officers both shifts'),
			array("days" => 'Total of Male security officers both shifts'),
			array("days" => 'Total of FeMale security officers both shifts'),
			
		);

	// $middleSideBarHeader = array(
			// array("week_days" => 'Su', 'column_name' => 'F', "atendence_day" => 'P'),
			// array("week_days" => 'M', 'column_name' => 'G', "atendence_day" => 'P'),
			// array("week_days" => 'Tu', 'column_name' => 'H', "atendence_day" => 'P'),
			// array("week_days" => 'W', 'column_name' => 'I', "atendence_day" => 'P'),
			// array("week_days" => 'TH', 'column_name' => 'J', "atendence_day" => 'P'),
			// array("week_days" => 'F', 'column_name' => 'K', "atendence_day" => 'P'),
			// array("week_days" => 'S', 'column_name' => 'L', "atendence_day" => 'P'),
			// array("week_days" => 'SU', 'column_name' => 'M', "atendence_day" => 'P'),
			// array("week_days" => 'Su', 'column_name' => 'N', "atendence_day" => 'P'),
			// array("week_days" => 'M', 'column_name' => 'O', "atendence_day" => 'D'),
			// array("week_days" => 'Tu', 'column_name' => 'P', "atendence_day" => 'D'),
			// array("week_days" => 'W', 'column_name' => 'Q', "atendence_day" => 'D'),
			// array("week_days" => 'TH', 'column_name' => 'R', "atendence_day" => 'D'),
			// array("week_days" => 'F', 'column_name' => 'S', "atendence_day" => 'D'),
			// array("week_days" => 'S', 'column_name' => 'T', "atendence_day" => 'D'),
			// array("week_days" => 'SU', 'column_name' => 'U', "atendence_day" => 'N'),
			// array("week_days" => 'Su', 'column_name' => 'V', "atendence_day" => 'N'),
			// array("week_days" => 'M', 'column_name' => 'W', "atendence_day" => 'N'),
			// array("week_days" => 'Tu', 'column_name' => 'X', "atendence_day" => 'N'),
			// array("week_days" => 'W', 'column_name' => 'Y', "atendence_day" => 'N'),
			// array("week_days" => 'TH', 'column_name' => 'Z', "atendence_day" => 'O'),
			// array("week_days" => 'F', 'column_name' => 'L', "atendence_day" => 'O'),
			// array("week_days" => 'S', 'column_name' => 'AA', "atendence_day" => 'O'),
			// array("week_days" => 'SU', 'column_name' => 'AB', "atendence_day" => 'O'),
			// array("week_days" => 'Su', 'column_name' => 'AC', "atendence_day" => 'O'),
			// array("week_days" => 'M', 'column_name' => 'AD', "atendence_day" => 'O'),
			// array("week_days" => 'Tu', 'column_name' => 'AE', "atendence_day" => 'P'),
			// array("week_days" => 'W', 'column_name' => 'AF', "atendence_day" => 'P'),
			// array("week_days" => 'TH', 'column_name' => 'AG', "atendence_day" => 'P'),
			// array("week_days" => 'F', 'column_name' => 'AH', "atendence_day" => 'P'),
			// array("week_days" => 'S', 'column_name' => 'AI', "atendence_day" => 'P'),
			// array("week_days" => 'SU', 'column_name' => 'AJ', "atendence_day" => 'P'),
			// array("week_days" => 'Su', 'column_name' => 'AK', "atendence_day" => 'P'),
			// array("week_days" => 'M', 'column_name' => 'AL', "atendence_day" => 'P'),
		// );
		$schedule_count = $data->guarding_schedule??0;
		$rightSideBarData = count($schedule_count);
		$next_column = 3+$rightSideBarData;
		$attendance_last_day = count($day_column);
		$last_row = $next_column +11;
		// dd($next_column);

        return [
            AfterSheet::class => function(AfterSheet $event) use ($sec_officer,$female,$male,$headline,$export_date,$working_night_diff,$working_day_diff,$working_night_time, $working_day_time,$working_days,$working_nigts,$last_row,$attendance_last_day,$next_column,$abs_column,$off_column,$ot_column,$sl_column,$day_column,$first_column,$last_column,$styleArray, $styleArrayFont, $styleArrayFontBlack, $leftSideBarData, $leftSideBarData1, $middleSideBarHeader, $rightSideBarData, $styleArrayBlueColor, $styleArrayGrey, $styleArrayBlueClr, $styleArrayLightBlueClr) {
                $rows = $event->sheet->getDelegate()->toArray();
				$spreadsheet = $event->sheet->getParent()->getProperties();
				
				$event->sheet->setCellValue('A1', 'Export Date')->mergeCells('A1:D1')
				->getStyle('A1:D1')
				->applyFromArray($styleArray)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('A2', $export_date)->mergeCells('A2:D2')
				->getStyle('A2:D2')
				->applyFromArray($styleArrayFont)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('E2', 'Day ->')
				->getStyle('E2')
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			//left sidebar STARTS
				$event->sheet->setCellValue('A3', 'SN.')
				->getStyle('A3')
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('B3', 'Assignment')
				->getStyle('B3')
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('C3', 'NAME')
				->getStyle('C3')
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('D3', 'ID No.')
				->getStyle('D3')
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('E3', 'ID No.')
				->getStyle('E3')
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('A'.($next_column+1), 'SHIFTS')->mergeCells('A'.($next_column+1).':A'.($next_column+2))
				->getStyle('A'.($next_column+1).':A'.($next_column+2))
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('B'.($next_column+1), 'Day Shifts: '. $working_day_time.' Hrs '. $working_day_diff .' Hrs ')->mergeCells('B'. ($next_column+1).':E'.($next_column+1))
				->getStyle('B'.($next_column+1).':E'.($next_column+1))
				->applyFromArray($styleArrayBlueColor)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('B'.($next_column+2), 'Night Shifts: '.$working_night_time.' Hrs '. $working_night_diff .' Hrs ')->mergeCells('B'.($next_column+2) .':E'.($next_column+2))
				->getStyle('B'.($next_column+2) .':E'.($next_column+2))
				->applyFromArray($styleArrayBlueColor)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('B'.($next_column+3), 'TOTAL')->mergeCells('B'.($next_column+3) .':B'.($next_column+4))
				->getStyle('B'.($next_column+3) .':B'.($next_column+4))
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('C'.($next_column+3), 'DAY SHIFT -'.$working_days)->mergeCells('C'.($next_column+3) .':E'.($next_column+3))
				->getStyle('C'.($next_column+3) .':E'.($next_column+3))
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('C'.($next_column+4), 'VARIANCE / SHORTAGE')->mergeCells('C'.($next_column+4) .':E'.($next_column+4))
				->getStyle('C'.($next_column+4) .':E'.($next_column+4))
				->applyFromArray($styleArrayFont)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('B'.($next_column+5), 'TOTAL')->mergeCells('B'.($next_column+5) .':B'.($next_column+6))
				->getStyle('B'.($next_column+5) .':B'.($next_column+6))
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('C'.($next_column+5), 'Night SHIFT -'.$working_nigts )->mergeCells('C'.($next_column+5) .':E'.($next_column+5))
				->getStyle('C'.($next_column+5) .':E'.($next_column+5))
				->applyFromArray($styleArrayFontBlack)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('C'.($next_column+6), 'VARIANCE / SHORTAGE')->mergeCells('C'.($next_column+6) .':E'.($next_column+6))
				->getStyle('C'.($next_column+6) .':E'.($next_column+6))
				->applyFromArray($styleArrayFont)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);




//problem start here that corrupt the file
				// $event->sheet->setCellValue('B'.($next_column+5), 'TOTAL')->mergeCells('B'.($next_column+5) .':B'.($next_column+6))
				// ->getStyle('B'.($next_column+5) .':B'.($next_column+6))
				// ->applyFromArray($styleArrayFontBlack)
				// ->getAlignment()
				// ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				// ->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				// $event->sheet->setCellValue('C'.($next_column+5) , 'Night SHIFT - '.$working_nigts)->mergeCells('C'.($next_column+5) .':E'.($next_column+5))
				// ->getStyle('C'.($next_column+5) .':E'.($next_column+5))
				// ->applyFromArray($styleArrayFontBlack)
				// ->getAlignment()
				// ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				// ->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				// $event->sheet->setCellValue('C'.($next_column+6), 'VARIANCE / SHORTAGE')->mergeCells('C'.($next_column+6) .':E'.($next_column+6))
				// ->getStyle('C'.($next_column+6) .':E'.($next_column+6) )
				// ->applyFromArray($styleArrayFont)
				// ->getAlignment()
				// ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				// ->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue('A'.($next_column+3) , '')->mergeCells('A'.($next_column+3) .':A'.($next_column+11))
				->getStyle('A'.($next_column+3) .':A'.($next_column+11))
				->applyFromArray($styleArrayGrey);
//problem end here that corrupt the file

			//left sidebar ENDS


				$event->sheet->setCellValue('E1', $headline)->mergeCells('E1:'.$last_column.'1')
				->getStyle('E1:'.$last_column.'1')
				->applyFromArray($styleArray)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


			//Right sidebar STARTS
				$event->sheet->setCellValue($abs_column.'2', 'ABS')->mergeCells($abs_column.'2:'.$abs_column.'3')
				->getStyle($abs_column.'2:'.$abs_column.'3')
				->applyFromArray($styleArray)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue($ot_column.'2', 'OVER'."\n".' TIME')->mergeCells($ot_column.'2:'.$ot_column.'3')
				->getStyle($ot_column.'2:'.$ot_column.'3')
				->applyFromArray($styleArrayBlueClr)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue($sl_column.'2', 'SL')->mergeCells($sl_column.'2:'.$sl_column.'3')
				->getStyle($sl_column.'2:'.$sl_column.'3')
				->applyFromArray($styleArrayBlueClr)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue($off_column.'2', 'OFF ')->mergeCells($off_column.'2:'.$off_column.'3')
				->getStyle($off_column.'2:'.$off_column.'3')
				->applyFromArray($styleArrayBlueClr)
				->getAlignment()
				->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


				// $event->sheet->setCellValue($abs_column.''.($next_column+1), '')->mergeCells($abs_column.''.($next_column+1).':'.$last_column.''.($next_column +4) )
				// ->getStyle($abs_column.''.($next_column+1) .':'.$last_column.''.($next_column+4))
				// ->applyFromArray($styleArrayGrey);
				
				//Unpaid/Emergency Leaves
				$event->sheet->setCellValue($abs_column.''.($next_column+1), 'Unpaid/Emergency Leaves')->mergeCells($abs_column.''.($next_column+1) .':'.$last_column.''.($next_column+1))
				->getStyle($abs_column.''.($next_column+1) .':'.$last_column.''.($next_column+1))
				->applyFromArray($styleArrayLightBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					
				
					
				$event->sheet->setCellValue($abs_column.''.($next_column+2), '=COUNTIF('.$first_column.'4:'.$day_column[$attendance_last_day-1].$next_column.',"=UL") + COUNTIF('.$first_column.'4:'.$day_column[$attendance_last_day-1].$next_column.',"=EL")')
				->mergeCells($abs_column.''.($next_column+2).':'.$last_column.''.($next_column+2))
				->getStyle($abs_column.''.($next_column+2).':'.$last_column.''.($next_column+2))
				->applyFromArray($styleArrayBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				//End Unpaid/Emergency Leaves
				//Special Shifts 
				$event->sheet->setCellValue($abs_column.''.($next_column+3), 'Special Shifts')->mergeCells($abs_column.''.($next_column+3) .':'.$last_column.''.($next_column+3))
				->getStyle($abs_column.''.($next_column+3) .':'.$last_column.''.($next_column+3))
				->applyFromArray($styleArrayLightBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					
				
					
				$event->sheet->setCellValue($abs_column.''.($next_column+4), '=COUNTIF('.$first_column.'4:'.$day_column[$attendance_last_day-1].$next_column.',"=ED") + COUNTIF('.$first_column.'4:'.$day_column[$attendance_last_day-1].$next_column.',"=A")')
				->mergeCells($abs_column.''.($next_column+4).':'.$last_column.''.($next_column+4))
				->getStyle($abs_column.''.($next_column+4).':'.$last_column.''.($next_column+4))
				->applyFromArray($styleArrayBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
				//special End 
				
				$event->sheet->setCellValue($abs_column.''.($next_column+5), 'Annual Leave')->mergeCells($abs_column.''.($next_column+5) .':'.$last_column.''.($next_column+5))
				->getStyle($abs_column.''.($next_column+5) .':'.$last_column.''.($next_column+5))
				->applyFromArray($styleArrayLightBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
					
				//Annual leaves formula
					
				$event->sheet->setCellValue($abs_column.''.($next_column+6), '=COUNTIF('.$first_column.'4:'.$day_column[$attendance_last_day-1].$next_column.',"=AL")')
				->mergeCells($abs_column.''.($next_column+6).':'.$last_column.''.($next_column+6))
				->getStyle($abs_column.''.($next_column+6).':'.$last_column.''.($next_column+6))
				->applyFromArray($styleArrayBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue($abs_column.''.($next_column+7), 'Total Absent')->mergeCells($abs_column.''.($next_column+7).':'.$last_column.''.($next_column+7))
				->getStyle($abs_column.''.($next_column+7).':'.$last_column.''.($next_column+7))
				->applyFromArray($styleArrayLightBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue($abs_column.''.($next_column+8), '=COUNTIF('.$first_column.'4:'.$day_column[$attendance_last_day-1].$next_column.',"=ABS")')
				->mergeCells($abs_column.''.($next_column+8).':'.$last_column.''.($next_column+8))
				->getStyle($abs_column.''.($next_column+8).':'.$last_column.''.($next_column+8))
				->applyFromArray($styleArrayBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue($abs_column.''.($next_column+9), 'O.T Days')->mergeCells($abs_column.''.($next_column+9).':'.$last_column.''.($next_column+9))
				->getStyle($abs_column.''.($next_column+9).':'.$last_column.''.($next_column+9))
				->applyFromArray($styleArrayLightBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue($abs_column.''.($next_column+10), '=COUNTIF('.$first_column.'4:'.$day_column[$attendance_last_day-1].$next_column.',"=OT") + COUNTIF('.$first_column.'4:'.$day_column[$attendance_last_day-1].$next_column.',"=OWD") + COUNTIF('.$first_column.'4:'.$day_column[$attendance_last_day-1].$next_column.',"=OWN")')
				->mergeCells($abs_column.''.($next_column+10).':'.$last_column.''.($next_column+10))
				->getStyle($abs_column.''.($next_column+10).':'.$last_column.''.($next_column+10))
				->applyFromArray($styleArrayBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue($abs_column.''.($next_column+11), 'Total Training')
				->mergeCells($abs_column.''.($next_column+11).':'.$last_column.''.($next_column+11))
				->getStyle($abs_column.''.($next_column+11).':'.$last_column.''.($next_column+11))
				->applyFromArray($styleArrayLightBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

				$event->sheet->setCellValue($abs_column.''.($next_column+12), '=COUNTIF('.$first_column.'4:'.$day_column[$attendance_last_day-1].$next_column.',"=T")')
				->mergeCells($abs_column.''.($next_column+12).':'.$last_column.''.($next_column+12))
				->getStyle($abs_column.''.($next_column+12).':'.$last_column.''.($next_column+12))
				->applyFromArray($styleArrayBlueClr)
				->getAlignment()
				->setHORIZONTAL(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
//Right sidebar ENDS

//setWidth of Excel STARTS
				$event->sheet->getDelegate()->getColumnDimension('A')->setWidth(6);
				$event->sheet->getDelegate()->getColumnDimension($abs_column)->setWidth(6);
				$event->sheet->getDelegate()->getColumnDimension($sl_column)->setWidth(6);
				$event->sheet->getDelegate()->getColumnDimension($off_column)->setWidth(6);
				// $event->sheet->getDelegate()->getColumnDimension('AQ')->setWidth(6);
				$event->sheet->getDelegate()->getColumnDimension('B')->setWidth(17);
				$event->sheet->getDelegate()->getColumnDimension('C')->setWidth(16);
				$change_column_width = $day_column;//['F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL'];
				foreach ($change_column_width as $key => $v) {
					$event->sheet->getDelegate()->getColumnDimension($v)->setWidth(4);
				}
//setWidth of Excel ENDS

				
				$row_number = $next_column +7;
                foreach ($leftSideBarData1 as $k => $v):
                        $event->sheet->setCellValue('B'.$row_number, $v['days'])->mergeCells('B'.$row_number.':'.'E'.$row_number);
					$row_number++;
                endforeach;

				$row_number = 2;
				if(count($middleSideBarHeader)):
                foreach ($middleSideBarHeader as $y => $z){
					foreach ($z as $k => $v){
							$event->sheet->setCellValue($v['column_name'].'2', $v['week_days'])
								->getStyle($v['column_name'].'2')
								->applyFromArray($styleArrayGrey);
							$event->sheet->setCellValue($v['column_name'].'3', $v['date'])
								->getStyle($v['column_name'].'3')
								->applyFromArray($styleArrayGrey);
							$event->sheet->setCellValue($v['column_name'].($row_number+2), $v['atendence_day']);
							
						   
					}
					$row_number++;
                }
				endif;
				$row_number = 4;
                foreach ($leftSideBarData as $k => $v):
                    $lastLetter = count($v);
                        $event->sheet->setCellValue('A'.$row_number, $v['SN']); 
                        $event->sheet->setCellValue('B'.$row_number, $v['Assignment']);
                        $event->sheet->setCellValue('C'.$row_number, $v['Name']);
                        $event->sheet->setCellValue('D'.$row_number, $v['Id_no']);
                        $event->sheet->setCellValue('E'.$row_number, $v['Id_no']);
					$row_number++;
                endforeach;
				 $row_number = $next_column+1;
				 	foreach ($middleSideBarHeader as $k => $z):
						foreach ($z as $k => $v):
								$event->sheet->setCellValue($v['column_name'].$row_number, $v['week_days'])
									->getStyle($v['column_name'].$row_number)
									->applyFromArray($styleArrayGrey);
									
								$event->sheet->setCellValue($v['column_name'].($row_number+1), $v['date'])
									->getStyle($v['column_name'].($row_number+1))
									->applyFromArray($styleArrayGrey);
								$event->sheet->setCellValue($v['column_name'].($row_number+2), '=COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=D") + COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=LD") + COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=OWD")');
								$event->sheet->setCellValue($v['column_name'].($row_number+3), '= (COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=D") + COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=LD") + COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=OWD") )- ('.$working_days.')');
								$event->sheet->setCellValue($v['column_name'].($row_number+4), '=COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=N") + COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=E") + COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=OWN")');
								$event->sheet->setCellValue($v['column_name'].($row_number+5), '= (COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=N")  + COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=E") + COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=OWN") )- ('.$working_nigts.')');
								$event->sheet->setCellValue($v['column_name'].($row_number+6), '= COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=OFF")');
								$event->sheet->setCellValue($v['column_name'].($row_number+7), '= COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=SL")');
								$event->sheet->setCellValue($v['column_name'].($row_number+8), '= COUNTIF('.$v['column_name'].'4:'.$v['column_name'].$next_column.',"=ABS")');
								$event->sheet->setCellValue($v['column_name'].($row_number+9), $rightSideBarData);
								$event->sheet->setCellValue($v['column_name'].($row_number+10), $male);
								$event->sheet->setCellValue($v['column_name'].($row_number+11), $female);

						endforeach;
					endforeach;
				$row_number = 4;
				
                for ($r=0;$r<$rightSideBarData;$r++):
                    $event->sheet->setCellValue($abs_column.''.$row_number, '=COUNTIF('.$first_column.$row_number.':'.$day_column[$attendance_last_day-1].$row_number.',"=ABS")');
                    $event->sheet->setCellValue($ot_column.''.$row_number, '=( COUNTIF('.$first_column.$row_number.':'.$day_column[$attendance_last_day-1].$row_number.',"=OT") + COUNTIF('.$first_column.$row_number.':'.$day_column[$attendance_last_day-1].$row_number.',"=OWN") + COUNTIF('.$first_column.$row_number.':'.$day_column[$attendance_last_day-1].$row_number.',"=OWD") )');
                    $event->sheet->setCellValue($sl_column.''.$row_number, '=COUNTIF('.$first_column.$row_number.':'.$day_column[$attendance_last_day-1].$row_number.',"=SL")');
                    $event->sheet->setCellValue($off_column.''.$row_number, '=COUNTIF('.$first_column.$row_number.':'.$day_column[$attendance_last_day-1].$row_number.',"=OFF")');
                    //$event->sheet->setCellValue('AQ'.$row_number, '=COUNTIF('.$first_column.$row_number.':'.$last_column.$row_number',"=A")');
					$row_number++;
                endfor;
            }
        ];
    }

}
