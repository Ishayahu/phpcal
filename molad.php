<?php
/**
 * Created by PhpStorm.
 * User: ishay
 * Date: 19.08.2022
 * Time: 10:40
 */
function molad()
{


    $gregorianMonthCT = date("n");
    $gregorianDayCT = date("j");
    $gregorianYearCT = date("Y");
    if(isset($_GET['day'])) $gregorianDayCT = $_GET['day'];
    if(isset($_GET['month'])) $gregorianMonthCT = $_GET['month'];
    if(isset($_GET['year'])) $gregorianYearCT = $_GET['year'];

    $jdNumberCT = gregoriantojd($gregorianMonthCT, $gregorianDayCT, $gregorianYearCT);
    $jdNumberCTNextShabes = $jdNumberCT+7;
    $jewishDateCT = jdtojewish($jdNumberCT);
    $jewishDateCTNextShabes = jdtojewish($jdNumberCTNextShabes);
    list($jewishMonthCT, $jewishDayCT, $jewishYearCT) = explode('/', $jewishDateCT);
    list($jewishMonthCTNextShabes, $jewishDayCTNextShabes, $jewishYearCTNextShabes) = explode('/', $jewishDateCTNextShabes);
    $jewishMonthCT++;
    if ($jewishMonthCT > 13) {
        $jewishMonthCT = 1;
        $jewishYearCT++;
    }
    $roshHodesh = jewishtojd($jewishMonthCT, 1, $jewishYearCT);
//    echo ($jewishMonthCT+6).','.$jewishYearCT;
    $pyjewishMonthCT=false;
    if($jewishMonthCT<=6){
        $pyjewishMonthCT=$jewishMonthCT+6;
    }else{
        $pyjewishMonthCT=$jewishMonthCT-7;}

    $daysOfWeek = array('1' => "йом ришон",
        '2' => "йом шейни",
        '3' => "йом шлиши",
        '4' => "йом ревии",
        '5' => "йом хамиши",
        '6' => "йом шиши",
        '7' => "шабат кодеш");
//    print '"E:\Program Files\Python39\python.exe" D:\YandexDisk\Sites\jewwish_calendar\php\molad.py ' .$jewishYearCT
//        ." $jewishDayCT / $jewishMonthCT ->  $pyjewishMonthCT <br />";
    $a = shell_exec('"E:\Program Files\Python39\python.exe" D:\YandexDisk\Sites\jewwish_calendar\php\molad.py '.$jewishYearCT.' '. $pyjewishMonthCT);
//    $a = shell_exec('/usr/local/bin/python3.8 /usr/local/www/zmanim/molad.py');
//print $a;
    $decoded = json_decode($a, true);

    $daysInHodesh = $decoded["last_day_of_month"];
    $days2roshHodesh = $roshHodesh - $jdNumberCT;
//    print "Рош ходеш через " . ($roshHodesh-$jdNumberCT) . "дней. След. шабат ".$jewishDayCTNextShabes." день месяца";
//    if ($days2roshHodesh <= 8 && ($days2roshHodesh>1 && $jewishDayCTTomorrow==30)) {
    if (($jewishDayCTNextShabes <= 7 && $daysInHodesh==29) ||
        ($jewishDayCTNextShabes <= 6 && $daysInHodesh==30)) {
        $moladWeekDay = $decoded['weekday'];
        $moladWeekDayName = $daysOfWeek[$moladWeekDay];
        $result =  "Молад будет в $moladWeekDayName
 в ${decoded['hour']} часов,
  ${decoded['minutes']} минут и ${decoded['parts']} частей";
} else {
        $result = false;
    }
    return $result;
}
//print molad();