<?php
/**
 * Created by PhpStorm.
 * User: ishay
 * Date: 17.06.2022
 * Time: 17:30
 */

function getPirkeyAvotPerek($debug=false)
{
    $today = new DateTime('NOW');
    $gregorianMonthCT = date("n");
    $gregorianDayCT = date("j");
    $gregorianYearCT = date("Y");

    $jdNumberCT = gregoriantojd($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT);
    $jewishDateCT = jdtojewish($jdNumberCT);
    list($jewishMonthCT, $jewishDayCT, $jewishYearCT) = explode('/', $jewishDateCT);

//    $jewishYearCT = 5782;

    $firstDayAfterPesakh = jdtogregorian(jewishtojd(8, 23, $jewishYearCT));
    $firstDayOfShavuot = jdtogregorian(jewishtojd(10, 6, $jewishYearCT));
    $secondDayOfShavuot = jdtogregorian(jewishtojd(10, 7, $jewishYearCT));
    $firstDayOfNewYear = jdtogregorian(jewishtojd(1, 1, $jewishYearCT + 1));
//    list($m,$d,$g) = explode('/', $firstDayOfNewYear);
    $firstDayOfNewYear = new DateTime($firstDayOfNewYear);

    $tishaBeAv = jdtogregorian(jewishtojd(12, 9, $jewishYearCT));
    if($debug) {
        print $firstDayAfterPesakh . "<br />";
        print date("w", strtotime($firstDayAfterPesakh)) . "<br />";

        print $firstDayOfShavuot . "<br />";
        print $secondDayOfShavuot . "<br />";
        print $firstDayOfNewYear->format('Y-m-d') . "<br />";
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
    // Если сейчас ПОСЛЕ 9 ава и 9 ава выпадало на шабат - вычитаем одну главу, так как
    // 9 ава в шабат не читают
    if($today>$tishaBeAv && date("w", strtotime($tishaBeAv)) == '6'){
        $perekNumber--;
    }
    if ($perekNumber == 0) {
        $perekNumber = 6;
    }
    // успеваем до Рош аШоно
    // нам осталось ещё глав:
    $pirkeyLigmor = 6-$perekNumber;
    // осталось недель
    $daysLigmor = $firstDayOfNewYear->diff($today)->days;
    $weeksLigmor = intdiv ($daysLigmor,7);
    // TODO так ли это
    if($weeksLigmor-$pirkeyLigmor>=3){
        // не делаем ничего
    }else{

        if ($weeksLigmor==0){
            $perekNumber = "5 и 6";
        }
        if ($weeksLigmor==1){
            $perekNumber = "3 и 4";
        }
        if ($weeksLigmor==2){
            $perekNumber = "1 и 2";
        }
    }
//    print "$pirkeyLigmor -> $daysLigmor ->$weeksLigmor<br/>";

    if($debug){

        print "$perekNumber глава Пиркей Авот <br />";
    }
    return $perekNumber;
}
getPirkeyAvotPerek(true);