<html>
    <head>
        <title> MJCC </title>
<!--        <link rel="icon" href="old/logo-header.png" type="image/x-icon">-->
<!--        TODO Переверстать под интернет адаптивно-->
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Молитвы Марьина Роща">
        <meta name="keywords" content="молитвы, zmanim, зманим, время молитв, марьина роща, меоц">
        <meta http-equiv="refresh" content="3600">
        <meta http-equiv="Cache-Control" content="no-cache">
        <?php
        if($_SERVER['SERVER_ADDR']=='127.0.0.1') {
        ?>
            <link rel="stylesheet" type="text/css" href="style.css">
            <link rel="stylesheet" type="text/css" href="style2.css">
        <?php }else{ ?>
            <link rel="stylesheet" type="text/css" href="zmanim.lasil.ru.css">
        <?php } ?>
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
        $gregorianDayWeekCT = date("w");
        print "\n<!-- dayweek: $gregorianDayWeekCT -->\n";
        $tomorrowGregorianMonthCT = $tomorrow->format("n");
        $tomorrowGregorianDayCT = $tomorrow->format("j");
        $tomorrowGregorianYearCT = $tomorrow->format("Y");
        $day = date("j");
        $month = date("n");
        $year = date("Y");
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
//            $jewishMonthNamesNonLeap = array("Tishri", "Heshvan", "Kislev", "Tevet",
//                                       "Shevat", "Adar", "", "Nisan",
//                                       "Iyyar", "Sivan", "Tammuz", "Av", "Elul");
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

        $localisation_ru = array(

                'Shacharit' => 'Шахарит',
                'Mincha Gdola' => 'Минха гдола',
                'Mincha' => 'Минха',
                'Mincha before sunset' => 'Минха перед шкией',
                'Maariv at nightfall' => 'Маарив самый ранний',
                'Maariv' => 'Маарив',
                'Dawn' => 'Рассвет',
                'Sunrise' => 'Восход солнца',
                'Latest shema' => 'Конец чтения Шма',
                'Midday' => 'Полдень',
                'Sunset' => 'Заход солнца',
                'Nightfall' => 'Выход звёзд',
                'Candle lighting' => 'Зажигание свечей',
                'Shabbat ends' => 'Выход шаббата',
                'Yom tov ends' => 'Выход йом това',
                'Kabbalat shabbat' => 'Кабалат шаббат',
                'Shacharit shabbat' => 'Шахарит в шаббат',
                'Shacharit yom tov' => 'Шахарит в йом тов',
            'Adv1' => 'Урок хасидута от главного раввина России р. Берл Лазара в шабат',
            'Adv2' => 'Урок по Таньи от главного раввина России р. Берл Лазара в воскресенье',
            'Adv3' => 'Урок Талмуда по будням с р.&nbsp;Марзелем',
            'Dedic' => 'В память р. Исроэль Рэфоэля сына р. Арье Зеева',
        );
        $localisation_he = array(
                'Shacharit' => '‏שחרית‏‎',
                'Mincha Gdola' => 'מנחה גדולה',
                'Mincha' => 'מנחה',
                'Mincha before sunset' => 'מנחה בזמנה',
                'Maariv at nightfall' => 'מעריב בזמנו',
                'Maariv' => 'מעריב',
                'Dawn' => 'עלות השחר',
                'Sunrise' => 'נץ החמה',
                'Latest shema' => 'סוף זמן קריאת שמע',
                'Midday' => 'חצות היום',
                'Sunset' => 'שקיעת החמה',
                'Nightfall' => 'צאת הכוכבים',
                'Candle lighting' => 'הדלקת נרות',
                'Shabbat ends' => 'יציאת שבת',
                'Yom tov ends' => 'מוצאי יום טוב',
                'Kabbalat shabbat' => 'קבלת שבת',
                'Shacharit shabbat' => 'שחרית בשבת',
                'Shacharit yom tov' => 'שחרית יום טוב',
            'Adv1' => 'שיעור חסידות שבת בבוקר עם&nbsp;הרב&nbsp;הראשי&nbsp;שליט\'\'א',
            'Adv2' => 'שיעור תניא יום ראשון בבוקר עם&nbsp;הרב&nbsp;הראשי&nbsp;שליט\'\'א',
            'Adv3' => 'שיעור גמרא עם הרב מרזל כל בוקר
                                             מיום שני עד שישי',
            'Dedic' => 'לע\'\'נ ר\' ישראל רפאל ב\'\'ר אריה זאב',

        );
        $localisation_en = array(
                'Shacharit' => 'Shacharit',
                'Mincha Gdola' => 'Mincha gedola',
                'Mincha' => 'Mincha',
                'Mincha before sunset' => 'Mincha before sunset',
                'Maariv at nightfall' => 'Maariv at nightfall',
                'Maariv' => 'Maariv',
                'Dawn' => 'Dawn',
                'Sunrise' => 'Sunrise',
                'Latest shema' => 'Latest shema',
                'Midday' => 'Midday',
                'Sunset' => 'Sunset',
                'Nightfall' => 'Nightfall',
                'Candle lighting' => 'Candle lighting',
                'Shabbat ends' => 'Shabbat ends',
                'Yom tov ends' => 'Yom tov ends',
                'Kabbalat shabbat' => 'Kabbalat shabbat',
                'Shacharit shabbat' => 'Shacharit shabbat',
                'Shacharit yom tov' => 'Shacharit yom tov',
                'Adv1' => 'Hasidut lesson from Chief Rabbi of Russia r. Berl Lazar on Shabbat',
                'Adv2' => 'Lesson on Tanya from Chief Rabbi of Russia r. Berl Lazar on Sunday',
                'Adv3' => 'Talmud lesson on weekdays with r.&nbsp;Marzel',
                'Dedic' => 'In memory of r. Yisroel Refoel son of r. Arie Zeev',
        );

        $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        $localNames = array('ru' => $localisation_ru,
            'en' => $localisation_en,
            'he' => $localisation_he,
            )[$lang];

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

        echo "<!-- jewishMonthCT: $jewishMonthCT -->\n";
        echo "<!-- jewishYearCT: $jewishYearCT -->\n";
        echo "<!-- jewishMonthCTName: $jewishMonthCTName -->\n";

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
                        <span class="parsha-title">
                            Глава Торы
                        </span>
                        <br>
                        <span class="font-size: <?php if(strpos($torasections, ' ') !== false){echo "parsha-name40";}else{echo "parsha-name70";} ?>">
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
                <div class="col-6 col-xl-3" id='menu' style='text-align: center;'>
                        <!-- Шахарис 7:30 -->
                                <div id='shakharit730' class="white">
                                    <div class="leftside-title">
