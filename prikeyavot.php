<?php
/**
 * Created by PhpStorm.
 * User: ishay
 * Date: 17.06.2022
 * Time: 17:30
 */

function getPirkeyAvotPerek($debug=false)
{

    $gregorianMonthCT = date("n");
    $gregorianDayCT = date("j");
    $gregorianYearCT = date("Y");
    if(isset($_GET['day'])) $gregorianDayCT = $_GET['day'];
    if(isset($_GET['month'])) $gregorianMonthCT = $_GET['month'];
    if(isset($_GET['year'])) $gregorianYearCT = $_GET['year'];
    $today = DateTime::createFromFormat('d-m-Y', "$gregorianDayCT-$gregorianMonthCT-$gregorianYearCT");

//    $d = DateTime::createFromFormat('d-m-Y', "$gregorianDayCT-$gregorianMonthCT-$gregorianYearCT");
//    if ($d === false) {
//        $gregorianDayWeekCT = date("w");
//    } else {
//        $custom_timestamp = $d->getTimestamp();
//        $gregorianDayWeekCT = date("w", $custom_timestamp);
//    }


    $jdNumberCT = gregoriantojd($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT);
    $jewishDateCT = jdtojewish($jdNumberCT);
    list($jewishMonthCT, $jewishDayCT, $jewishYearCT) = explode('/', $jewishDateCT);

//    $jewishYearCT = 5782;

    $today_for_compare = jdtogregorian(jewishtojd(8, 23, $jewishYearCT));
    $firstDayAfterPesakh = jdtogregorian(jewishtojd(8, 23, $jewishYearCT));
    $firstDayOfShavuot = jdtogregorian(jewishtojd(10, 6, $jewishYearCT));
    $secondDayOfShavuot = jdtogregorian(jewishtojd(10, 7, $jewishYearCT));
    $firstDayOfNewYear = jdtogregorian(jewishtojd(1, 1, $jewishYearCT + 1));
//    list($m,$d,$g) = explode('/', $firstDayOfNewYear);
    $firstDayOfNewYear = new DateTime($firstDayOfNewYear);

    $tishaBeAv = jdtogregorian(jewishtojd(12, 9, $jewishYearCT));
    if($debug) {
        print  "Первый день после Песаха $firstDayAfterPesakh<br />";
        print "День недели первого дня после Песаха " . date("w", strtotime($firstDayAfterPesakh)) . "<br />";

        print "Первый день Шавуот $firstDayOfShavuot<br />";
        print "Второй день Шавуот $secondDayOfShavuot<br />";
        print "Первый день Рош аШана" . $firstDayOfNewYear->format('Y-m-d') . "<br />";
    }
    $daysToStartingShabbat = 6 - intval(date("w", strtotime($firstDayAfterPesakh)));
    $startingShabbat = new DateTime($firstDayAfterPesakh);
    $startingShabbat->add(new DateInterval("P$daysToStartingShabbat" . "D"));
    if($debug){
        print "Первую главу учат = " . $startingShabbat->format('Y-m-d') . "<br />";
    }
//    $dateDiff = date_diff(new DateTime("NOW"), $startingShabbat)->days;
    $dateDiff = date_diff($today, $startingShabbat)->days;
    if ($dateDiff == 0) {
        return 1;
    }
    if($debug){
        print "От изучения первой главы прошло $dateDiff дней <br />";
    }
    $shabats = intdiv($dateDiff, 7);
//    Если после Шавуота
    if($today > new DateTime($secondDayOfShavuot)) {
        if($debug){
            print "Мы после Шавуота<br />";
        }
        if (date("w", strtotime($firstDayOfShavuot)) == '6' ||
            date("w", strtotime($secondDayOfShavuot)) == '6') {
            $shabats--;
        }
    }
    if($debug){

        print "От изучения первой главы прошло $shabats недель <br />";
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
//getPirkeyAvotPerek(true);