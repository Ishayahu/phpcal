<html>
    <head>
        <title> MJCC </title>
<!--        <link rel="icon" href="old/logo-header.png" type="image/x-icon">-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="description" content="Free PHP Luach">
        <meta name="keywords" content="Free, Luach, PHP, code, Luach PHP, jewish calendar, hebrew calendar, jüdischer Kalender">
        <meta http-equiv="refresh" content="3600">
        <meta http-equiv="Cache-Control" content="no-cache">
        <link rel="stylesheet" type="text/css" href="style.css">
        <link rel="stylesheet" type="text/css" href="style2.css">
        <script language="JavaScript" src="prevnext.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <body>
    <?php

        // для заблюривания прошедшего времени
        $timers = array();
        function computeDiff($t){
            $h = $t->format("H");
            $m = $t->format("i");
            $currh = date('H');
            $currm = date('i');
            $diff = ($h-$currh)*60+($m-$currm);
            if ($diff>0)
                return $diff;
            else
                return 0;
        }
        function computeDiff2($h, $m){
            $currh = date('H');
            $currm = date('i');
            $diff = ($h-$currh)*60+($m-$currm);
            if ($diff>0)
                return $diff;
            else
                return 0;
        }
        date_default_timezone_set('Europe/Moscow');
        $timezone = date_default_timezone_get();
        $currh = date('H');
        $currm = date('i');
        print "<!-- $currh: $currm -->";
        print "<!-- $timezone -->";

        // https://github.com/zachweix/PhpZmanim
        require 'vendor/autoload.php';
        use PhpZmanim\Zmanim;
        use PhpZmanim\Calendar\ComplexZmanimCalendar;
        use PhpZmanim\Geo\GeoLocation;
        // $geoLocation = new GeoLocation("MJCC", 55.790853, 37.608302, 186, "Europe/Moscow");
        $geoLocation = new GeoLocation("MJCC", 55.790853, 37.608302, 0, "Europe/Moscow");
        $today = new DateTime('NOW');
//        $today = new DateTime('18-06-2022');
        $tomorrow = new DateTime('tomorrow');
        $gregorianMonthCT = date("n");
        $gregorianDayCT = date("j");
        $gregorianYearCT = date("Y");
        if(isset($_GET['day'])) $gregorianDayCT = $_GET['day'];
        if(isset($_GET['month'])) $gregorianMonthCT = $_GET['month'];
        if(isset($_GET['year'])) $gregorianYearCT = $_GET['year'];

        $d = DateTime::createFromFormat('d-m-Y', "$gregorianDayCT-$gregorianMonthCT-$gregorianYearCT");
        if ($d === false) {
            $gregorianDayWeekCT = date("w");
        } else {
            $custom_timestamp = $d->getTimestamp();
            $gregorianDayWeekCT = date("w", $custom_timestamp);
        }







        print "\n<!-- dayweek: $gregorianDayWeekCT -->\n";
        $tomorrowGregorianMonthCT = $tomorrow->format("n");
        $tomorrowGregorianDayCT = $tomorrow->format("j");
        $tomorrowGregorianYearCT = $tomorrow->format("Y");
        $day = date("j");
        $month = date("n");
        $year = date("Y");
        if(isset($_GET['day'])) $day = $_GET['day'];
        if(isset($_GET['month'])) $month = $_GET['month'];
        if(isset($_GET['year'])) $year = $_GET['year'];
        if(isset($_GET['weekday'])) $gregorianDayWeekCT = $_GET['weekday'];
        $gregorianMonthCT = $month;
        $gregorianDayCT = $day;
        $gregorianYearCT = $year;

        require "molad.php";
        $shabesMevorkhim = molad();
//        $gregorianMonthCT = '7';
//        $gregorianDayCT = '16';
//        $gregorianYearCT = '2022';
////        $gregorianDayWeekCT = "2";
//            $day = $gregorianDayCT;
//            $month = $gregorianMonthCT;
//            $year = $gregorianYearCT;

        $complexZmanimCalendar = new ComplexZmanimCalendar($geoLocation, $gregorianYearCT, $gregorianMonthCT, $gregorianDayCT);
//        $complexZmanimCalendar = new ComplexZmanimCalendar($geoLocation, date("Y"), date("n"), date("j"));
//        $complexZmanimCalendar = new ComplexZmanimCalendar($geoLocation, '2022', '04', '15');
        // $complexZmanimCalendar->setCalculatorType("Noaa");
        echo "<!-- СМ тут: восход, конце Шма Танья/Гра-->\n";
        echo "<!-- ". $complexZmanimCalendar->sunrise . "-->\n";

        echo "<!-- ". $complexZmanimCalendar->sofZmanShmaBaalHatanya . "-->\n";
        echo "<!-- ". $complexZmanimCalendar->sofZmanShmaGra . "-->\n";


        error_reporting(E_ALL);
        include("torah.inc");
//        include("dst.inc");
//        include("custom.inc");
        include("locations.inc");
        include("times.inc");
//        include("zmanim3.inc");

        //include("dafyomi.inc");
        //include("mishnayomit.inc");
        include("holiday.inc");
        function isDiaspora() {
            return true;
        }
        function isLeap($year){
            return date("L", mktime(0,0,0, 7,7, $year));
        }
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
        // Если в запросе указана дата - показываем. Если нет - актуальную.
        // TODO зачем?
        /*if (isSet($_REQUEST["jahr"]) && isSet($_REQUEST["Monate"])) {
            $jahr = $_REQUEST["jahr"];
            $monat = $_REQUEST["Monate"];
            $carentday = date("j");
        } else {
            $monat = date("n");
            $jahr = date("Y");
            $carentday = date("j");
        }*/
        // то же для локации
        if (isSet($_REQUEST["activelocation"])) {
            $activelocation = $_REQUEST["activelocation"];
        } else {
            $activelocation = "Moskau";
        }
        // Перевод названий еврейских месяцев с английского на русский.
        $heMonth = array ( 'Tishri' => 'Тишрея', 'Heshvan' => 'Хешвана', 'Kislev' => 'Кислева', 'Tevet' => 'Тевета', 'Shevat' => 'Швата', 'Adar' => 'Адара', 'AdarI' => 'Адара 1-го', 'AdarII' => 'Адара 2-го', 'Nisan' => 'Нисана', 'Iyyar' => 'Ияра', 'Sivan' => 'Сивана', 'Tammuz' => 'Тамуза', 'Av' => 'Ава', 'Elul' => 'Элула');
        $ruMonth = array ( 'January' => 'Январь','February' => 'Февраль', 'March' => 'Март', 'April' => 'Апрель', 'May' => 'Май', 'June' => 'Июнь', 'July' => 'Июль', 'August' => 'Август', 'September' => 'Сентябрь', 'October' => 'Октябрь', 'November' => 'Ноябрь', 'December' => 'Декабрь');


        // Названия грегорианских месяцев
        $gregorianMonthNames = array("Января", "Февраля", "Марта", "Апреля",
                                     "Мая", "Июня", "Июля", "Августа", "Сентября",
                                     "Октября", "Ноября", "Декабря");
        // Названия дней недели
        $dw = array( '0'=>'воскресенье', '1'=>'понедельник', '2'=>'вторник', '3'=>'среда', '4'=>'четверг', '5'=>'пятница', '6'=>'суббота');



        $jdNumberCT = gregoriantojd($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT);
        $jewishDateCT = jdtojewish($jdNumberCT);
        list($jewishMonthCT, $jewishDayCT, $jewishYearCT) = explode('/', $jewishDateCT);
        // jewish tomorrow date
        $tomorrowJdNumberCT = gregoriantojd($tomorrowGregorianMonthCT,$tomorrowGregorianDayCT,$tomorrowGregorianYearCT);
        $tomorrowJewishDateCT = jdtojewish($tomorrowJdNumberCT);
        list($tomorrowJewishMonthCT, $tomorrowJewishDayCT, $tomorrowJewishYearCT) = explode('/', $tomorrowJewishDateCT);
        // Torah section on this week and motzei shabbat
        $location = searchLocation($activelocation);
        $elevation = $location[3];
        $timeStringSb;
        // дней до шабата
        $dif = 6 - $gregorianDayWeekCT;
