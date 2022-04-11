<?php
// FormatTime
// GetSunrise, GetSunriseDegreesBelowHorizon
// GetSunset, GetSunsetDegreesBelowHorizon
// AddMinutes, SubtractMinutes
// GetProportionalHours, GetShaaZmanit 
// candlTimeSunset, candlTimeStras

function FormatTime($time) {
  $hour = $time[0];
  $min = $time[1];

//  $hourModulo12 = $hour % 12;
// if ($hourModulo12 == 0) $hourModulo12 = 12;

//  if ($hour >= 12)
//    $ampm = "PM";
//  else
//    $ampm = "AM";

//  if ($hourModulo12 < 10)
//    $hourStr = "0" . $hourModulo12;
//  else
//    $hourStr = $hourModulo12;
  if ($min < 10)
    $minStr = "0" . $min;
  else
    $minStr = $min;
  return $hour . ":" . $minStr;
}

function leap($y)
{
  if ($y % 400 == 0)
    return true;
  if ($y % 100 != 0)
  {
    if ($y % 4 == 0)
      return true;
  }
  return false;
}

function doy($d, $m, $y)
{
  $monCount = array(0, 1, 32, 60, 91, 121, 152, 182, 213, 244, 274, 305, 335, 366);
  if (($m > 2) && (leap($y)))
    return $monCount[$m] + $d + 1;
  else  
    return $monCount[$m] + $d;
}

function todec($deg, $min)
{
  return ($deg + $min / 60.0);
}

function M($x) 
{
  return (0.9856 * $x - 3.251);
}

function L($x)
{
  return ($x + 1.916 * sin(0.01745 * $x) + 0.02 * sin(2 * 0.01745 * $x) + 282.565);
}

function adj($x)
{
  return (-0.06571 * $x - 6.620);
}

function float_abs($x)
{
	if ($x < 0.0)
		return (-$x);
	else
		return ($x);
}

function suntime($d, $m, $y, 
		 $zendeg, $zenmin,
		 $londeg, $lonmin, $ew,
		 $latdeg, $latmin, $ns,
		 $tz,
		 $elevation) // H�he �ber dem Meerespiegel in Metern
{
  if ($zendeg == 90) {
    $earthRadiusInMeters = 6356.9 * 1000.0;
    $elevationAdjustment = rad2deg
      (acos($earthRadiusInMeters / ($earthRadiusInMeters + $elevation)));

    $z = $zendeg + $zenmin / 60;
    $z += $elevationAdjustment;
    $zendeg = floor($z);
    $zenmin = ($z - floor($z)) * 60;
  }

  $day = doy($d, $m, $y);
  $cosz = cos(0.01745 * todec($zendeg, $zenmin));

  $longitude = todec($londeg, $lonmin) * (($ew == 0) ? 1 : -1);
  $lonhr     = $longitude / 15.0;
  $latitude  = todec($latdeg, $latmin) * (($ns == 0) ? 1 : -1);
  $coslat    = cos(0.01745 * $latitude);
  $sinlat    = sin(0.01745 * $latitude);

  $t_rise = $day + (6.0 + $lonhr) / 24.0;
  $t_set  = $day + (18.0 + $lonhr) / 24.0;

  $xm_rise = M($t_rise);
  $xl_rise = L($xm_rise);
  $xm_set  = M($t_set);
  $xl_set  = L($xm_set);
  
  $a_rise = 57.29578 * atan( 0.91746 * tan(0.01745 * $xl_rise) );
  $a_set  = 57.29578 * atan( 0.91746 * tan(0.01745 * $xl_set) );
  if (float_abs($a_rise + 360.0 - $xl_rise) > 90.0)
    $a_rise += 180.0;
  if ($a_rise > 360.0)
    $a_rise -= 360.0;

  if (float_abs($a_set + 360.0 - $xl_set) > 90.0)
    $a_set += 180.0;
  if ($a_set > 360.0)
    $a_set -= 360.0;

  $ahr_rise = $a_rise / 15.0;
  $sindec = 0.39782 * sin(0.01745 * $xl_rise);
  $cosdec = sqrt(1.0 - $sindec * $sindec);
  $h_rise = ($cosz - $sindec * $sinlat) / ($cosdec * $coslat);

  $ahr_set = $a_set / 15.0;
  $sindec = 0.39782 * sin(0.01745 * $xl_set);
  $cosdec = sqrt(1.0 - $sindec * $sindec);
  $h_set = ($cosz - $sindec * $sinlat) / ($cosdec * $coslat);

  if (float_abs($h_rise) <= 1.0)
    $h_rise = 57.29578 * acos($h_rise);
  else
    return ""; //NO_SUNRISE;

  if (float_abs($h_set) <= 1.0)
    $h_set = 57.29578 * acos($h_set);
  else
    return ""; //NO_SUNSET;
  $ut_rise  = ((360.0 - $h_rise) / 15.0) + $ahr_rise + adj($t_rise) + $lonhr;
  $ut_set  = ($h_rise / 15.0) + $ahr_set + adj($t_set) + $lonhr;

  $returnSunrise = $ut_rise + $tz;  // sunrise
  $returnSunset = $ut_set  + $tz;  // sunset
  return array($returnSunrise, $returnSunset);
}

