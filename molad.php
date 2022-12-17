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

    $jdNumberCT = gregoriantojd($gregorianMonthCT, $gregorianDayCT, $gregorianYearCT);
    $jewishDateCT = jdtojewish($jdNumberCT);
    list($jewishMonthCT, $jewishDayCT, $jewishYearCT) = explode('/', $jewishDateCT);
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
        $pyjewishMonthCT=$jewishMonthCT-6;}

    $daysOfWeek = array('1' => "йом ришон",
        '2' => "йом шейни",
        '3' => "йом шлиши",
        '4' => "йом ревии",
        '5' => "йом хамиши",
        '6' => "йом шиши",
        '7' => "шабат кодеш");
    $a = shell_exec('"E:\Program Files\Python39\python.exe" D:\YandexDisk\Sites\jewwish_calendar\php\molad.py '.$jewishYearCT.' '. $pyjewishMonthCT);
//    $a = shell_exec('/usr/local/bin/python3.8 /usr/local/www/zmanim/molad.py');
//print $a;
    $decoded = json_decode($a, true);


    $days2roshHodesh = $roshHodesh - $jdNumberCT;
//print "Рош ходеш через " . ($roshHodesh-$jdNumberCT) . "дней";
    if ($days2roshHodesh <= 8) {
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
print molad();