<html>
<head>
    <title> MJCC </title>
    <link rel="icon" href="logo-header.png" type="image/x-icon">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="Free PHP Luach">
    <meta name="keywords" content="Free, Luach, PHP, code, Luach PHP, jewish calendar, hebrew calendar, jüdischer Kalender">
    <meta http-equiv="refresh" content="3600">
    <meta http-equiv="Cache-Control" content="no-cache">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <script language="JavaScript" src="prevnext.js"></script>
</head>
<body>
    <?php
        // TODO правильное время молитв
        // TODO правильное зманим
        // TODO правильные вставки
        // TODO объявления
        // TODO дизайн

        error_reporting(E_ALL);
        include("torah.inc");
        include("dst.inc");
        include("custom.inc");
        include("locations.inc");
        include("times.inc");
        include("zmanim3.inc");
        //include("dafyomi.inc");
        //include("mishnayomit.inc");
        include("holiday.inc");
        function isDiaspora() {
            return true;
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
        // Если в запросе указана дата - показываем. Если нет - актуальную.
        // TODO зачем?
        if (isSet($_REQUEST["jahr"]) && isSet($_REQUEST["Monate"])) {
            $jahr = $_REQUEST["jahr"];
            $monat = $_REQUEST["Monate"];
            $carentday = date("j");
        } else {
            $monat = date("n");
            $jahr = date("Y");
            $carentday = date("j");
        }
        // то же для локации
        if (isSet($_REQUEST["activelocation"])) {
            $activelocation = $_REQUEST["activelocation"];
        } else {
            $activelocation = "Moskau";
        }
        function makeLink($href) {
            return "><img src= border=0></a>";
        }
        // Перевод названий еврейских месяцев с английского на русский.
        $heMonth = array ( 'Tishri' => 'Тишрея', 'Heshvan' => 'Хешвана', 'Kislev' => 'Кислева', 'Tevet' => 'Тевета', 'Shevat' => 'Швата', 'Adar' => 'Адара', 'AdarI' => 'Адара 1-го', 'AdarII' => 'Адара 2-го', 'Nisan' => 'Нисана', 'Iyyar' => 'Ияра', 'Sivan' => 'Сивана', 'Tammuz' => 'Тамуза', 'Av' => 'Ава', 'Elul' => 'Элула');
        $ruMonth = array ( 'January' => 'Январь','February' => 'Февраль', 'March' => 'Март', 'April' => 'Апрель', 'May' => 'Май', 'June' => 'Июнь', 'July' => 'Июль', 'August' => 'Август', 'September' => 'Сентябрь', 'October' => 'Октябрь', 'November' => 'Ноябрь', 'December' => 'Декабрь');

        $gregorianMonthCT = date("n");
        $gregorianDayCT = date("j");
        $gregorianYearCT = date("Y");
        $gregorianDayWeekCT = date("w");
        // Названия грегорианских месяцев
        $gregorianMonthNames = array("Января", "Февраля", "Марта", "Апреля",
                                     "Мая", "Июня", "Июля", "Августа", "Сентября",
                                     "Октября", "Ноября", "Декабря");
        // Названия дней недели
        $dw = array( '0'=>'воскресенье', '1'=>'понедельник', '2'=>'вторник', '3'=>'среда', '4'=>'четверг', '5'=>'пятница', '6'=>'суббота');


        $jdNumberCT = gregoriantojd($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT);
        $jewishDateCT = jdtojewish($jdNumberCT);
        list($jewishMonthCT, $jewishDayCT, $jewishYearCT) = explode('/', $jewishDateCT);
        // Torah section on this week and motzei shabbat
        $location = searchLocation($activelocation);
        $elevation = $location[3];
        $timeStringSb;
        // дней до шабата
        $dif = 6 - $gregorianDayWeekCT;
        $daysInMonth = cal_days_in_month(CAL_JEWISH, $jewishMonthCT, $jewishYearCT);
        $jewishDateSbDay = $jewishDayCT + $dif;
        // Получаем дату шабата и время его конца
        // ЕСЛИ СЕГОДНЯ ШАБАТ
        if($gregorianDayWeekCT == 6) {
            $torasections = getTorahSections($jewishMonthCT, $jewishDayCT, $jewishYearCT);
            $timeSb = GetSunsetDegreesBelowHorizon($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT,
                                8.55,
                                                   $location[0], $location[1], $location[2], $elevation);
            //if($gregorianMonthCT>3 && $gregorianMonthCT<11)
            //  $timeSb = AddMinutes($timeSb, 60);
            $timeStringSb = FormatTime($timeSb);
            $dateSbMonth = $gregorianMonthCT;
            $dateSbDay = $gregorianDayCT;
        } else {
            // если ближайший шабат в этом месяце
            if(checkdate($gregorianMonthCT, $gregorianDayCT+$dif, $gregorianYearCT) != false){
                $jdNumberSB = gregoriantojd($gregorianMonthCT,$gregorianDayCT+$dif,$gregorianYearCT);
                $jewishDateSB = jdtojewish($jdNumberSB);
                list($jewishMonthSB, $jewishDaySB, $jewishYearSB) = explode('/', $jewishDateSB);
                $torasections = getTorahSections($jewishMonthSB, $jewishDaySB, $jewishYearSB);
                $timeSb = GetSunsetDegreesBelowHorizon($gregorianMonthCT,$gregorianDayCT+$dif,$gregorianYearCT,
                                    8.55,
                                                       $location[0], $location[1], $location[2], $elevation);
                //if($gregorianMonthCT>3 && $gregorianMonthCT<11)
                //  $timeSb = AddMinutes($timeSb, 60);
                $timeStringSb = FormatTime($timeSb);
                $dateSbMonth = $gregorianMonthCT;
                $dateSbDay = $gregorianDayCT+$dif;
            // если ближайший шабат в след. месяце
            } elseif(checkdate($gregorianMonthCT + 1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT) != false){
                $jdNumberSB = gregoriantojd($gregorianMonthCT + 1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT);
                $jewishDateSB = jdtojewish($jdNumberSB);
                list($jewishMonthSB, $jewishDaySB, $jewishYearSB) = explode('/', $jewishDateSB);
                $torasections = getTorahSections($jewishMonthSB, $jewishDaySB, $jewishYearSB);
                $timeSb = GetSunsetDegreesBelowHorizon($gregorianMonthCT + 1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT,
                                    8.55,
                                                       $location[0], $location[1], $location[2], $elevation);
                //if(($gregorianMonthCT + 1)>3 && ($gregorianMonthCT + 1)<11) //only summer time
                //  $timeSb = AddMinutes($timeSb, 60);
                $timeStringSb = FormatTime($timeSb);
                $dateSbMonth = $gregorianMonthCT + 1;
                $dateSbDay = ($gregorianDayCT+$dif) - $daysInMonth;
            // если ближайший шабат в след. году
            } elseif(checkdate(1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT + 1) != false) {
                $jdNumberSB = gregoriantojd(1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT + 1);
                $jewishDateSB = jdtojewish($jdNumberSB);
                list($jewishMonthSB, $jewishDaySB, $jewishYearSB) = explode('/', $jewishDateSB);

                $torasections = getTorahSections($jewishMonthSB, $jewishDaySB, $jewishYearSB);
                $timeSb = GetSunsetDegreesBelowHorizon(1, ($gregorianDayCT+$dif) - $daysInMonth, $gregorianYearCT + 1,
                            8.55,
                            $location[0], $location[1], $location[2], $elevation);

                $timeStringSb = FormatTime($timeSb);
                $dateSbMonth = 1;
                $dateSbDay = ($gregorianDayCT+$dif) - $daysInMonth;

            }
        }

        //	if ($torasections != "") {
        //  $ts = "<div style=\"font-family: Courier New; font-size: 12px; color: blue\" >" . //$torasections . "</div>";
        //	}
        // Friday candels on this week

        // Дата пятницы и время начала шабата
        $difF = 5 - $gregorianDayWeekCT;
        // ???? Почему не просто == 5? иначе на шабате это тоже будет срабатывать
//        if($gregorianDayWeekCT > 4){
        if($gregorianDayWeekCT == 5){
            $timeC = GetSunset($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT,
                $location[0], $location[1], $location[2], $elevation);
            //if($gregorianMonthCT>3 && $gregorianMonthCT<11)
                //$timeC = AddMinutes($timeC, 60);
            $timeC = SubtractMinutes($timeC, 18); // 18 minutes before sunset
            $timeStringC = FormatTime($timeC);
            $dateFrMonth = $gregorianMonthCT;
            $dateFrDay = $gregorianDayCT;
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
            } elseif(checkdate($gregorianMonthCT + 1, ($gregorianDayCT+$difF) - $daysInMonth, $gregorianYearCT) != false) {
                $timeC = GetSunset($gregorianMonthCT + 1, ($gregorianDayCT+$difF) - $daysInMonth, $gregorianYearCT,
                    $location[0], $location[1], $location[2], $elevation);
                //if(($gregorianMonthCT + 1)>3 && ($gregorianMonthCT + 1)<11)
                    //$timeC = AddMinutes($timeC, 60);
                $timeC = SubtractMinutes($timeC, 18); // 18 minutes before sunset
                $timeStringC = FormatTime($timeC);
                $dateFrMonth = $gregorianMonthCT + 1;
                $dateFrDay = ($gregorianDayCT+$difF) - $daysInMonth;
            } elseif(checkdate(1, ($gregorianDayCT+$difF) - $daysInMonth, $gregorianYearCT + 1) != false) {
                $timeC = GetSunset(1, ($gregorianDayCT+$difF) - $daysInMonth, $gregorianYearCT + 1,
                                    $location[0], $location[1], $location[2], $elevation);
                $timeC = SubtractMinutes($timeC, 18); // 18 minutes before sunset
                $timeStringC = FormatTime($timeC);
                $dateFrMonth = 1;
                $dateFrDay = ($gregorianDayCT+$difF) - $daysInMonth;
            }
        }

