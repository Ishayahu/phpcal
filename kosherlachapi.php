<?php
/**
 * Created by PhpStorm.
 * User: ishay
 * Date: 29.03.2023
 * Time: 20:56
 */

/*
 * недельная глава Торы
 * какой ближайший праздник или пост
 * TODO когда начало (зажигание свечей) или исход праздника / Шаббата
 * сколько дней осталось до ближайшего праздника / поста
 * TODO Все эти данные нужны на 3-х языках: русском, английском и иврите
 */
require "holidays.php";
require "torah.inc";
require 'vendor/autoload.php';
use PhpZmanim\Zmanim;
use PhpZmanim\Calendar\ComplexZmanimCalendar;
use PhpZmanim\Geo\GeoLocation;


function isJewishLeapYear($year) {
    if ($year % 19 == 0 || $year % 19 == 3 || $year % 19 == 6 ||
        $year % 19 == 8 || $year % 19 == 11 || $year % 19 == 14 ||
        $year % 19 == 17)
        return true;
    else
        return false;
}
function getJewishMonthName($jewishMonth, $jewishYear) {
    $jewishMonthNamesLeap = array("Tishri", "Heshvan", "Kislev", "Tevet",
        "Shevat", "AdarI", "AdarII", "Nisan",
        "Iyyar", "Sivan", "Tammuz", "Av", "Elul");
    $jewishMonthNamesNonLeap = array("Tishri", "Heshvan", "Kislev", "Tevet",
        "Shevat", "Adar", "Adar", "Nisan",
        "Iyyar", "Sivan", "Tammuz", "Av", "Elul");
    if (isJewishLeapYear($jewishYear))
        return $jewishMonthNamesLeap[$jewishMonth-1];
    else
        return $jewishMonthNamesNonLeap[$jewishMonth-1];
}
// TODO пока всегда тру
function isDiaspora() {
    return true;
}
function FormatTime($time) {
    $hour = $time[0];
    $min = $time[1];
    if ($min < 10)
        $minStr = "0" . $min;
    else
        $minStr = $min;
    return $hour . ":" . $minStr;
}

function getJD($date){
    $day = $date->format("j");
    $month = $date->format("n");
    $year = $date->format("Y");
    $jdNumber = gregoriantojd($month,$day,$year);
    return jdtojewish($jdNumber);

}
$holidays = sorted_holidays();

$holiday = array_shift($holidays);
$result = array();
//var_dump($holiday);
//print $holiday['name'] .' in '. $holiday['until'];
// Получаем нееврейские даты
list($dm, $year) = explode(',', $holiday['string']);
list($month,$day) = explode(' ', $dm);
$holiday['n_year'] = (int)trim($year);
$holiday['n_month'] = (int)date_parse(trim($month))['month'];
$holiday['n_day'] = (int)trim(explode('-',$day)[0]);

// Зажигание праздник
//$holiday_date = strtotime("${holiday['n_year']}-${holiday['n_month']}-${holiday['n_day']}");
//list($holiday_month, $holiday_day, $holiday_year) = explode('/', getJD($holiday_date));
$geoLocation = new GeoLocation("MJCC", 55.790853, 37.608302, 0, "Europe/Moscow");
$holidayZmanimCalendar = new ComplexZmanimCalendar($geoLocation, $holiday['n_year'], $holiday['n_month'], $holiday['n_day']);
$holiday['candleLighting'] = $holidayZmanimCalendar->candleLighting->format("H:i");

$result['holiday'] = $holiday;

//print "<p>";
//$gregorianMonthCT = date("n");
//$gregorianDayCT = date("j");
//$gregorianYearCT = date("Y");
//$gregorianDayWeekCT = date("w");

$nearestShabbat = new DateTime('NOW');
$nearestShabbat->modify("-4 days")->modify("next Saturday");
$shabbatZmanimCalendar = new ComplexZmanimCalendar($geoLocation,
    $nearestShabbat->format("Y"),
    $nearestShabbat->format("n"),
    $nearestShabbat->format("j"));
$result['candleLighting'] = $shabbatZmanimCalendar->candleLighting->format("H:i");

$shabatEndsHours = intval($shabbatZmanimCalendar->tzais->format("H"));
$shabatEndsMinutes = intval($shabbatZmanimCalendar->tzais->format("i"));
$shabatEndsSeconds = intval($shabbatZmanimCalendar->tzais->format("s"));
if($shabatEndsSeconds>0){
    $shabatEndsMinutes++;
}
$result['shabbatEnds'] =  FormatTime([$shabatEndsHours, $shabatEndsMinutes]);

//$nearestShabbatDay = $nearestShabbat->format("j");
//$nearestShabbatMonth = $nearestShabbat->format("n");
//$nearestShabbatYear = $nearestShabbat->format("Y");
//$nearestShabbatJdNumber = gregoriantojd($nearestShabbatMonth,$nearestShabbatDay,$nearestShabbatYear);
//$nearestShabbatDate = jdtojewish($nearestShabbatJdNumber);

//list($nearestShabbatJewwishMonthCT, $nearestShabbatJewwishDayCT, $nearestShabbatJewwishYearCT) = explode('/', $nearestShabbatDate);
list($nearestShabbatJewwishMonthCT, $nearestShabbatJewwishDayCT, $nearestShabbatJewwishYearCT) =
    explode('/', getJD($nearestShabbat));

//print "<p>";

$heMonth = array ( 'Tishri' => 'Тишрея', 'Heshvan' => 'Хешвана', 'Kislev' => 'Кислева', 'Tevet' => 'Тевета', 'Shevat' => 'Швата', 'Adar' => 'Адара', 'AdarI' => 'Адара 1-го', 'AdarII' => 'Адара 2-го', 'Nisan' => 'Нисана', 'Iyyar' => 'Ияра', 'Sivan' => 'Сивана', 'Tammuz' => 'Тамуза', 'Av' => 'Ава', 'Elul' => 'Элула');


//$jdNumberCT = gregoriantojd($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT);
//
//
//$jewishDateCT = jdtojewish($jdNumberCT);
//list($jewishMonthCT, $jewishDayCT, $jewishYearCT) = explode('/', $jewishDateCT);
list($jewishMonthCT, $jewishDayCT, $jewishYearCT) = explode('/', getJD(new DateTime('NOW')));
$jewishMonthCTName = getJewishMonthName($jewishMonthCT, $jewishYearCT);
//print $jewishDayCT . " " . $heMonth[$jewishMonthCTName] . " " . $jewishYearCT;
//print "<p>";



$torasections = getTorahSections($nearestShabbatJewwishMonthCT, $nearestShabbatJewwishDayCT, $nearestShabbatJewwishYearCT);
//print "Недельная глава: ". $torasections;
$result['torasections'] = $torasections;
//print "<p>";
header('Content-type: application/json');
echo json_encode($result);