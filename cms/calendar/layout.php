<?php header("Content-Type: text/html; charset=utf-8");
header('Expires: Thu, 19 Nov 1981 08:52:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
	$month = $_POST['month'];
		$year = $_POST['year'];

		if(empty($_POST['day'])) {
		$thisDate = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		} else {
		$thisDate=strtotime(str_replace("-"," ",$_POST['day']));
		//$thisDate = mktime(0, 0, 0, date("m", $querydate), date("d", $querydate), date("Y", $querydate));
		}

		echo '<div style="margin-bottom:8px;padding-bottom:4px;border-bottom:1px solid #cdcdcd;">
					<form name="changeCalendarDate">
						<select id="ccMonth" onChange="startCalendar($(\'#ccMonth\').val(), $(\'#ccYear\').val())">';

						for($i=1; $i<=12; $i++){
							$monthMaker = mktime(0, 0, 0, $i, 1, 2006);
							if($month > 0) {
								if($month == $i) {
									$sel = 'selected';
								} else {
									$sel = '';
								}
							} else {
								if(date("m", $thisDate) == $i) {
									$sel = 'selected';
								} else {
									$sel = '';
								}
							}

							echo '<option value="'. $i .'" '. $sel .'>'. date("F", $monthMaker) .'</option>';
						}

				echo '</select>
						&nbsp;
						<select id="ccYear" onChange="startCalendar($(\'#ccMonth\').val(), $(\'#ccYear\').val())">';

						$yStart = 2002;
						$yEnd = ($yStart + 20);
						for($i=$yStart; $i<$yEnd; $i++)
						{
							if($year > 0) {
								if($year == $i) {
									$sel = 'selected';
								} else {
									$sel = '';
								}
							} else {
								if(date("Y", $thisDate) == $i) {
									$sel = 'selected';
								} else {
									$sel = '';
								}
							}
							echo '<option value="'. $i .'" '. $sel .'>'. $i .'</option>';
						}

				echo '</select>
					</form>
				</div>';

		// Display the week days.
		echo '<div class="clear-block"><div class="calendarFloat" style="background-color: transparent;">M</div>
				<div class="calendarday" style="background-color: transparent;">T</div>
				<div class="calendarday" style="background-color: transparent;">W</div>
				<div class="calendarday" style="background-color: transparent;">T</div>
				<div class="calendarday" style="background-color: transparent;">F</div>
				<div class="calendarday" style="background-color: #E6E3E0;">S</div>
				<div class="calendarday" style="background-color: #E6E3E0;">S</div></div>';
				$today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
		// Show the calendar.
		for($i=0; $i<date("t", $thisDate); $i++)
		{
			$thisDay = ($i + 1);
			if(($month == 0) || ($year == 0)) {
				$finalDate = mktime(0, 0, 0, date("m"), $thisDay, date("Y"));
				$fdf = mktime(0, 0, 0, date("m"), 1, date("Y"));
				$month = date("m");
				$year = date("Y");
			} else {
				$finalDate = mktime(0, 0, 0, $month, $thisDay, $year);
				$fdf = mktime(0, 0, 0, $month, 1, $year);
			}


			// Skip some cells to take into account for the weekdays.
			if($i == 0) {
				$firstDay = date("w", $fdf);
				$skip = ($firstDay - 1);
				if($skip < 0) { $skip = 6; }

				for($s=0; $s<$skip; $s++)
				{
					echo '<div class="calendarFloat" style="border: 1px solid transparent;">&nbsp;</div>';
				}
			}


			// <span style="position: relative; top: '. $tTop .'; left: 1px;">'. $thisDay .'</span>

			// Display the day.
			$dateastext=date("Y/m/d",$finalDate);
			switch($_GET['f']){
			case "history":
			$querypage="history/";
			break;
			case "calendar":
			$querypage="calendar/";
			break;
			default:
			$querypage="calendar/";
			}
			if($_GET['f']=='history' && $thisDate < $finalDate){
			$makeday= '<div class="calendarFloat ';
			if($thisDate == $finalDate) {
				$makeday=$makeday."thisday";
			}else{
			if((date("w", $finalDate) == 0) || (date("w", $finalDate) == 6)) {
				$makeday=$makeday."wknd";
			}
			}
			$makeday=$makeday.'" id="calendarDay_'. $thisDay .'">
						<span style="position: relative; top: '. $tTop .'; left: 1px;">'. $thisDay .'</span>
					</div>';
			}else{
						$makeday= '<a href="http://www.virtualtextileproject.org/'.$querypage.$dateastext.'/"><div class="calendarFloat ';
			if($thisDate == $finalDate) {
				$makeday=$makeday."thisday";
			}else{
			if((date("w", $finalDate) == 0) || (date("w", $finalDate) == 6)) {
				$makeday=$makeday."wknd";
			}
			}
			$makeday=$makeday.'" id="calendarDay_'. $thisDay .'">
						<span style="position: relative; top: '. $tTop .'; left: 1px;">'. $thisDay .'</span>
					</div></a>';
			}
					echo $makeday;
		}
?>