function timeadj($t)
{
  if ($t < 0)
    $t += 24.0;

  $hour = floor($t);
  $min  = floor(($t - $hour) * 60.0 + 0.5);

  if ($min >= 60) 
  {
    $hour += 1;
    $min  -= 60;
  }

  if ($hour > 24)
    $hour -= 24;
  
  return array($hour, $min);
}

function GetDegreesBelowHorizonAdd($uMonth, $uDay, $uYear,
				$fDegreesBelowHorizon,
				$iLatitude, $iLongitude, $iTimeZone,
				$elevation) {
  $returnTimes = suntime($uDay, $uMonth, $uYear,
	      90, 50, 
	      floor(abs($iLongitude / 100)), 
		floor(abs($iLongitude % 100)), ($iLongitude < 0) ? 0 : 1,
	      floor(abs($iLatitude / 100)), 
		floor(abs($iLatitude % 100)), ($iLatitude < 0) ? 1 : 0,
	      $iTimeZone, $elevation);
  if ($returnTimes != "")
  {              
    $srTime = timeadj($returnTimes[1]);
    while ($srTime[0] > 12)
      $srTime[0] -= 12;

    $db = $fDegreesBelowHorizon + 90.0;
    $deghour = floor($db);
    $db = $db - $deghour;
    $db *= 60.0;
    $degmin = floor($db);
    $returnTimes = suntime($uDay, $uMonth, $uYear,
		$deghour, $degmin, 
		floor(abs($iLongitude / 100)), 
		  floor(abs($iLongitude % 100)), ($iLongitude < 0) ? 0 : 1,
		floor(abs($iLatitude / 100)), 
		  floor(abs($iLatitude % 100)), ($iLatitude < 0) ? 1 : 0,
		$iTimeZone, $elevation);
    if ($returnTimes != "")
    {              
      $dbTime = timeadj($returnTimes[1]);
      while ($dbTime[0] > 12)
        $dbTime[0] -= 12;

      $srTimeValue = $srTime[0] * 60 + $srTime[1];
      $dbTimeValue = $dbTime[0] * 60 + $dbTime[1];
      return $dbTimeValue - $srTimeValue;
    }
  }
  return "";
}

function GetSunrise($uMonth, $uDay, $uYear,
		   $iLatitude, $iLongitude, $iTimeZone, $elevation)
{
  $returnTimes = suntime($uDay, $uMonth, $uYear,
	      90, 50, 
	      floor(abs($iLongitude / 100)), 
		floor(abs($iLongitude % 100)), ($iLongitude < 0) ? 0 : 1,
	      floor(abs($iLatitude / 100)), 
		floor(abs($iLatitude % 100)), ($iLatitude < 0) ? 1 : 0,
	      $iTimeZone, $elevation);
  if ($returnTimes != "")
  {              
    $returnTime = timeadj($returnTimes[0]);
    
    while ($returnTime[0] > 12)
      $returnTime[0] -= 12;
    
    return $returnTime;
  }
  else
    return "";
}

function GetSunriseDegreesBelowHorizon($uMonth, $uDay, $uYear,
				      $fDegreesBelowHorizon,
				      $iLatitude, $iLongitude, $iTimeZone,
				      $elevation)
{
  $t = GetSunrise($uMonth, $uDay, $uYear, $iLatitude, $iLongitude, $iTimeZone, $elevation);
  if ($t != "") {
    $adding = GetDegreesBelowHorizonAdd($uMonth, $uDay, $uYear, $fDegreesBelowHorizon, $iLatitude, $iLongitude, $iTimeZone, $elevation);
    if ($adding != "") {
      return SubtractMinutes($t, $adding);
    } else {
      return "";
    }
  } else {
    return "";
  }
}

function GetSunset($uMonth, $uDay, $uYear,
		   $iLatitude, $iLongitude, $iTimeZone, $elevation)
{
  $returnTimes = suntime($uDay, $uMonth, $uYear,
	      90, 50, 
	      floor(abs($iLongitude / 100)), 
		floor(abs($iLongitude % 100)), ($iLongitude < 0) ? 0 : 1,
	      floor(abs($iLatitude / 100)), 
		floor(abs($iLatitude % 100)), ($iLatitude < 0) ? 1 : 0,
	      $iTimeZone, $elevation);
  if ($returnTimes != "")
  {              
    $returnTime = timeadj($returnTimes[1]);
    
    while ($returnTime[0] < 12)
      $returnTime[0] += 12;
    
    return $returnTime;
  }
  else
    return "";
}