//    Теперь мы знаем дату пятницы, шабата, время зажигания и конца шабата. Проверил 2021/12/23 - совпало с календарём
//        Получаем дату ближайшего праздника - не требуется
//        $proba = getJewishNextHolliday($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT);
//        error_log("getJewishNextHolliday = $proba",3,"D:\\TEMP\\temp\\php.log");
        $jewishMonthCTName = getJewishMonthName($jewishMonthCT, $jewishYearCT);

        /*function NerShabbos(){
        $gregorianMonthCT = date("n");
        $gregorianDayCT = date("j");
        $gregorianYearCT = date("Y");
        $gregorianDayWeekCT = date("w");
        $difF = 5 - $gregorianDayWeekCT;

        }*/
        // TODO проверить, нужны ли эти переменные, вроде уже есть. Особенно location, ведь мы уже искали время шабата...
        $day = date("j");
        $month = date("n");
        $monthStr = jdmonthname(gregoriantojd($month, 1, 2000), 1);
        $year = date("Y");
        $locName = "MJCC";
        $location = searchLocation($locName);
        if ($location != "") {
          $caption = "Zmanim for $day $monthStr $year, $locName";
          $elevation = $location[3];
          $sunrise = GetSunrise($month, $day, $year, $location[0], $location[1], $location[2], $elevation);
          $sunset = GetSunset($month, $day, $year, $location[0], $location[1], $location[2], $elevation);
        }

        echo "<!-- sunrise: ". print_r($sunrise). " -->\n";
        echo "<!-- sunset: ". print_r($sunset). " -->\n";

    echo "
    
    <div id='header'>
    
     <table cellpadding='0' cellspacing='0' style='width: 100%;'>
     <tr>
     <td colspan='3'><div class=\"white\"><span style=\"font-size: 50px;
        font-family: Georgia, 'Times New Roman', Times, serif; color: #333333;\">Синагога <span style=\"font-family: Courier New; font-size: 70px; font-weight: bold;\"> 'Бейт Менахем - ХАБАД Любавич'</span> Марьина Роща</span></div></td>
     </tr>
       <tr>
        <td style='width:25%; text-align: center;'><span  style='font-family: Arial; font-size: 45px; color: navy; font-weight: bold;'>" . $jewishDayCT . " " . $heMonth[$jewishMonthCTName] . " " . $jewishYearCT. "</span></td>
        <td style='width:50%; text-align: center;'>
        <div class=\"parsha\">
    <span style=\"font-size: 30px;
        font-family: Georgia, 'Times New Roman', Times, serif; color: #333333;\">Глава Торы</span><br>
    <span style=\"font-size: 70px; font-family: Arial; color: #333333; font-weight: bold;\">
    " . $torasections;
    //if ($jewishDateSbDay>22)
    //echo "<br> Шаббат меворхим";
    echo "</span>
     </div>
     </td>
        <td style='width:25%; text-align: center;'><span  style='font-family: Arial; font-size: 45px; color: navy; font-weight: bold;'>" . $gregorianDayCT . " " . $gregorianMonthNames[$gregorianMonthCT - 1] . " " . $gregorianYearCT . "</td>
       </tr>
      </table>
    </div>
       <div id='menu' style='text-align: center;'>
       
       <table align=\"center\" cellspacing=0 cellpadding=3 width='100%'>
    
      
    
      <tr>
      
     <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Шахарит &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspשחרית</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">7:30
     </span> </div></td>
     </tr>  
       <tr>
     <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Шахарит &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspשחרית</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">8:30
     </span> </div></td>
     </tr>  
       <tr>
     <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Шахарит &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspשחרית</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">9:15
     </span> </div></td>
     </tr>
       <tr>
     <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Шахарит &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspשחרית</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">10:00
     </span> </div></td>
     </tr>
        <tr>
     <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Шахарит &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspשחרית</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">11:00
     </span> </div></td>
     </tr>
     
        <tr>
     <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Минха &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp&nbspמנחה‏‎</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">";
       if ($sunrise != "" && $sunset != "") {
        $myTime = GetProportionalHours(6.5, $sunrise, $sunset);
        echo FormatTime($myTime);
      } else {
        echo "---";
      }
     echo" </span> </div></td></tr>";
     echo "
         <tr>
     <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Минха &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp &nbsp&nbsp&nbsp&nbsp&nbsp&nbspמנחה‏‎</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">".FormatTime(SubtractMinutes($sunset, 20))."
     </span> </div></td>
     </tr>
     
         <tr>
     <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Маарив &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp מעריב</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">";
       $myTime = GetSunsetDegreesBelowHorizon($month, $day, $year, 6, $location[0], $location[1], $location[2], $elevation);

      if ($myTime != "") echo FormatTime($myTime); else echo "---";
     echo "</span> </div></td>
     </tr>
     
          <tr>
     <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Маарив &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp מעריב</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">22:00</span> </div></td>
     </tr>
           <tr>
     <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Урок Талмуда в будний день <br> שיעור גמרא</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">9:30</span> </div></td>
     </tr>
     
     "
     /*
      <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Tachoma; color: #333333;\">Исход субботы</span><br><span style=\"font-size: 45px; font-family: Arial; color: #333333; font-weight: bold;\">
     " .$dateSbDay. " " . $gregorianMonthNames[$dateSbMonth - 1] . "<br> " . $timeStringSb . "

      </span> </div></td>
      </tr>


      <tr>
      <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Arial; color: #333333; font-weight: bold;\">
    " . $proba /*. "

     </span> </div></td>
      </tr>
      <tr>

      <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Arial; color: #333333; font-weight: bold;\">
    <span style=\"font-size: 30px;
        font-family: Arial; color: #333333;\">Урок Торы для мужчин <br> среда 20:00 </span>


     </span> </div></td>
      </tr>
      <tr>

      <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Arial; color: #333333; font-weight: bold;\">
    <span style=\"font-size: 30px;
        font-family: Arial; color: #333333;\">Урок Торы для женщин <br> воскресенье 12:00 </span>


     </span> </div></td>
      </tr>
       <tr>

      <td ><div class=\"white\"><span style=\"font-size: 30px; font-family: Arial; color: #333333; font-weight: bold;\">
    <span style=\"font-size: 30px;
        font-family: Arial; color: #333333;\">Урок Иврита <br> воскресенье 14:00 </span>


     </span> </div></td>
      </tr>"*/;

     echo  "</table></div>";
       echo "<div id='sidebar'>";
      //<Отладочка>
    //echo "Я месяц и я тут!!!!!".$jewishMonthCTName."Я закончился( <br>Я ещё какой-то месяц и я начался".$jewishMonthCT."я тоже закончился((". $jewishYearCT;
    //</Отладочка>
    if ($location != "") {
      $caption = "Zmanim for $day $monthStr $year, $locName";
      $elevation = $location[3];
      // TODO Эта штука печатает и времена, что не кул
      CalculateZmanimForDay($month, $day, $year, $location[0], $location[1], $location[2], $elevation, $caption);
    }
       echo "</div>
       <div id='content' align=center>
       
      <canvas id='clock' width='550' height='550' >Извините, ваш браузер не поддерживает тег canvas</canvas>
    <script type='text/javascript'>
    
    var oldSeconds = 0;
    
    function updateClock() {
     var t = new Date();
     var clockArms = [t.getHours(), t.getMinutes(), t.getSeconds()];
     if (clockArms[2] == oldSeconds) return; //секунды не менялись? выйти
     oldSeconds = clockArms[2];
     
     var c = document.getElementById('clock');
     var ctx = c.getContext('2d');
     //очистить канву:
     ctx.fillStyle = 'rgba(0, 0, 0, 0)';
     ctx.fillRect(0, 0, c.width, c.height);
     //нарисовать контур часов:
     
     var x = Math.round(c.width/2);
     var y = Math.round(c.height/2);
     var r = Math.round(Math.min(x,y))-4;
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
     
     
     var delta = Math.max(8,Math.round(r/10)); //для размера шрифта и отсечек
     ctx.font = 'bold '+delta+'pt sans-serif';
     var u=Math.PI/2;
     var r1=r-delta;
     for (var i=1; i<=12; i++) {
      ctx.beginPath();
      var x1 = x+Math.round(r1*Math.cos(u)), //так можно узнать позиции делений циферблата
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
     
    function initClock() {
     window.setInterval(updateClock, 333); //интервал обновления - треть секунды
    }
    
    onload = initClock;
    
    </script>
    <noscript>Извините, для работы приложения нужен включённый Javascript</noscript><br><br>
    
    <marquee direction='left' bgcolor='' width='550px' ><span style=\"font-size: 30px;
        font-family: Georgia, 'Times New Roman', Times, serif; color: #333333;\">МОРИД АТАЛЬ и ВЕТЕН БРАХА</span></marquee>
    </div>
    <div id='footer'>
    
    
    <div style=\"font-family: Courier New; font-size: 36px; font-weight: bold; color: blue; text-align: center; border-style: groove; border-color: black; margin-left: 30%; margin-right: 30%; background-color: white;\" >В память р. Исроэль Рэфоэля сына Арье Зеева</div>
    </div>
       
    <div style=\"font-family: Courier New; font-size: 20px; color: black; text-align: right;\" ><br> © Программа Jzmanim разработана Еврейским университетом и Бейт Хабад Сокольники</div>
    </div>";




    ?>





</body>
</html>