//        $daysInMonth = cal_days_in_month(CAL_JEWISH, $jewishMonthCT, $jewishYearCT);
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $gregorianMonthCT, $gregorianYearCT);
        $jewishDateSbDay = $jewishDayCT + $dif;

        // Получаем дату шабата и время его конца
        // ЕСЛИ СЕГОДНЯ ШАБАТ
        if($gregorianDayWeekCT == 6) {

            $torasections = getTorahSections($jewishMonthCT, $jewishDayCT, $jewishYearCT);
            echo "<!-- TORAH: 6: -->";
            echo "<!-- TORAH: 6: $torasections -->";
            $timeSb = GetSunsetDegreesBelowHorizon($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT,
                                8.55,
                                                   $location[0], $location[1], $location[2], $elevation);
            //if($gregorianMonthCT>3 && $gregorianMonthCT<11)
            //  $timeSb = AddMinutes($timeSb, 60);
            $timeStringSb = FormatTime($timeSb);
            $dateSbMonth = $gregorianMonthCT;
            $dateSbDay = $gregorianDayCT;
            $dateSbYear = $gregorianYearCT;
        } else {
            // если ближайший шабат в этом месяце
            if(checkdate($gregorianMonthCT, $gregorianDayCT+$dif, $gregorianYearCT) != false){
                $jdNumberSB = gregoriantojd($gregorianMonthCT,$gregorianDayCT+$dif,$gregorianYearCT);
                $jewishDateSB = jdtojewish($jdNumberSB);
                list($jewishMonthSB, $jewishDaySB, $jewishYearSB) = explode('/', $jewishDateSB);
                $torasections = getTorahSections($jewishMonthSB, $jewishDaySB, $jewishYearSB);
                echo "<!-- TORAH: 6-1:  -->";
                echo "<!-- TORAH: 6-1: $torasections -->";
                $timeSb = GetSunsetDegreesBelowHorizon($gregorianMonthCT,$gregorianDayCT+$dif,$gregorianYearCT,
                                    8.55,
                                                       $location[0], $location[1], $location[2], $elevation);
                //if($gregorianMonthCT>3 && $gregorianMonthCT<11)
                //  $timeSb = AddMinutes($timeSb, 60);
                $timeStringSb = FormatTime($timeSb);
                $dateSbMonth = $gregorianMonthCT;
                $dateSbDay = $gregorianDayCT+$dif;
                $dateSbYear = $gregorianYearCT;
            // если ближайший шабат в след. месяце
            } elseif(checkdate($gregorianMonthCT + 1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT) != false){
                $jdNumberSB = gregoriantojd($gregorianMonthCT + 1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT);
                $jewishDateSB = jdtojewish($jdNumberSB);
                list($jewishMonthSB, $jewishDaySB, $jewishYearSB) = explode('/', $jewishDateSB);
                $torasections = getTorahSections($jewishMonthSB, $jewishDaySB, $jewishYearSB);
//                $torasections = getTorahSections($jewishMonthSB, 4, $jewishYearSB);
                echo "<!-- TORAH: 6-2: " . ($gregorianMonthCT + 1) .", $gregorianDayCT + $dif - $daysInMonth, ". $gregorianYearCT ." -->";
                echo "<!-- TORAH: 6-2: $torasections -->";
                $timeSb = GetSunsetDegreesBelowHorizon($gregorianMonthCT + 1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT,
                                    8.55,
                                                       $location[0], $location[1], $location[2], $elevation);
                //if(($gregorianMonthCT + 1)>3 && ($gregorianMonthCT + 1)<11) //only summer time
                //  $timeSb = AddMinutes($timeSb, 60);
                $timeStringSb = FormatTime($timeSb);
                $dateSbMonth = $gregorianMonthCT + 1;
                $dateSbDay = ($gregorianDayCT+$dif) - $daysInMonth;
                $dateSbYear = $gregorianYearCT;
            // если ближайший шабат в след. году
            } elseif(checkdate(1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT + 1) != false) {
                $jdNumberSB = gregoriantojd(1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT + 1);
                $jewishDateSB = jdtojewish($jdNumberSB);
                list($jewishMonthSB, $jewishDaySB, $jewishYearSB) = explode('/', $jewishDateSB);

                $torasections = getTorahSections($jewishMonthSB, $jewishDaySB, $jewishYearSB);
                echo "<!-- TORAH: 6-3:  -->";
                echo "<!-- TORAH: 6-3: $torasections -->";
                $timeSb = GetSunsetDegreesBelowHorizon(1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT + 1,
                            8.55,
                            $location[0], $location[1], $location[2], $elevation);

                $timeStringSb = FormatTime($timeSb);
                $dateSbMonth = 1;
                $dateSbDay = ($gregorianDayCT+$dif) - $daysInMonth;
                $dateSbYear = $gregorianYearCT + 1;

            }
        }

        // Дата пятницы и время начала шабата
        $difF = 5 - $gregorianDayWeekCT;
        // ???? Почему не просто == 5? иначе на шабате это тоже будет срабатывать
        if($gregorianDayWeekCT == 5){
            $timeC = GetSunset($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT,
                $location[0], $location[1], $location[2], $elevation);
            //if($gregorianMonthCT>3 && $gregorianMonthCT<11)
                //$timeC = AddMinutes($timeC, 60);
            $timeC = SubtractMinutes($timeC, 18); // 18 minutes before sunset
            $timeStringC = FormatTime($timeC);
            $dateFrMonth = $gregorianMonthCT;
            $dateFrDay = $gregorianDayCT;
            $dateFrYear = $gregorianYearCT;
        } else {
            if(checkdate($gregorianMonthCT, $gregorianDayCT+$difF, $gregorianYearCT) != false){
                $timeC = GetSunset($gregorianMonthCT,$gregorianDayCT+$difF,$gregorianYearCT,
                    $location[0], $location[1], $location[2], $elevation);
                //if($gregorianMonthCT>3 && $gregorianMonthCT<11)
                    //$timeC = AddMinutes($timeC, 60);
                $timeC = SubtractMinutes($timeC, 18); // 18 minutes before sunset
                $timeStringC = FormatTime($timeC);
                $dateFrMonth = $gregorianMonthCT;
                $dateFrDay = $gregorianDayCT+$difF;
                $dateFrYear = $gregorianYearCT;
            } elseif(checkdate($gregorianMonthCT + 1, ($gregorianDayCT+$difF) - $daysInMonth, $gregorianYearCT) != false) {
                $timeC = GetSunset($gregorianMonthCT + 1, ($gregorianDayCT+$difF) - $daysInMonth, $gregorianYearCT,
                    $location[0], $location[1], $location[2], $elevation);
                //if(($gregorianMonthCT + 1)>3 && ($gregorianMonthCT + 1)<11)
                    //$timeC = AddMinutes($timeC, 60);
                $timeC = SubtractMinutes($timeC, 18); // 18 minutes before sunset
                $timeStringC = FormatTime($timeC);
                $dateFrMonth = $gregorianMonthCT + 1;
                $dateFrDay = ($gregorianDayCT+$difF) - $daysInMonth;
                $dateFrYear = $gregorianYearCT;
            } elseif(checkdate(1, ($gregorianDayCT+$difF) - $daysInMonth, $gregorianYearCT + 1) != false) {
                $timeC = GetSunset(1, ($gregorianDayCT+$difF) - $daysInMonth, $gregorianYearCT + 1,
                                    $location[0], $location[1], $location[2], $elevation);
                $timeC = SubtractMinutes($timeC, 18); // 18 minutes before sunset
                $timeStringC = FormatTime($timeC);
                $dateFrMonth = 1;
                $dateFrDay = ($gregorianDayCT+$difF) - $daysInMonth;
                $dateFrYear = $gregorianYearCT + 1;
            }
        }
        echo "<!-- FRIDAY: $dateFrDay / $dateFrMonth / $dateFrYear-->\n";
        echo "<!-- SHABAT: $dateSbDay / $dateSbMonth / $dateSbYear -->\n";
        echo "<!-- jewishDateCT: $jewishDateCT -->\n";

        $fridayComplexZmanimCalendar = new ComplexZmanimCalendar($geoLocation, $dateFrYear, $dateFrMonth, $dateFrDay);
        $shabatComplexZmanimCalendar = new ComplexZmanimCalendar($geoLocation, $dateSbYear, $dateSbMonth, $dateSbDay);
        $jewishMonthCTName = getJewishMonthName($jewishMonthCT, $jewishYearCT);
        $tomorrowJewishMonthCTName = getJewishMonthName($tomorrowJewishMonthCT, $tomorrowJewishYearCT);

    //Если сегодня канун праздника, праздник или последний день праздника:
        $erevYomTov = false;
        $holAmoed = false;
        $firstDayOfYomTov = false;
        $secondDayOfYomTov = false;
        $lastDayOfYomTov = false;
        $firstDayCandleLigtning = false;
        $secondDayCandleLigtning = false;
        $mozeiYomTov = false;
        if(
            ($jewishDayCT==14 && $jewishMonthCTName=='Nisan') ||
            ($jewishDayCT==14 && $jewishMonthCTName=='Tishri')
//            ($jewishDayCT==3 && $jewishMonthCTName=='Tishri') ||
//            ($jewishDayCT==10 && $jewishMonthCTName=='Tevet') ||
//            ($jewishDayCT==17 && $jewishMonthCTName=='Tammuz') ||
//            ($jewishDayCT==9 && $jewishMonthCTName=='Av')
        ){
            $erevYomTov=true;
            $firstDayCandleLigtning = new ComplexZmanimCalendar($geoLocation, $year, $month, $day);
        }
        // TODO тут надо учесть вариант шабата после йом-това. но это потом
        if(
            ($jewishDayCT==16 && $jewishMonthCTName=='Nisan') ||
            ($jewishDayCT==16 && $jewishMonthCTName=='Tishri')
//            ($jewishDayCT==3 && $jewishMonthCTName=='Tishri') ||
//            ($jewishDayCT==10 && $jewishMonthCTName=='Tevet') ||
//            ($jewishDayCT==17 && $jewishMonthCTName=='Tammuz') ||
//            ($jewishDayCT==9 && $jewishMonthCTName=='Av')
        ){
            $mozeiYomTov=true;
            $mozeiYomTovTime = new ComplexZmanimCalendar($geoLocation, $year, $month, $day);
        }
        if(
            ($jewishDayCT==15 && $jewishMonthCTName=='Nisan') ||
            ($jewishDayCT==15 && $jewishMonthCTName=='Tishri')
//            ($jewishDayCT==3 && $jewishMonthCTName=='Tishri') ||
//            ($jewishDayCT==10 && $jewishMonthCTName=='Tevet') ||
//            ($jewishDayCT==17 && $jewishMonthCTName=='Tammuz') ||
//            ($jewishDayCT==9 && $jewishMonthCTName=='Av')
        ){
            $secondDayOfYomTov=true;
            $secondDayCandleLigtning = new ComplexZmanimCalendar($geoLocation, $year, $month, $day);
        }
        if(
            ($jewishDayCT==22 && $jewishMonthCTName=='Nisan') ||
            ($jewishDayCT==23 && $jewishMonthCTName=='Tishri')
//            ($jewishDayCT==3 && $jewishMonthCTName=='Tishri') ||
//            ($jewishDayCT==10 && $jewishMonthCTName=='Tevet') ||
//            ($jewishDayCT==17 && $jewishMonthCTName=='Tammuz') ||
//            ($jewishDayCT==9 && $jewishMonthCTName=='Av')
        ){
            $lastDayOfYomTov=true;
        }
        if(
            ($jewishMonthCTName=='Nisan' && ($jewishDayCT>15 && $jewishDayCT<21)) ||
            ($jewishMonthCTName=='Tishri' && ($jewishDayCT>15 && $jewishDayCT<21))
//            ($jewishDayCT==14 && $jewishMonthCTName=='Tishri')
//            ($jewishDayCT==3 && $jewishMonthCTName=='Tishri') ||
//            ($jewishDayCT==10 && $jewishMonthCTName=='Tevet') ||
//            ($jewishDayCT==17 && $jewishMonthCTName=='Tammuz') ||
//            ($jewishDayCT==9 && $jewishMonthCTName=='Av')
        ){
            $holAmoed=true;
        }
        // Отладка времени конца шабата
        if (false) {
            echo "<!-- SHABAT-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais72 . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim3Point7Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim3Point8Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim5Point95Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim3Point65Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim3Point676Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim4Point61Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim4Point37Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim5Point88Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim4Point8Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim6Point45Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim7Point083Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim7Point67Degrees . "-->\n";
            // Это почему-то не работает
            // echo "<!-- ".$shabatComplexZmanimCalendar->tzaisGeonim8Point5Degrees. "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim9Point3Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisGeonim9Point75Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais60 . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisAteretTorah . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais72Zmanis . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais90Zmanis . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais96Zmanis . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais90 . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais120 . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais120Zmanis . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais16Point1Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais26Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais18Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais19Point8Degrees . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzais96 . "-->\n";
            echo "<!-- " . $shabatComplexZmanimCalendar->tzaisBaalHatanya . "-->\n";
        }
        // Функция принимает именно массив: час, минута
        $sunrise = [$complexZmanimCalendar->sunrise->format("H"), $complexZmanimCalendar->sunrise->format("i")];
        $sunset = [$complexZmanimCalendar->sunset->format("H"), $complexZmanimCalendar->sunset->format("i")];


        // Зимой три маарива, летом - три минхи
        // TODO надо будет автоматом переходить, пока не ясно когда
        $leto = false;

        // Если маарив начинается после 17:40 - то нет маарива в 18

        $maarivPossibleTime = GetSunsetDegreesBelowHorizon($month, $day, $year, 6, $location[0], $location[1], $location[2], $elevation);
        $h = $complexZmanimCalendar->tzaisBaalHatanya->format("H");
        $m = $complexZmanimCalendar->tzaisBaalHatanya->format("i");

        if($maarivPossibleTime[0] > $h || ($maarivPossibleTime[0] == $h && $maarivPossibleTime[1] > $m)){
            $firstMaarivTime = $maarivPossibleTime;
        }else{
            $firstMaarivTime = [intval($h), intval($m)];
        }
        if (($firstMaarivTime[0]==17 && $firstMaarivTime[1]>40)|| $firstMaarivTime[0]>17){
            $maariv18 = false;
        }else{
            $maariv18 = true;
        }




        $tishaBeAv=false;
        $tzom=false;
        echo "<!-- HE DAY:MONTH $jewishDayCT / $jewishMonthCTName -->";
        if (($jewishDayCT==9 && $jewishMonthCTName=='Av' && $gregorianDayWeekCT!=6)||
            ($jewishDayCT==10 && $jewishMonthCTName=='Av' && $gregorianDayWeekCT==0)
        ){
            $tishaBeAv=true;
        }
        // "Tishri", "Heshvan", "Kislev", "Tevet", "Shevat", "AdarI", "AdarII", "Nisan", "Iyyar", "Sivan", "Tammuz", "Av", "Elul"
        if(
                ($jewishDayCT==13 && $jewishMonthCTName=='AdarII') ||
                ($jewishDayCT==13 && $jewishMonthCTName=='Adar') ||
                ($jewishDayCT==3 && $jewishMonthCTName=='Tishri') ||
                ($jewishDayCT==10 && $jewishMonthCTName=='Tevet') ||
                ($jewishDayCT==17 && $jewishMonthCTName=='Tammuz' && $gregorianDayWeekCT!=6) ||
                ($jewishDayCT==18 && $jewishMonthCTName=='Tammuz' && $gregorianDayWeekCT==0) ||
                ($jewishDayCT==9 && $jewishMonthCTName=='Av' && $gregorianDayWeekCT!=6) ||
                ($jewishDayCT==10 && $jewishMonthCTName=='Av' && $gregorianDayWeekCT==0)
        ){
            $tzom=true;
        }


        // my debugs
        $roshChodeshDebug = false;
    ?>
        <div class="container-fluid" style="height: 100%">
            <div class="row" id='header'>
                <!-- Header -->
                <div class="col-12 white">
                    <span class="title">
                        Синагога
                        <span class="synagogue-name">'Бейт Менахем - ХАБАД Любавич'</span>
                        Марьина Роща
                    </span>
                </div>
            </div>
            <div class="row" id="subheader">
                <div class="col-3 date">
                    <div id="jewishDate">
                        <?php echo $jewishDayCT . " " . $heMonth[$jewishMonthCTName] . " " . $jewishYearCT; ?>
                    </div>
                </div>
                <div class="col-6">
                    <div class="parsha">
                        <span style="font-size: 30px;font-family: Georgia, 'Times New Roman', Times, serif; color: #333333;">
                            Глава Торы
                        </span>
                        <br>
                        <span style="font-size: <?php if(strpos($torasections, ' ') !== false){echo "40";}else{echo "70";} ?>px; font-family: Arial; color: #333333; font-weight: bold;">
                                    <?php echo $torasections; ?>
                        </span>
                    </div>
                </div>
                <div class="col-3 date">
                    <div>
                        <?php echo $gregorianDayCT . " " . $gregorianMonthNames[$gregorianMonthCT - 1] . " " . $gregorianYearCT; ?>
                    </div>
                </div>
            </div>
            <div class="row" id="mainBlock">
                <!-- Левый блок с молитвами-->
                <div class="col-3" id='menu' style='text-align: center;'>
                    <table align="center" cellspacing=0 cellpadding=3 width='100%'>
                        <!-- Шахарис 7:30 -->
                        <tr>
                            <td>
                                <div id='shakharit730' class="white">
                                    <div class="leftside-title">
                                        <span>Шахарит</span><span>שחרית</span>
                                    </div>

                                    <div class="leftside-time" >
                                    <?php if($gregorianDayWeekCT==0){
                                        echo "8:00";
                                    }elseif ($gregorianDayWeekCT==6){
                                        echo "--:--";
                                    }else{
                                        echo "7:30";
                                    }?>
                                    </div>
                                </div>
                                <div class="between-row-spacer"></div>
                            </td>
                        </tr>
                        <!-- Шахарис 8:30 -->
                        <tr>
                            <td>
                                <div id='shakharit830' class="white">
                                    <div class="leftside-title">
                                        <?php
                                        if ($shabesMevorkhim && $gregorianDayWeekCT==6){
                                            print "<span>Теилим</span><span>תהילים</span>";
                                        }else{
                                            print "<span>Шахарит</span><span>שחרית</span>";
                                        }
                                        ?>
                                    </div>
                                    <div class="leftside-time" >
                                        <?php if($gregorianDayWeekCT==0){
                                            echo "9:00";
                                        }elseif ($gregorianDayWeekCT==6){
                                            if($shabesMevorkhim){
                                                echo "8:30";
                                            }else{
                                                echo "--:--";
                                            }

                                        }else{
                                            echo "8:30";
                                        }?>

                                    </div>
                                </div>
                                <div class="between-row-spacer"></div>
                            </td>
                        </tr>
                        <!-- Шахарис 9:15 -->
                        <tr>
                            <td>
                                <div id='shakharit915' class="white">
                                    <div class="leftside-title">
                                        <span>Шахарит</span><span>שחרית</span>
                                    </div>
                                    <div class="leftside-time" >
                                        <?php if($gregorianDayWeekCT==0){
                                            echo "--:--";
                                        }elseif ($gregorianDayWeekCT==6){
                                            echo "--:--";
                                        }else{
                                            echo "9:15";
                                        }?>

                                    </div>
                                </div>
                                <div class="between-row-spacer"></div>
                            </td>
                        </tr>
                        <!-- Шахарис 10 -->
                        <tr>
                            <td>
                                <div id='shakharit1000' class="white">
                                    <div class="leftside-title">
                                        <span>Шахарит</span><span>שחרית</span>
                                    </div>
                                    <div class="leftside-time">10:00</div>
                                </div>
                                <div class="between-row-spacer"></div>
                            </td>
                        </tr>
                        <!-- Шахарис 11 -->
                        <tr>
                            <td>
                                <div id='shakharit1100' class="white">
                                    <div class="leftside-title">
                                        <span>Шахарит</span><span>שחרית</span>
                                    </div>
                                    <div class="leftside-time">
                                        <?php
                                            if ($gregorianDayWeekCT==6){
                                                echo "--:--";
                                            }else{
                                                echo "11:00";
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="between-row-spacer"></div>
                            </td>
                        </tr>
                        <!-- Минха ранняя -->
                        <tr>
                            <td>
                                <div id='minkhagdola' class="white">
                                    <div class="leftside-title">
                                        <span>Минха Гдола</span><span>מנחה גדולה</span>
                                    </div>
                                    <div class="leftside-time">
                                        <?php
                                            if ($sunrise != "" && $sunset != "") {
                                                $minchaPossibleTime = GetProportionalHours(6.5, $sunrise, $sunset);
                                                $h = $complexZmanimCalendar->minchaGedolaBaalHatanya->format("H");
                                                $m = $complexZmanimCalendar->minchaGedolaBaalHatanya->format("i");
                                                if($minchaPossibleTime[0] > $h || ($minchaPossibleTime[0] == $h && $minchaPossibleTime[1] > $m)){
                                                    $myTime = $minchaPossibleTime;
                                                }else{
                                                    $myTime = [intval($h), intval($m)];
                                                }
                                                echo FormatTime($myTime);
                                                $timers['minkhagdola'] =  computeDiff2($myTime[0],$myTime[1]);
                                            } else {
                                                echo "---";
                                                $timers['minkhagdola'] =  computeDiff2(0);
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="between-row-spacer"></div>
                            </td>
                        </tr>
                        <?php
                        if ($leto){
                            $timers['minkha1800'] =  computeDiff2(18,0);
                            echo "
                        <!-- Минха 3 -->
                        <tr>
                            <td>
                                <div id='minkha1800' class=\"white\">
                                    <div class=\"leftside-title\">
                                        <span>Минха</span><span>מנחה</span>
                                    </div>
                                    <div class=\"leftside-time\">18:00</div>
                                </div>
                                <div class=\"between-row-spacer\"></div>
                            </td>
                        </tr>
                            ";
                        }
                        ?>

                        <!-- Минха 2 -->
                        <tr>
                            <td>
                                <div id='minkhaktana' class="white">
                                    <div class="leftside-title">
                                        <span>Минха перед шкией</span><span>מנחה בזמנה</span>
                                    </div>
                                    <div class="leftside-time">
                                        <?php
                                            // проверяем есть ли файл с ручным временем. если есть - используем его
                                            if (($handle = @fopen("minha.csv", "r")) !== FALSE) {
                                                $found = false;
                                                while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                                                    if($data[0]==  $gregorianDayCT && $data[1]== $gregorianMonthCT){
                                                        echo $data[2];
                                                        $found = true;
                                                        $temp = explode(":", $data[2]);
                                                        $timers['minkhaktana'] =  computeDiff2($temp[0],$temp[1]);
                                                    }
                                                }
                                                if(!$found){
                                                    echo "<!-- no record for $gregorianDayCT && $gregorianMonthCT -->";
                                                    $t = SubtractMinutes($sunset, 20);
                                                    echo FormatTime($t);
//                                                    echo FormatTime(SubtractMinutes($sunset, 20));
                                                    $timers['minkhaktana'] =  computeDiff2($t[0],$t[1]);
                                                }

                                                fclose($handle);
                                            }else{
                                                echo "<!-- no file -->";
                                                echo FormatTime(SubtractMinutes($sunset, 20));
                                                $t = SubtractMinutes($sunset, 20);
                                                $timers['minkhaktana'] =  computeDiff2($t[0],$t[1]);
                                            }

                                        ?>
                                    </div>
                                </div>
                                <div class="between-row-spacer"></div>
                            </td>
                        </tr>
                        <!-- Маарив -->
                        <tr>
                            <td>
                                <div id='maariv' class="white">
                                    <div class="leftside-title">
                                        <span>Маарив самый ранний</span><span>מעריב בזמנו</span>
                                    </div>
                                    <div class="leftside-time">
                                        <?php
                                            if ($firstMaarivTime != ""){
                                                if($gregorianDayWeekCT==6){
                                                    $shabatHours = intval($shabatComplexZmanimCalendar->tzais->format("H"));
                                                    $shabatMinutes = intval($shabatComplexZmanimCalendar->tzais->format("i"));
                                                    $shabatSeconds = intval($shabatComplexZmanimCalendar->tzais->format("s"));
                                                    if($shabatSeconds>0){
                                                        $shabatMinutes++;
                                                    }
                                                    echo FormatTime([$shabatHours, $shabatMinutes]);

                                                    $timers['maariv'] =  computeDiff2($shabatHours,$shabatMinutes);

                                                }else{
                                                    echo FormatTime($firstMaarivTime);
                                                    $timers['maariv'] =  computeDiff2($firstMaarivTime[0],$firstMaarivTime[1]);

                                                }
                                            }  else {
                                                echo "---";
                                                $timers['maariv'] = 0;
                                            }
                                        ?>
                                    </div>
                                </div>
                                <div class="between-row-spacer"></div>
                            </td>
                        </tr>
                        <?php
                        if ($maariv18){


                            echo "
                        <!-- Маарив 18 -->
                        <tr>
                            <td>
                                <div id='maariv1800' class=\"white\">
                                    <div class=\"leftside-title\">
                                        <span>Маарив</span><span>מעריב</span>
                                    </div>
                                    ";
                            if ($gregorianDayWeekCT==6){
                                $timers['maariv1800'] =  computeDiff2(0,0);
                                echo "<div class=\"leftside-time\">--:--</div>";
                            }else{
                                $timers['maariv1800'] =  computeDiff2(18,0);
                                echo "<div class=\"leftside-time\">18:00</div>";
                            }
                            echo "    
                                </div>
                                <div class=\"between-row-spacer\"></div>
                            </td>
                        </tr>
                            ";
                        }
                        ?>

                        <!-- Маарив 22 -->
                        <tr>
                            <td>
                                <div id='maarivlast' class="white">
                                    <div class="leftside-title">
                                        <span>Маарив</span><span>מעריב</span>
                                    </div>
                                    <?php
                                        if($firstMaarivTime[0]>=22 || ($firstMaarivTime[0]==21 && $firstMaarivTime[1]>=51)){
                                            print '<div class="leftside-time">--:--</div>';
                                            $timers['maarivlast'] =  0;
                                        }else{
                                            if($gregorianDayWeekCT==6){
                                                print '<div class="leftside-time">--:--</div>';
                                                $timers['maarivlast'] =  computeDiff2(0,0);
                                            }else{
                                                print '<div class="leftside-time">22:00</div>';
                                                $timers['maarivlast'] =  computeDiff2(22,0);
                                            }
                                        }
                                    ?>
                                </div>
                                <div class="between-row-spacer"></div>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- Часы + Вставка-->
                <div class="col-6" id='content'>
                    <div class="canva_wrapper">
                        <div id="adverbs_left" class="adverbs left">
                            <strong style="text-align: center;width: 100%;display: block;">Айом Йом</strong>
                            <div>

                            <?php
                                //echo (file_exists(getcwd()."/left.txt")) ? file_get_contents(getcwd()."/left.txt") : "";
                                include("haiomFromChabad.php");
                            ?>
                            </div>
                        </div>
                        <canvas id='clock' width='550' height='550' >Извините, ваш браузер не поддерживает тег canvas</canvas>
                        <div id="adverbs_right" class="adverbs right">
                            <strong style="text-align: center;width: 100%;display: block;">Рамбам</strong><br/>
                            <div>

                            <?php
                                //echo (file_exists(getcwd()."/right.txt")) ? file_get_contents(getcwd()."/right.txt") : "";
                                include("rambam.php");
                            ?>
                            </div>
                        </div>

                    </div>
                    <div class="marquee_wrapper">
                        <div class="marquee" id="marquee">
                            <?php
                            // ищем дату смены вставки на летнюю
                            $firstDayOfNewYear = DateTime::createFromFormat('n/j/Y',jdtogregorian(jewishtojd(1,1,$jewishYearCT+1)))->setTime(0,0,0,0);
                            $firstDayAfterPesakh = DateTime::createFromFormat('n/j/Y',jdtogregorian(jewishtojd(8,23,$jewishYearCT)))->setTime(0,0,0,0);
                            $firstDayOfPesakh = jewishtojd(8,15,$jewishYearCT);
                            $firstDayOfPesakh = jdtogregorian($firstDayOfPesakh);
                            $firstDayOfPesakh = DateTime::createFromFormat('n/j/Y', $firstDayOfPesakh)->setTime(0,0,0,0);
                            $secondDayOfPesakh = jewishtojd(8,16,$jewishYearCT);
                            $secondDayOfPesakh = jdtogregorian($secondDayOfPesakh);
                            $secondDayOfPesakh = DateTime::createFromFormat('n/j/Y', $secondDayOfPesakh)->setTime(0,0,0,0);
                            $firstDayOfShavuot = jewishtojd(10,6,$jewishYearCT);
                            $firstDayOfShavuot = jdtogregorian($firstDayOfShavuot);
                            $firstDayOfShavuot = DateTime::createFromFormat('n/j/Y', $firstDayOfShavuot)->setTime(0,0,0,0);
    //                        print_r($firstDayOfPesakh);

                            $shminiAzeret = jewishtojd(1,22,$jewishYearCT+1);
                            $shminiAzeret = jdtogregorian($shminiAzeret);
                            //        print_r($shminiAzeret);
                            $shminiAzeret = DateTime::createFromFormat('n/j/Y', $shminiAzeret)->setTime(0,0,0,0);
    //                        print_r($shminiAzeret);

                            $insertion = "";
                            if(isLeap($year+1)==1){
                                $sixtyDaysAfter = DateTime::createFromFormat('n/j/Y', "12/6/$year")->setTime(0,0,0,0);
                            }else{
                                $sixtyDaysAfter = DateTime::createFromFormat('n/j/Y', "12/5/$year")->setTime(0,0,0,0);
                            }
    //                        print_r($sixtyDaysAfter);
    //                        print_r($today);
    //                        print_r($firstDayOfPesakh);

                            if($firstDayOfPesakh < $today && $today < $shminiAzeret){
                                $insertion .= "МОРИД АТАЛЬ и ";
                            }else{
                                $insertion .= "МАШИВ АРУАХ УМОРИД АГЕШЕМ и ";
                            }

                            if ($firstDayOfPesakh->format('Y')===$today->format('Y')){
                                if ($firstDayOfPesakh < $today && $today < $sixtyDaysAfter) {
                                    $insertion .= "БРАХА";
                                } else {
                                    $insertion .= "ТАЛЬ УМАТАР ЛЕВРАХА";
                                }
                            } else if ($today < $sixtyDaysAfter) {
                                $insertion .= "БРАХА";
                            } else {
                                $insertion .= "ТАЛЬ УМАТАР ЛЕВРАХА";
                            }
                            // Рош Ходеш

                            if($jewishDayCT==1 || $jewishDayCT==30 || $roshChodeshDebug || $holAmoed){
                                $insertion .= "<br />ЯАЛЕ ВЕЯВО";
                            }
                            if($tishaBeAv){
                                $insertion .= "<br />НАХЕМ";
                            }
                            if($tzom){
                                $insertion .= "<br />АНЕЙНУ";
                            }
                            // по умолчанию омера нет
                            $omer = 99;
                            if($secondDayOfPesakh <= $today && $today < $firstDayOfShavuot){
                                $interval = $firstDayOfPesakh->diff($today);
                                $omer = $interval->format('%a');
                                if($omer<50){
                                    $insertion .= "<br /><span id='omer'>Сегодня $omer день омера</span>";
                                }
                            }
                            // пиркей авот
//                            if($firstDayAfterPesakh <= $today && $today < $firstDayOfNewYear && date("w", strtotime($today->format("Y-m-d")))=='6'){
                            if($firstDayAfterPesakh <= $today && $today < $firstDayOfNewYear && $gregorianDayWeekCT=='6'){
                                // исключаем 9 ава - не читают пиркей авот
                                if(!($jewishDayCT==9 && $jewishMonthCTName=='Av')) {
                                    require "prikeyavot.php";
                                    $insertion .= "<br />" . getPirkeyAvotPerek() . " глава Пиркей Авот</span>";
                                }
                            }
                            if($shabesMevorkhim && $gregorianDayWeekCT=='6'){
                                $insertion .= "<br />".$shabesMevorkhim;
                            }
                            echo $insertion;
                            ?>
                        </div>
                    </div>
                    <div class="ads">
                        <div class="row">
                            <div class="col-4">
                                <div class="advs-block">
                                    <span class="leftside-title">
                                        Урок Талмуда по будням с р.&nbsp;Марзелем
                                    </span>
                                    <span class="leftside-title">
                                        שיעור גמרא עם הרב מרזל כל בוקר
                                         מיום שני עד שישי
                                    </span>
                                    <span class="leftside-time">9:30</span>
                                </div>

                            </div>
                            <div class="col-4">
                                <div class="advs-block">
                                    <span class="leftside-title">Урок по Таньи от главного раввина России р. Берл Лазара в воскресенье
                                    </span>
                                    <span class="leftside-title">
                                         שיעור תניא יום ראשון בבוקר עם&nbsp;הרב&nbsp;הראשי&nbsp;שליט''א</span>
                                    <span class="leftside-time">10:00</span>
                                </div>

                            </div>
                            <div class="col-4">
                                <div class="advs-block">
                                    <span class="leftside-title">
                                        Урок хасидута от главного раввина России р. Берл Лазара в шабат
                                    </span>
                                    <span class="leftside-title">
                                        שיעור חסידות שבת בבוקר עם&nbsp;הרב&nbsp;הראשי&nbsp;שליט''א
                                    </span>
                                    <span class="leftside-time">9:30</span>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-- Правый блок со временем -->
                <div class="col-3" id='sidebar'>
                    <?php
                    if ($location != "") {
                        include "myleftside.php";
                    }
                    ?>
                </div>

            </div>
            <div class="row" id='footer'>
                <div class="col-3"></div>
                <div class="col-6 dedication">
                    В память р. Исроэль Рэфоэля сына р. Арье Зеева
                </div>
                <div class="col-1"></div>
                <div class="credits col-2">
                    © Еврейский университет / LaSil/IT
                </div>

            </div>
        </div>

    <script type='text/javascript'>
        let oldSeconds = 0;
        let curDate = new Date();
        function updateClock() {
            let t = new Date();
            // обновим страницу чтобы была новая дата
            if (t.getDate()!=curDate.getDate()){
                window.location.reload();
            }
            let clockArms = [t.getHours(), t.getMinutes(), t.getSeconds()];
            if (clockArms[2] == oldSeconds) return; //секунды не менялись? выйти
            oldSeconds = clockArms[2];

            let c = document.getElementById('clock');
            let ctx = c.getContext('2d');
            //очистить канву:
            ctx.fillStyle = 'rgba(0, 0, 0, 0)';
            ctx.fillRect(0, 0, c.width, c.height);
            //нарисовать контур часов:

            let x = Math.round(c.width/2);
            let y = Math.round(c.height/2);
            let r = Math.round(Math.min(x,y))-4;
            ctx.beginPath();
            ctx.arc(x,y,r,0,2*Math.PI,true);
            ctx.fillStyle = 'rgb(255,255,255)';
            ctx.fill();

            ctx.lineWidth = 4;
            ctx.shadowColor = 'rgb(128,0,0)';
            ctx.shadowOffsetX = 0;
            ctx.shadowOffsetY = 0;
            ctx.shadowBlur = 0;
            //enableShadow(ctx, false);
            ctx.strokeStyle = 'rgb(235,215,180)';
            ctx.stroke();
            ctx.closePath();

            //нарисовать метки циферблата и цифры:
            ctx.save();
            ctx.textBaseline = 'middle';
            ctx.textAlign = 'center';


            let delta = Math.max(8,Math.round(r/10)); //для размера шрифта и отсечек
            ctx.font = 'bold '+delta+'pt sans-serif';
            let u=Math.PI/2;
            let r1=r-delta;
            for (let i=1; i<=12; i++) {
                ctx.beginPath();
                let x1 = x+Math.round(r1*Math.cos(u)), //так можно узнать позиции делений циферблата
                    y1 = y-Math.round(r1*Math.sin(u)),
                    x2 = x+Math.round(r*Math.cos(u)),
                    y2 = y-Math.round(r*Math.sin(u));
                ctx.strokeStyle = 'rgb(99,99,99)';
                ctx.moveTo(x1,y1);
                //ctx.lineTo(x2,y2);
                u+=Math.PI/6;
                ctx.stroke();
                ctx.closePath();
                ctx.beginPath();
                ctx.fillStyle = 'rgb(33,33,33)';
                ctx.fillText (''+(13-i),x1,y1); //а так вывести цифры по часовому кругу
                ctx.fill();
                ctx.closePath();
            }
            ctx.restore();
            //нарисовать стрелки:
            clockArms[1] += clockArms[2] / 60;
            clockArms[0] += clockArms[1] / 60;
            drawClockArm(ctx, x, y, clockArms[0] * 30, 2*r/2.5 - 20, 10, 'navy');
            drawClockArm(ctx, x, y, clockArms[1] * 6,  2*r/2.2 - 12, 6, 'darkgreen');
            drawClockArm(ctx, x, y, clockArms[2] * 6,  2*r/2.0 - 12,  3, 'darkred');
        }
        function drawClockArm(ctx, x,y, degrees, len, lineWidth, style) {
            ctx.save();
            ctx.lineWidth = lineWidth;
            ctx.lineCap = 'round';
            ctx.translate(x, y);
            ctx.rotate((degrees - 90) * Math.PI / 180);
            ctx.strokeStyle = style;
            ctx.beginPath();
            ctx.moveTo(-len / 10, 0);
            ctx.lineTo(len, 0);
            ctx.stroke();
            ctx.restore();
        }
    </script>
    <script>
        function addYaaleVeYavo() {
            let date = new Date();
            let maarivHour = <?php echo $firstMaarivTime[0]?>;
            let maarivMinute = <?php echo $firstMaarivTime[1]?>;
            let curHour = date.getHours();
            let curMinute = date.getMinutes();
            // считаем разницу до маарива чтобы поставить таймер на обновление вставки
            let diff = ((maarivHour-curHour)*60+(maarivMinute-curMinute))*60*1000;
            if (diff>0) {
                let marquee = document.getElementById("marquee");
                window.setTimeout(() => {
                    if (!marquee.innerHTML.includes("ЯАЛЕ ВЕЯВО")) {
                        marquee.innerHTML += "<br />ЯАЛЕ ВЕЯВО";
                    }
                }, diff)
            }
        }
        function prepareTomorrow() {
            let date = new Date();
            let maarivHour = <?php echo $firstMaarivTime[0]?>;
            let maarivMinute = <?php echo $firstMaarivTime[1]?>;
            let curHour = date.getHours();
            // let omerDate = '';
            <?php
                if(isset($interval)) {
                    print "
                    // secondDayOfPesakh =" . $secondDayOfPesakh->format('Y-m-d') . "\n" .
                        "// today =" . $today->format('Y-m-d') . "\n" .
                        "// interval =" . $interval->format('%a') . "\n" .
                        "// days =" . $interval->d . "\n";
                }else{
                    print '// no omer' ."\n";
                }

            ?>
            let curMinute = date.getMinutes();
            // считаем разницу до маарива чтобы поставить таймер на обновление даты
            let diff = ((maarivHour-curHour)*60+(maarivMinute-curMinute))*60*1000;
            if (diff>0) {
                let marquee = document.getElementById("marquee");
                let omer = document.getElementById("omer");
                let jewishDate = document.getElementById("jewishDate");
                window.dateUpdate = window.setTimeout(() => {
                    jewishDate.innerText = '<?php echo $tomorrowJewishDayCT . " " . $heMonth[$tomorrowJewishMonthCTName] . " " . $tomorrowJewishYearCT; ?>';
                    <?php
                    if($omer+1<50){
                        $tomorrowOmer = $omer+1;
                        print "omer.innerText = 'Сегодня $tomorrowOmer день омера';";
                    }
                    ?>

                }, diff)
            }
        }
        function removeYaaleVeYavo() {
            let date = new Date();
            let maarivHour = <?php echo $firstMaarivTime[0]?>;
            let maarivMinute = <?php echo $firstMaarivTime[1]?>;
            let curHour = date.getHours();
            let curMinute = date.getMinutes();
            // считаем разницу до маарива чтобы поставить таймер на обновление вставки
            let diff = ((maarivHour-curHour)*60+(maarivMinute-curMinute))*60*1000;
            if (diff>0) {
                let marquee = document.getElementById("marquee");
                window.setTimeout(() => {
                    if (marquee.innerHTML.includes("ЯАЛЕ ВЕЯВО")) {
                        marquee.innerHTML = marquee.innerHTML.replace("<br>ЯАЛЕ ВЕЯВО","");
                    }
                }, diff)
            }
        }
    </script>
    <!-- разделили на две части, чтобы часы в любом случае работали -->
    <script>
        function initClock() {
            window.setInterval(updateClock, 333); //интервал обновления - треть секунды
            prepareTomorrow();
            // ставим обработчик на рош ходеш
            <?php
            if($jewishDayCT==29 || $jewishDayCT==30){
                echo "addYaaleVeYavo();";
            }
            if($jewishDayCT==1 || $jewishDayCT==30){
                echo "removeYaaleVeYavo();";
            }
            ?>
        }
        onload = initClock;
    </script>
    <script>
        // Подгоняем размер шрифта айом йом
        document.addEventListener("DOMContentLoaded", ()=>{
            let advleft = document.getElementById('adverbs_left');
            while(advleft.offsetHeight>550){
                let style = window.getComputedStyle(advleft, null).getPropertyValue('font-size');
                let fontSize = parseFloat(style);
                advleft.style.fontSize = (fontSize - 1) + 'px';
            }
        });

    </script>
    <?php

        if($gregorianDayWeekCT==0){
            $timers['shakharit730'] =  computeDiff2(8,0);
            $timers['shakharit830'] =  computeDiff2(9,0);
            $timers['shakharit915'] = 0;
            $timers['shakharit1100'] =  computeDiff2(11,0);
        }elseif ($gregorianDayWeekCT==6){
            $timers['shakharit730'] = 0;
            $timers['shakharit830'] = 0;
            $timers['shakharit915'] = 0;
            $timers['shakharit1100'] = 0;
        }else{
            $timers['shakharit730'] =  computeDiff2(7,30);
            $timers['shakharit830'] =  computeDiff2(8,30);
            $timers['shakharit915'] =  computeDiff2(9,15);
            $timers['shakharit1100'] =  computeDiff2(11,0);
        }
        $timers['shakharit1000'] =  computeDiff2(10,00);
        if($complexZmanimCalendar->alosHashachar){
            $timers['alot'] =  computeDiff($complexZmanimCalendar->alosHashachar);
        }
        $timers['netz'] =  computeDiff($complexZmanimCalendar->sunrise);
        $timers['shma'] =  computeDiff($complexZmanimCalendar->sofZmanShmaBaalHatanya);
        $timers['hatzot'] =  computeDiff($complexZmanimCalendar->chatzos);
        $timers['shkia'] =  computeDiff($complexZmanimCalendar->sunset);
        $timers['zeit'] =  computeDiff($complexZmanimCalendar->tzaisBaalHatanya);
        print "<!--";
        print_r($timers);
        print "-->";

        foreach ($timers as $key => $value){
            if($value!==false){
                $value = $value*60*1000;
                print "
                    <script>
                        window.$key = setTimeout(()=>{
                            let e = document.getElementById('$key');
                            e.classList.add('gone');
                            
                        },$value)
                    </script>
                ";
            }
        }
    ?>
    <noscript>Извините, для работы приложения нужен включённый Javascript</noscript>
    <script>
        document.addEventListener("DOMContentLoaded", ()=>{
            let mainBlock = document.getElementById('mainBlock');
            let footer = document.getElementById('footer');
            let mainBlockTop = mainBlock.getBoundingClientRect().top;
            let footerHeight = footer.getBoundingClientRect().height;
            let mainBlockHeight = screen.availHeight - mainBlockTop - footerHeight;

        })
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </body>
</html>