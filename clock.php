<html>
    <head>
        <title>Алахические часы</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="Алахические часы">
        <meta name="keywords" content="молитвы, zmanim, зманим, время молитв">
        <meta http-equiv="refresh" content="3600">
        <meta http-equiv="Cache-Control" content="no-cache">
        <link rel="stylesheet" type="text/css" href="css/clock.css">
        <script language="JavaScript" src="clock.js"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    </head>
    <body>
    <?php
        error_reporting(E_ALL);
        // то же для локации
        if (isSet($_REQUEST["activelocation"])) {
            $activelocation = $_REQUEST["activelocation"];
        } else {
            $activelocation = "Moskau";
        }
        date_default_timezone_set('Europe/Moscow');
        $timezone = date_default_timezone_get();
        $currh = date('H');
        $currm = date('i');
        print "<!-- $currh: $currm -->";
        print "<!-- $timezone -->";

        // Перевод названий еврейских месяцев с английского на русский.
        $heMonth = array ( 'Tishri' => 'Тишрея', 'Heshvan' => 'Хешвана', 'Kislev' => 'Кислева', 'Tevet' => 'Тевета', 'Shevat' => 'Швата', 'Adar' => 'Адара', 'AdarI' => 'Адара 1-го', 'AdarII' => 'Адара 2-го', 'Nisan' => 'Нисана', 'Iyyar' => 'Ияра', 'Sivan' => 'Сивана', 'Tammuz' => 'Тамуза', 'Av' => 'Ава', 'Elul' => 'Элула');
        $ruMonth = array ( 'January' => 'Январь','February' => 'Февраль', 'March' => 'Март', 'April' => 'Апрель', 'May' => 'Май', 'June' => 'Июнь', 'July' => 'Июль', 'August' => 'Август', 'September' => 'Сентябрь', 'October' => 'Октябрь', 'November' => 'Ноябрь', 'December' => 'Декабрь');

        // Названия грегорианских месяцев
        $gregorianMonthNames = array("Января", "Февраля", "Марта", "Апреля",
            "Мая", "Июня", "Июля", "Августа", "Сентября",
            "Октября", "Ноября", "Декабря");
        // Названия дней недели
        $dw = array( '0'=>'воскресенье', '1'=>'понедельник', '2'=>'вторник', '3'=>'среда', '4'=>'четверг', '5'=>'пятница', '6'=>'суббота');

        $today = new DateTime('NOW');
        $tomorrow = new DateTime('tomorrow');
        $gregorianMonthCT = date("n"); // $month
        $gregorianDayCT = date("j"); // $day
        $gregorianYearCT = date("Y"); // $year
        $gregorianDayWeekCT = date("w");
        print "\n<!-- dayweek: $gregorianDayWeekCT -->\n";
        $tomorrowGregorianMonthCT = $tomorrow->format("n");
        $tomorrowGregorianDayCT = $tomorrow->format("j");
        $tomorrowGregorianYearCT = $tomorrow->format("Y");


        // для заблюривания прошедшего времени
        $timers = array();
        function computeDiff($t){
//            print("2022-12-6 16:08"<"2022-12-7 09:28");
//            print($t->format('Y-n-j H:i').'\n');
//            print(date('Y-n-j H:i'));
            if($t->format('Y-n-j H:i')<date('Y-n-j H:i')){
                return 0;
            }
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
        function computeDiffMin($minutes){

            $currh = date('H');
            $currm = date('i');
            print("$currh, $currm, $minutes\n");
            $diff = $currh*60 + $currm - $minutes;
            if ($diff>0)
                return $diff;
            else
                return 0;
        }
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
                "Shevat", "Adar", "", "Nisan",
                "Iyyar", "Sivan", "Tammuz", "Av", "Elul");
            if (isJewishLeapYear($jewishYear))
                return $jewishMonthNamesLeap[$jewishMonth-1];
            else
                return $jewishMonthNamesNonLeap[$jewishMonth-1];
        }
        function zmanToMinutesFrom0($zman){
            $h = (int)$zman->format("H");
            $m = (int)$zman->format("i");
            $rezult = $h*60+$m;
            return $rezult;
        }
        function timeFromMinutes($minutes){
            $Y = $GLOBALS['gregorianYearCT'];
            $M = $GLOBALS['gregorianMonthCT'];
            $D = $GLOBALS['gregorianDayCT'];

            if($minutes<0){
                $yesterday = date_create("$Y-$M-$D");
                $yesterday->modify("-1 day");
                $minutes = $minutes+60*24;
                $h = intdiv($minutes, 60);
                $m = $minutes - $h*60;
                $yesterday->setTime($h, $m);
                return $yesterday;
            } else {
                $h = intdiv($minutes, 60);
                $m = $minutes - $h*60;
                $result = date_create("$Y-$M-$D $h:$m");
                return $result;
            }

        }
        function FormatTime($zman) {
            $hour = (int)$zman->format("H");
            $min = (int)$zman->format("i");
            if ($min < 10)
                $minStr = "0" . $min;
            else
                $minStr = $min;
            return $hour . ":" . $minStr;
        }
        // https://github.com/zachweix/PhpZmanim
        require 'vendor/autoload.php';
        use PhpZmanim\Zmanim;
        use PhpZmanim\Calendar\ComplexZmanimCalendar;
        use PhpZmanim\Geo\GeoLocation;
        // $geoLocation = new GeoLocation("MJCC", 55.790853, 37.608302, 186, "Europe/Moscow");
        $geoLocation = new GeoLocation("MJCC", 55.790853, 37.608302, 0, "Europe/Moscow");


        $complexZmanimCalendar = new ComplexZmanimCalendar($geoLocation, $gregorianYearCT, $gregorianMonthCT, $gregorianDayCT);

        echo "<!-- СМ тут: восход, конце Шма Танья/Гра-->\n";
        $chatzos = $complexZmanimCalendar->chatzos;
        $minchaGedolaBaalHatanya = $complexZmanimCalendar->minchaGedolaBaalHatanya;
        $midnight = zmanToMinutesFrom0($chatzos) - 60*12;
        //раница в полчаса - умножаем на два
        $shaaZmanitYom = (zmanToMinutesFrom0($minchaGedolaBaalHatanya) - zmanToMinutesFrom0($chatzos))*2;
        // 24*60 - временной час дня*12 и делим это на 12
        $shaaZmanitLaila = (24*60 - $shaaZmanitYom*12)/12;

        $endOfDay = timeFromMinutes(zmanToMinutesFrom0($chatzos)+$shaaZmanitYom*6);
        if( computeDiff($endOfDay)===0){
            // нам нужно всё пересчитать, так как новый день начался
            $tommorow = date_create("$gregorianYearCT-$gregorianMonthCT-$gregorianDayCT");
            $tommorow->modify("+1 day");
            $gregorianMonthCT = $tommorow->format("n"); // $month
            $gregorianDayCT = $tommorow->format("j"); // $day
            $gregorianYearCT = $tommorow->format("Y"); // $year

            $complexZmanimCalendar = new ComplexZmanimCalendar($geoLocation, $gregorianDayCT, $gregorianMonthCT, $gregorianDayCT);
            $chatzos = $complexZmanimCalendar->chatzos;
            $minchaGedolaBaalHatanya = $complexZmanimCalendar->minchaGedolaBaalHatanya;
            $midnight = zmanToMinutesFrom0($chatzos) - 60*12;
            //раница в полчаса - умножаем на два
            $shaaZmanitYom = (zmanToMinutesFrom0($minchaGedolaBaalHatanya) - zmanToMinutesFrom0($chatzos))*2;
            // 24*60 - временной час дня*12 и делим это на 12
            $shaaZmanitLaila = (24*60 - $shaaZmanitYom*12)/12;
            $forTomorrow = true;

        }else{
            $forTomorrow = false;
        }


        $values = [
            "Начало ночи" => ['night0', timeFromMinutes($midnight-$shaaZmanitLaila*6)],
            "Выход звёзд/маарив" => ['tzais', $complexZmanimCalendar->tzaisBaalHatanya],
            "1 час ночи" => ['night1', timeFromMinutes($midnight-$shaaZmanitLaila*5)],


            "2 час ночи" => ['night2', timeFromMinutes($midnight-$shaaZmanitLaila*4)],
            "3 час ночи" => ['night3', timeFromMinutes($midnight-$shaaZmanitLaila*3)],
            "Конец первой стражи (4 час ночи)" => ['night4', timeFromMinutes($midnight-$shaaZmanitLaila*2)],
            "5 час ночи" => ['night5', timeFromMinutes($midnight-$shaaZmanitLaila)],
            "Полночь (6 час ночи)" => ['midnight', timeFromMinutes($midnight)],
            "7 час ночи" => ['night7', timeFromMinutes($midnight+$shaaZmanitLaila)],
            "Конец воторой стражи (8 час ночи)" => ['night8', timeFromMinutes($midnight+$shaaZmanitLaila*2)],
            "9 час ночи" => ['night9', timeFromMinutes($midnight+$shaaZmanitLaila*3)],
            "10 час ночи" => ['night10', timeFromMinutes($midnight+$shaaZmanitLaila*4)],
            "Алот аШахар" => ['alos', $complexZmanimCalendar->alosHashachar],
            "11 час ночи" => ['night11', timeFromMinutes($midnight+$shaaZmanitLaila*5)],
            "Конец ночи/Утро" => ['night12', timeFromMinutes($midnight+$shaaZmanitLaila*6)],


            "1 час дня" => ['day1', timeFromMinutes(zmanToMinutesFrom0($chatzos)-$shaaZmanitYom*5)],
            "Восход солнца" => ['sunrise', $complexZmanimCalendar->sunrise],
            "2 час дня" => ['day2', timeFromMinutes(zmanToMinutesFrom0($chatzos)-$shaaZmanitYom*4)],
//            "3 час дня" => ['day3', timeFromMinutes(zmanToMinutesFrom0($chatzos)-$shaaZmanitYom*3)],
            "Конец времени Шма (3 час дня)" => ['shma', $complexZmanimCalendar->sofZmanShmaBaalHatanya],
//            "4 час дня" => ['day4', timeFromMinutes(zmanToMinutesFrom0($chatzos)-$shaaZmanitYom*2)],
            "Конец времени молитвы (4 час дня)" => ['tfila', $complexZmanimCalendar->sofZmanTfilaBaalHatanya],
            "5 час дня" => ['day5', timeFromMinutes(zmanToMinutesFrom0($chatzos)-$shaaZmanitYom)],


            "Минха гдола" => ['mincha1', $minchaGedolaBaalHatanya],
            "7 час дня" => ['day7', timeFromMinutes(zmanToMinutesFrom0($chatzos)+$shaaZmanitYom)],
            "8 час дня" => ['day8', timeFromMinutes(zmanToMinutesFrom0($chatzos)+$shaaZmanitYom*2)],
            "9 час дня" => ['day9', timeFromMinutes(zmanToMinutesFrom0($chatzos)+$shaaZmanitYom*3)],
            "Минха ктана" => ['mincha2', $complexZmanimCalendar->minchaKetanaBaalHatanya],
            "10 час дня" => ['day10', timeFromMinutes(zmanToMinutesFrom0($chatzos)+$shaaZmanitYom*4)],
            "Плаг минха" => ['mincha3', $complexZmanimCalendar->plagHaminchaBaalHatanya],

            "11 час дня" => ['day11', timeFromMinutes(zmanToMinutesFrom0($chatzos)+$shaaZmanitYom*5)],
            "Шкия" => ['shkia', $complexZmanimCalendar->sunset],
            "Конец дня/Ночь" => ['day11', timeFromMinutes(zmanToMinutesFrom0($chatzos)+$shaaZmanitYom*6)],




        ];
        print("<div class='container mt-3'>");
        print("
                <div class='row record'>
                    <div class='col-6 key'>Временной час ночью</div>
                    <div class='col-6 value'>$shaaZmanitLaila мин.</div>
                </div>
                <div class='row record'>
                    <div class='col-6 key'>Временной час днём</div>
                    <div class='col-6 value'>$shaaZmanitYom мин.</div>
                </div>
                ");
        foreach  ($values as $k=>$v){
            list($name, $v) = $v;
            $formattetTime = FormatTime($v);
            $timers[$name] = computeDiff($v);
            print("
            <div id='$name' class='row record'>
                <div class='col-8 key'>$k</div>
                <div class='col-4 value'>$formattetTime</div>
            </div>
            ");
        }
        print("</div>");
        echo "<!-- Временной час днём ". $shaaZmanitYom . "-->\n";
        echo "<!-- Временной час ночью ". $shaaZmanitLaila . "-->\n";

        echo "<!-- Полдень ". $chatzos . "-->\n";
        echo "<!-- Минха гдола ". $minchaGedolaBaalHatanya . "-->\n";

        if($forTomorrow){
            //добавляем к таймерам минуты до конца сегодняшнего дня
            $minutesToTomorrow = (24-date('H'))*60+(60-date('i'));
            foreach(["night7", "night8", "night9", "night10", "alos", "night11", "night12", "day1", "sunrise", "day2", "shma", "tfila", "day5", "mincha1", "day7", "day8", "day9", "mincha2", "day10", "mincha3", "day11", "shkia"] as $k){
                $timers[$k] = $timers[$k]+$minutesToTomorrow;
            }
        }
//    var_dump($timers);
        ?>

        <div class="row" id='footer'>
            <div class="col-3"></div>
            <div class="col-6 dedication">
                ©2022 LaSil/IT
            </div>
            <div class="col-3"></div>
        </div>


        <?php
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