<!--                                        <span>Шахарит</span><span>שחרית</span>-->
                                        <span><?php echo $localNames['Shacharit'] ?></span>
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
                        <!-- Шахарис 8:30 -->
                                <div id='shakharit830' class="white">
                                    <div class="leftside-title">
                                        <?php
                                        if ($shabesMevorkhim && $gregorianDayWeekCT==6){
                                            print "<span>Теилим</span><span>תהילים</span>";
                                        }else{
                                            print "<span>${localNames['Shacharit']}</span>";
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
                        <!-- Шахарис 9:15 -->
                                <div id='shakharit915' class="white">
                                    <div class="leftside-title">
                                        <span><?php echo $localNames['Shacharit'] ?></span>
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
                        <!-- Шахарис 10 -->
                                <div id='shakharit1000' class="white">
                                    <div class="leftside-title">
                                        <span><?php echo $localNames['Shacharit'] ?></span>
                                    </div>
                                    <div class="leftside-time">10:00</div>
                                </div>
                        <!-- Шахарис 11 -->
                                <div id='shakharit1100' class="white">
                                    <div class="leftside-title">
                                        <span><?php echo $localNames['Shacharit'] ?></span>
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
                        <!-- Минха ранняя -->
                                <div id='minkhagdola' class="white">
                                    <div class="leftside-title">
                                        <span><?php echo $localNames['Mincha Gdola'] ?></span>
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
                        <?php
                        if ($leto){
                            $timers['minkha1800'] =  computeDiff2(18,0);
                            echo "
                        <!-- Минха 3 -->
                                <div id='minkha1800' class=\"white\">
                                    <div class=\"leftside-title\">
                                        <span>";
                            echo $localNames['Mincha'];
                            echo "</span>
                                    </div>
                                    <div class=\"leftside-time\">18:00</div>
                                </div>
                            ";
                        }
                        ?>

                        <!-- Минха 2 -->
                                <div id='minkhaktana' class="white">
                                    <div class="leftside-title">
                                        <span><?php echo $localNames['Mincha before sunset'] ?></span>
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
                                                        print("<!--".$temp[0]." -- ".$temp[1]."-->");
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
                        <!-- Маарив -->
                                <div id='maariv' class="white">
                                    <div class="leftside-title">
                                        <span><?php echo $localNames['Maariv at nightfall'] ?></span>
                                    </div>
                                    <div class="leftside-time">
                                        <?php
                                            if ($firstMaarivTime != ""){
                                                echo FormatTime($firstMaarivTime);
                                                $timers['maariv'] =  computeDiff2($firstMaarivTime[0],$firstMaarivTime[1]);
                                            }  else {
                                                echo "---";
                                                $timers['maariv'] = 0;
                                            }
                                        ?>
                                    </div>
                                </div>
                        <?php
                        if ($maariv18){
                            $timers['maariv'] =  computeDiff2(18,0);
                            echo "
                        <!-- Маарив 18 -->
                                <div id='maariv1800' class=\"white\">
                                    <div class=\"leftside-title\">
                                        <span>${localNames['Maariv']}</span>
                                    </div>
                                    <div class=\"leftside-time\">18:00</div>
                                </div>
                            ";
                        }
                        ?>

                        <!-- Маарив 22 -->
                                <div id='maarivlast' class="white">
                                    <div class="leftside-title">
                                        <span><?php echo $localNames['Maariv'] ?></span>
                                    </div>
                                    <?php
                                        if($firstMaarivTime[0]>=22 || ($firstMaarivTime[0]==21 && $firstMaarivTime[1]>=51)){
                                            print '<div class="leftside-time">--:--</div>';
                                            $timers['maarivlast'] =  0;
                                        }else{
                                            print '<div class="leftside-time">22:00</div>';
                                            $timers['maarivlast'] =  computeDiff2(22,0);
                                        }
                                    ?>
                                </div>
                </div>

                <!-- Правый блок со временем -->
                <div class="col-6 col-xl-3" id='sidebar'>
                    <?php
                    if ($location != "") {
                        include "myleftside.php";
                    }
                    ?>
                </div>

            </div>
            <div class="row">
                <div class="ads">
                    <div class="col-12">
                        <div class="advs-block">
                                        <span class="leftside-title">
                                            <?php echo $localNames['Adv1'] ?>
                                        </span>
                            <span class="leftside-time">9:30</span>
                        </div>
                    </div>
                        <div class="col-12">
                            <div class="advs-block">
                                        <span class="leftside-title">
                                            <?php echo $localNames['Adv2'] ?>
                                        </span>
                                <span class="leftside-time">10:00</span>
                            </div>

                        </div>
                    <div class="col-12">
                        <div class="advs-block">
                                        <span class="leftside-title">
                                            <?php echo $localNames['Adv3'] ?>
                                        </span>
                            <span class="leftside-time">9:30</span>
                        </div>

                    </div>


                </div>
            </div>

            <div class="row" id='footer'>
                <div class="col-12 col-xl-3"></div>
                <div class="col-12 col-xl-6 dedication">
                    <?php echo $localNames['Dedic'] ?>
                </div>
                <div class="col-12 col-xl-1"></div>
                <div class="credits col-12 col-xl-2">
                    <div>
                        © Еврейский университет / <a href="https://lasil.ru">LaSil/IT</a>
                    </div>
                </div>

            </div>
<!--            <div class="row">-->
<!--                <div class="col-12">-->
<!--                    <button type="button" id="subscribe">Напомнить о миньянах</button>-->
<!--                </div>-->
<!--            </div>-->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>


    </body>
</html>