function GetSunsetDegreesBelowHorizon($uMonth, $uDay, $uYear,
				      $fDegreesBelowHorizon,
				      $iLatitude, $iLongitude, $iTimeZone, $elevation)
{
  $t = GetSunset($uMonth, $uDay, $uYear, $iLatitude, $iLongitude, $iTimeZone, $elevation);
  if ($t != "") {
    $adding = GetDegreesBelowHorizonAdd($uMonth, $uDay, $uYear, $fDegreesBelowHorizon, $iLatitude, $iLongitude, $iTimeZone, $elevation);
    if ($adding != "") {
      return AddMinutes($t, $adding);
    } else {
      return "";
    }
  } else {
    return "";
  }
}

function AddMinutes($time, $min) {
  if ($time == "") return "";
  $time2 = $time;
  $time2[1] += $min;
  while ($time2[1] >= 60) {
    $time2[1] -= 60;
    $time2[0]++;
  }
  return $time2;
}

function SubtractMinutes($time, $min) {
  if ($time == "") return "";
  $time2 = $time;
  $time2[1] -= $min;
  while ($time2[1] < 0) {
    $time2[1] += 60;
    $time2[0]--;
  }
  return $time2;
}

function GetProportionalHours($value, $sunrise, $sunset) {
  if ($sunrise == "" || $sunset == "") return "";
  $sr = $sunrise[0] * 60 + $sunrise[1];
  $ss = $sunset[0] * 60 + $sunset[1];
  $retval = $sr + floor((($ss-$sr) * $value) / 12);
  return array(floor($retval / 60), $retval % 60);
}

function GetProportionalHoursNight($value, $sunrise, $sunset) {
  if ($sunrise == "" || $sunset == "") return "";
  $sr = $sunrise[0] * 60 + $sunrise[1];
  $ss = $sunset[0] * 60 + $sunset[1];
  $retval = $ss + floor((($sr-$ss) * $value) / 12);
  
  return array(floor($retval / 60), $retval % 60);
}


function GetShaaZmanit($sunrise, $sunset) {
  $sr = $sunrise[0] * 60 + $sunrise[1];
  $ss = $sunset[0] * 60 + $sunset[1];
  return floor(($ss - $sr) / 12);
}

function candlTimeSunset($tagH, $monH, $jahrH, $activelocation)
{

$wochentagDH = date("w",strtotime("$jahrH-$monH-$tagH"));
$location = searchLocation($activelocation);
$elevation = $location[3];
  $sunrise = GetSunrise($monH, $tagH, $jahrH,
			$location[0], $location[1], $location[2], $elevation);
  if ($sunrise != "") {
    if (isDaylightSavingsTime($monH, $tagH, $jahrH))
      $sunrise = AddMinutes($sunrise, 60);
  }
  $sunset = GetSunset($monH, $tagH, $jahrH,
			$location[0], $location[1], $location[2], $elevation);
  if ($sunset != "") {
    if (isDaylightSavingsTime($monH, $tagH, $jahrH))
      $sunset = AddMinutes($sunset, 60);
  }
  
if ($sunrise != "" && $sunset != "") {
    $chaztot = GetProportionalHours(6, $sunrise, $sunset);
    $chaztot = FormatTime($chaztot); 
  } else {
    $chaztot =" ";
  }

if ($wochentagDH != 5) { // Friday
		               
                $timeCandle = $sunset;
		$timeCandleS = GetSunsetDegreesBelowHorizon($monH, $tagH, $jahrH,
			8.55,
			$location[0], $location[1], $location[2], $elevation);
		
                   
		//if($monH>3 && $monH<11) {
        //           $timeCandle = AddMinutes($timeCandle, 60);
		//   $timeCandleS = AddMinutes($timeCandleS, 60);
				   
		//		   }
		$timeCandleH = SubtractMinutes($timeCandle, 18); // 18 minutes before sunset
		$timeCandleH = FormatTime($timeCandleH);
		$timeCandleS = FormatTime($timeCandleS);
		
            
				}
	else
	{
	        $timeCandleH = "";
		$timeCandleS = "";
			
          }
                $timeEndTzom = GetSunsetDegreesBelowHorizon($monH, $tagH, $jahrH,
			6,
			$location[0], $location[1], $location[2], $elevation);
                $timeEndChag = GetSunsetDegreesBelowHorizon($monH, $tagH, $jahrH,
			8.55,
			$location[0], $location[1], $location[2], $elevation);
                $timeBeginTzom = GetSunriseDegreesBelowHorizon($monH, $tagH, $jahrH,
			16.1,
			$location[0], $location[1], $location[2], $elevation);
                  // if($monH>3 && $monH<11) {
                  //                 $timeEndTzom = AddMinutes($timeEndTzom, 60);
                  //                 $timeBeginTzom = AddMinutes($timeBeginTzom, 60);
                  //                 $timeEndChag = AddMinutes($timeEndChag, 60);
				  // }
                $timeEndTzom = FormatTime($timeEndTzom);
            if($timeBeginTzom != "")
                $timeBeginTzom = FormatTime($timeBeginTzom);
            else
               $timeBeginTzom = $chaztot;
               $timeEndChag = FormatTime($timeEndChag);
return array($timeCandleH, $timeCandleS, $wochentagDH, $timeEndTzom, $timeBeginTzom, $timeEndChag);
		
}

?>
