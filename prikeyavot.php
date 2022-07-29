<?php
/**
 * Created by PhpStorm.
 * User: ishay
 * Date: 17.06.2022
 * Time: 17:30
 */

function getPirkeyAvotPerek($debug=false)
{
    $jewishYearCT = 5782;
    $firstDayAfterPesakh = jdtogregorian(jewishtojd(8, 23, $jewishYearCT));
    $firstDayOfShavuot = jdtogregorian(jewishtojd(10, 6, $jewishYearCT));
    $secondDayOfShavuot = jdtogregorian(jewishtojd(10, 7, $jewishYearCT));
    $firstDayOfNewYear = jdtogregorian(jewishtojd(1, 1, $jewishYearCT + 1));
    if($debug) {
        print $firstDayAfterPesakh . "<br />";
        print date("w", strtotime($firstDayAfterPesakh)) . "<br />";

        print $firstDayOfShavuot . "<br />";
        print $secondDayOfShavuot . "<br />";
        print $firstDayOfNewYear . "<br />";
    }
    $daysToStartingShabbat = 6 - intval(date("w", strtotime($firstDayAfterPesakh)));
    $startingShabbat = new DateTime($firstDayAfterPesakh);
    $startingShabbat->add(new DateInterval("P$daysToStartingShabbat" . "D"));
    if($debug){
        print "1 perek=" . $startingShabbat->format('Y-m-d') . "<br />";
    }
    $dateDiff = date_diff(new DateTime("NOW"), $startingShabbat)->days;
    if ($dateDiff == 0) {
        return 1;
    }
    if($debug){
        print "$dateDiff дней <br />";
    }
    $shabats = intdiv($dateDiff, 7);
    if (date("w", strtotime($firstDayOfShavuot)) == '6' ||
        date("w", strtotime($secondDayOfShavuot)) == '6') {
        $shabats--;
    }
    if($debug){

        print "$shabats недель <br />";
    }
    $perekNumber = bcmod($shabats, 6) + 1;
    if ($perekNumber == 0) {
        $perekNumber = 6;
    }
    if($debug){

        print "$perekNumber глава Пиркей Авот <br />";
    }
    return $perekNumber;
}
//getPirkeyAvotPerek(true);