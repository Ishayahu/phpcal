<?php

function getJewishNextDate($jewishMonth, $jewishDay, $jewishYear) {
  
$carrent_time = strftime("%Y-%m-%d %T");
$gregorianMonthCT = substr($carrent_time,5,2);
$gregorianDayCT = substr($carrent_time,8,2);
$gregorianYearCT = substr($carrent_time,0,4);

$jdNumberCT = gregoriantojd($gregorianMonthCT,$gregorianDayCT,$gregorianYearCT);
$jewishDateCT = jdtojewish($jdNumberCT);
list($jewishMonthCT, $jewishDayCT, $jewishYearCT) = explode('/', $jewishDateCT);

// Ближайшая еврейская дата дня рождения.
if($jewishMonthCT < $jewishMonth || ($jewishMonthCT == $jewishMonth && $jewishDay > $jewishDayCT))
{
$jewishYearF = $jewishYearCT;
}
elseif($jewishMonthCT == $jewishMonth && $jewishDay == $jewishDayCT)
{
$jewishYearF = $jewishYearCT;
}
elseif($jewishMonthCT > $jewishMonth || ($jewishMonthCT == $jewishMonth && $jewishDay < $jewishDayCT))
{
$jewishYearF = $jewishYearCT+1;
}

$jdNumberF = jewishtojd($jewishMonth, $jewishDay, $jewishYearF);
$gregorianDateF = jdtogregorian($jdNumberF);
list($gregorianMonthF, $gregorianDayF, $gregorianYearF) = explode('/', $gregorianDateF);
// перевод даты в unix время
$time_unix = mktime(0,0,0,$gregorianMonthF,$gregorianDayF,$gregorianYearF);
// формула расчёта : сколько осталось дней
$day_restF = round((($time_unix - time())/86400),0);
if($day_restF > 0)
$restF = $day_restF;
else
$restF = 0;
$yaersAfter = $jewishYearF - $jewishYear;
$dateF = array($gregorianMonthF, $gregorianDayF, $gregorianYearF, $restF, $yaersAfter, $jewishYearF);
return $dateF;
}

function getNearHolliday($motday, $tagtoday, $jahrtoday)
{
$juldToday = gregoriantojd($motday, $tagtoday, $jahrtoday);
	$hebrewtoday = jdtojewish($juldToday);
	$hebrewStart = explode("/", $hebrewtoday);

$mdays = cal_days_in_month(CAL_JEWISH, $hebrewStart[0], $hebrewStart[2]);
if($hebrewStart[0] == 3 && $mdays == 29)
    $chanukaTevet = 3;
 else  
    $chanukaTevet = 2;


for ($dn = $hebrewStart[1]; $dn <= 30; $dn++) {

$jdDaten = jewishtojd($hebrewStart[0], $dn, $hebrewStart[2]);
$DateHn = getdate(jdtounix($jdDaten));
$wochentagn = $DateHn["wday"];



	    if (

                (($dn < 3 || ($dn ==3 && $wochentagn != 6) || ($dn == 4 && $wochentagn == 0) || $dn == 10 || ($dn > 14 && $dn < 24)) && $hebrewStart[0] == 1)
                || 
                (($dn == 19 || $dn ==20 || $dn > 24) && $hebrewStart[0] == 3)
                ||
                (($dn <= $chanukaTevet || $dn == 10) && $hebrewStart[0] == 4)
                ||
                (($dn == 10 || $dn == 15) && $hebrewStart[0] == 5)
                ||
                (($dn == 14 || $dn == 15) && $hebrewStart[0] == 6 && !isJewishLeapYear($hebrewStart[2]))
                ||
                (($dn == 14 || $dn == 15) && $hebrewStart[0] == 7 && isJewishLeapYear($hebrewStart[2]))
                ||
                (($dn == 11 || ($dn > 13 && $dn < 23)) && $hebrewStart[0] == 8)
                ||
                (($dn == 14 ||$dn == 18) && $hebrewStart[0] == 9)
                ||
                (($dn == 6 || $dn == 7) && $hebrewStart[0] == 10)
                ||
                (($dn == 3 ||$dn == 12 || $dn == 13 || ($dn == 17 && $wochentagn != 6)  ||  ($dn ==18 && $wochentagn == 0)) && $hebrewStart[0] == 11)
                ||
                ((($dn == 9 && $wochentagn != 6)  ||  ($dn == 10 && $wochentagn ==0) || $dn == 15) && $hebrewStart[0] == 12)
                ||
                ($dn == 18 && $hebrewStart[0] == 13)


) {
                 $YomTov = $dn;
                    break;                     
		}
               
           }

      
      if(isSet($YomTov))
  {
      $MonthNearHolliday = $hebrewStart[0];
      $DayNearHolliday = $dn;
      $YearNearHolliday = $hebrewStart[2];
   
   }
 else
   {
    for ($m = $hebrewStart[0]+1; $m <= 26; $m++) {
if($m > 13) {
$hebrewYear = $hebrewStart[2] + 1;
$m = $m - 13;
}
else {
$hebrewYear = $hebrewStart[2]; 
}


$mdays = cal_days_in_month(CAL_JEWISH, $m, $hebrewYear);
if($m == 3 && $mdays == 29)
    $chanukaTevet = 3;
 else  
    $chanukaTevet = 2;

for ($d = 1; $d <= 30; $d++) {

$jdDate = jewishtojd($m, $d, $hebrewYear);
$DateH = getdate(jdtounix($jdDate));
$wochentag = $DateH["wday"];



	    if (

                (($d < 3 || ($d ==3 && $wochentag != 6) || ($d == 4 && $wochentag == 0) || $d == 10 || ($d > 14 && $d < 24)) && $m == 1)
                || 
                (($d == 19 || $d ==20 || $d > 24) && $m == 3)
                ||
                (($d <= $chanukaTevet || $d == 10) && $m == 4)
                ||
                (($d == 10 || $d == 15) && $m == 5)
                ||
                (($d == 14 || $d == 15) && $m == 6 && !isJewishLeapYear($hebrewYear))
                ||
                (($d == 14 || $d == 15) && $m == 7 && isJewishLeapYear($hebrewYear))
                ||
                (($d == 11 || ($d > 13 && $d < 23)) && $m == 8)
                ||
                (($d == 14 ||$d == 18) && $m == 9)
                ||
                (($d == 6 || $d == 7) && $m == 10)
                ||
                (($d == 3 ||$d == 12 || $d == 13 || ($d == 17 && $wochentag != 6)  ||  ($d ==18 && $wochentag == 0)) && $m == 11)
                ||
                ((($d == 9 && $wochentag != 6)  ||  ($d == 10 && $wochentag ==0) || $d == 15) && $m == 12)
                ||
                ($d == 18 && $m == 13)


) {
                       $MonthNearHolliday = $m;
                       $DayNearHolliday = $d;
                       $YearNearHolliday = $hebrewYear;
                    break 2;                     
		}
               
           }
     }
   }
return array ($MonthNearHolliday, $DayNearHolliday, $YearNearHolliday);	 
}



function getJewishNextHolliday($gMonth, $gDay, $gYear) {

$NameHolliday = getNearHolliday($gMonth, $gDay, $gYear);
$m = $NameHolliday[0];
$d = $NameHolliday[1];
$y = $NameHolliday[2];
$mjn = getJewishMonthName($m, $y);
$df = getJewishNextDate($m, $d, $y);
$lastnamber = mb_substr($df[3], -1);

if ($lastnamber === '1')
          {$wordD = 'день';}
elseif ($lastnamber === '0')
          {$wordD = 'дней';}
else
{
    if ($lastnamber < 5)
          {$wordD = 'дня';} 
   else   {$wordD = 'дней';} 
 }
  
  
//************************************************** Rosh Asana ************************************************************        

       
if(($d ==1 || $d == 2) && $mjn == "Tishri" && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> - <span style = 'color: red'>\"Рош Ашана\"</span> осталось ".$df[3]." ".$wordD.".";
elseif(($d ==1 || $d == 2) && $mjn == "Tishri" && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> - <span style = 'color: red'>\"Рош Ашана\"</span>.";

//*************************************************** Tzom Gedalia ****************************************************************
$jdTzomGedalia = jewishtojd(1, 3, $df[5]);
$datumTzomGedalia = getdate(jdtounix($jdTzomGedalia));
$wochentagTzomGedalia = $datumTzomGedalia["wday"];

if ($wochentagTzomGedalia == 6) {

if($d ==4 && $mjn == "Tishri" && $df[3] != 0)
$nh = "<b>До поста</b> \"Гедалия\" осталось ".$df[3]." ".$wordD.".";
elseif($d ==4 && $mjn == "Tishri" && $df[3] == 0)
$nh = "<b>Сегодня пост</b> \"Гедалия\".";
} 
else {
if($d ==3 && $mjn == "Tishri" && $df[3] != 0)
$nh = "<b>До поста</b> \"Гедалия\" осталось ".$df[3]." ".$wordD.".";
elseif($d ==3 && $mjn == "Tishri" && $df[3] == 0)
$nh = "<b>Сегодня пост</b> \"Гедалия\".";
}

//*************************************************** Yom Kipur ***************************************************************

if($d == 10 && $mjn == "Tishri" && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> - <span style = 'color: red'>\"Йом Кипур\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 10 && $mjn == "Tishri" && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> - <span style = 'color: red'>\"Йом Кипур\"</span>.";

//*************************************************** Sukkot ***************************************************************

if(($d > 14 && $d < 24) && $mjn == "Tishri" && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> - <span style = 'color: red'>\"Суккот\"</span> осталось ".$df[3]." ".$wordD.".";
elseif(($d > 14 && $d < 24) && $mjn == "Tishri" && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> - <span style = 'color: red'>\"Суккот\".</span>";


//*************************************************** 19 Kislev ***************************************************************

if(($d == 19 || $d == 20) && $mjn == "Kislev" && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> - <span style = 'color: red'>\"Новый год хасидизма\"</span> осталось ".$df[3]." ".$wordD.".";
elseif(($d == 19 || $d == 20) && $mjn == "Kislev" && $df[3] == 0)
$nh = "<b>Сегодня</b> <span style = 'color: red'>\"Новый год хасидизма\"</span>.";

//*************************************************** Chanuka ***************************************************************

$kl = 3;//cal_days_in_month(CAL_JEWISH, 3, $df[3]);
if($kl == 29)
$cht = 4;
else
$cht = 3;

if(($d > 24 && $d < $kl) && $mjn == "Kislev" && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> - <span style = 'color: red'>\"Ханука\"</span> осталось ".$df[3]." ".$wordD.".";
elseif(($d > 24 && $d < $kl) && $mjn == "Kislev" && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> - <span style = 'color: red'>\"Ханука\"</span>.";


if($d < $cht && $mjn == "Tevet" && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> - <span style = 'color: red'>\"Ханука\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d < $cht && $mjn == "Tevet" && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> - <span style = 'color: red'>\"Ханука\"</span>.";

//*************************************************** 10 Teveta ***************************************************************

if($d == 10 && $mjn == "Tevet" && $df[3] != 0)
$nh = "<b>До поста</b> \"10 Тевета\" осталось ".$df[3]." ".$wordD.".";
elseif($d == 10 && $mjn == "Tevet" && $df[3] == 0)
$nh = "<b>Сегодня пост</b> \"10 Тевета\".";

//*************************************************** 10 Shvata ***************************************************************

if($d == 10 && $mjn == "Shevat" && $df[3] != 0)
$nh = "До <span style = 'color: red'>\"10 Швата\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 10 && $mjn == "Shevat" && $df[3] == 0)
$nh = "<b>Сегодня</b> <span style = 'color: red'>\"10 Швата\"</span>.";
//*************************************************** 15 Shvata ***************************************************************

if($d == 15 && $mjn == "Shevat" && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> - <span style = 'color: red'>\"Нового года деревьев\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 15 && $mjn == "Shevat" && $df[3] == 0)
$nh = "<Сегодня праздник</b> <span style = 'color: red'>\"Новый год деревьев\"</span>.";

//*************************************************** Purim ***************************************************************


if($d == 14 && ($mjn == "Adar" || $mjn == "AdarII") && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> - <span style = 'color: red'>\"Пурим\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 14 && ($mjn == "Adar" || $mjn == "AdarII") && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> <span style = 'color: red'>\"Пурим\"</span>.";

//*************************************************** Shushan Purim ***************************************************************


if($d == 15 && ($mjn == "Adar" || $mjn == "AdarII") && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> - <span style = 'color: red'>\"Шушан Пурим\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 15 && ($mjn == "Adar" || $mjn == "AdarII") && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> <span style = 'color: red'>\"Шушан Пурим\"</span>.";


//*************************************************** 11 Nisana ***************************************************************

if($d == 11 && $mjn == "Nisan" && $df[3] != 0)
$nh = "До <span style = 'color: red'>\"Дня рождения Ребе\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 11 && $mjn == "Nisan" && $df[3] == 0)
$nh = "<b>Сегодня</b> <span style = 'color: red'>\"День рождения Ребе\"</span>.";
//*************************************************** Pesach ***************************************************************

if(($d > 13 && $d < 23) && $mjn == "Nisan" && $df[3] != 0)
$nh = "<b>До ближайшего пораздника</b> - <span style = 'color: red'>\"Песах\"</span> осталось ".$df[3]." ".$wordD.".";
elseif(($d > 13 && $d < 23) && $mjn == "Nisan" && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> <span style = 'color: red'>\"Песах\"</span>.";


//*************************************************** Pesach sheni ***************************************************************


if($d == 14 && $mjn == "Iyyar" && $df[3] != 0)
$nh = "<b>До праздника <span style = 'color: red'>\"Песах шени\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 14 && $mjn == "Iyyar" && $df[3] == 0)
$nh = "<b>Сегодня праздник <span style = 'color: red'>\"Песах шени\"</span>.";

//*************************************************** Lag Ba-Omer ***************************************************************

if($d == 18 && $mjn == "Iyyar" && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> <span style = 'color: red'>\"Лаг Ба-Омер\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 18 && $mjn == "Iyyar" && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> <span style = 'color: red'>\"Лаг Ба-Омер\"</span>.";

//*************************************************** Shavuot ***************************************************************

if(($d == 6 || $d == 7) && $mjn == "Sivan" && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> <span style = 'color: red'>\"Шавуот\"</span> осталось ".$df[3]." ".$wordD.".";
elseif(($d == 6 || $d == 7) && $mjn == "Sivan" && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> <span style = 'color: red'>\"Шавуот\"</span>.";

//***************************************************  3 Tammuz ***************************************************************

if($d == 3 && $mjn == "Tammuz" && $df[3] != 0)
$nh = "До <span style = 'color: red'>\"Дня Ребе\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 3 && $mjn == "Tammuz" && $df[3] == 0)
$nh = "<b>Сегодня</b> <span style = 'color: red'>\"День Ребе\"</span>.";
//*************************************************** 12 - 13 Tammuz **********************************************************

if(($d == 13 || $d == 12) && $mjn == "Tammuz" && $df[3] != 0)
$nh = "До <span style = 'color: red'>\"Дня Освобождения\"</span> осталось ".$df[3]." ".$wordD.".";
elseif(($d == 13 || $d == 12) && $mjn == "Tammuz" && $df[3] == 0)
$nh = "<b>Сегодня</b> <span style = 'color: red'>\"День Освобождения\"</span>.";

//*************************************************** 17 Tammuz **********************************************************

$jdtzomtammus = jewishtojd(11, 17, $df[5]);
$tzomtammus = getdate(jdtounix($jdtzomtammus));
$wochentagTzomTammus = $tzomtammus["wday"];

if ($wochentagTzomTammus == 6) {

if($d ==18 && $mjn == "Tammuz" && $df[3] != 0)
$nh = "<b>До поста</b> \"17 Тамуза\" осталось ".$df[3]." ".$wordD.".";
elseif($d ==18 && $mjn == "Tammuz" && $df[3] == 0)
$nh = "<b>Сегодня пост</b> \"17 Тамуза\".";
} 
else {
if($d ==17 && $mjn == "Tammuz" && $df[3] != 0)
$nh = "<b>До поста</b> \"17 Тамуза\" осталось ".$df[3]." ".$wordD.".";
elseif($d ==17 && $mjn == "Tammuz" && $df[3] == 0)
$nh = "<b>Сегодня пост</b> \"17 Тамуза\".";
}


//*************************************************** 9 Ava **********************************************************


$jdtischabeaw = jewishtojd(12, 9, $df[5]);
$tischabeaw = getdate(jdtounix($jdtischabeaw));
$wochentagtischabeaw = $tischabeaw["wday"];

if ($wochentagtischabeaw == 6) {

if($d ==10 && $mjn == "Av" && $df[3] != 0)
$nh = "<b>До поста</b> \"9 Ава\" осталось ".$df[3]." ".$wordD.".";
elseif($d ==10 && $mjn == "Av" && $df[3] == 0)
$nh = "<b>Сегодня пост</b> \"9 Ава\".";
} 
else {
if($d ==9 && $mjn == "Av" && $df[3] != 0)
$nh = "<b>До поста</b> \"9 Ава\" осталось ".$df[3]." ".$wordD.".";
elseif($d ==9 && $mjn == "Av" && $df[3] == 0)
$nh = "<b>Сегодня пост</b> \"9 Ава\".";
}

//*************************************************** 15 Ava **********************************************************

if($d == 15 && $mjn == "Av" && $df[3] != 0)
$nh = "<b>До ближайшего праздника</b> <span style = 'color: red'>\"15 Ава\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 15 && $mjn == "Av" && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> <span style = 'color: red'>\"15 Ава\"</span>.";


//*************************************************** 18 Elula **********************************************************

if($d == 18 && $mjn == "Elul" && $df[3] != 0)
$nh = "<b>До праздника</b> <span style = 'color: red'>\"Хай Элул\"</span> осталось ".$df[3]." ".$wordD.".";
elseif($d == 18 && $mjn == "Elul" && $df[3] == 0)
$nh = "<b>Сегодня праздник</b> <span style = 'color: red'>\"Хай Элул\"</span>.";
return $nh;


}






//(($d < 4 || $d == 10 || ($d > 14 && $d < 24)) && $m == 1 && $m != $hebrewStart[0]) ||
//               (($dn < 4 || $dn == 10 || ($dn > 14 && $dn < 24)) && $m == 1 && $m == $hebrewStart[0])
//                || 
//                (($d == 19 || $d ==20 || $d > 24)  && $m == 3 && $m != $hebrewStart[0]) ||
//                (($dn == 19 || $dn ==20 || $dn > 24) && $m == 3 && $m == $hebrewStart[0])
//                ||
//                (($d <= $chanukaTevet || $d == 10) && $m == 4 && $m != $hebrewStart[0]) ||
//                (($dn <= $chanukaTevet || $dn == 10) && $m == 4)
//                ||
//                (($d == 10 || $d == 15) && $m == 5 && $m != $hebrewStart[0]) ||
//                (($dn == 10 || $dn == 15) && $m == 5)
//              ||
//                (($d == 13 || $d == 14 || $d == 15) && $m == 6 && $m != $hebrewStart[0]) ||
//                (($dn == 13 ||$dn == 14 || $dn == 15) && $m == 6)
//                ||
//                (($d == 14 || $d == 15) && $m == 7 && $m != $hebrewStart[0]) ||
//                (($dn == 14 || $dn == 15) && $m == 7)
//                ||
//                (($d == 11 || ($d > 13 && $d < 23)) && $m == 8 && $m != $hebrewStart[0]) ||
//                (($dn == 11 || ($dn > 13 && $dn < 23)) && $m == 8)
//                ||
//                (($d == 14 || $d == 18) && $m == 9 && $m != $hebrewStart[0]) ||
//                (($dn == 14 ||$dn == 18) && $m == 9)
//                ||
//                (($d == 6 || $d == 7) && $m == 10 && $m != $hebrewStart[0]) ||
//                (($dn == 6 ||$dn == 7) && $m == 10)
//                ||
//               (($d == 3 || $d == 12 || $d == 13 || $d == 17) && $m == 11 && $m != $hebrewStart[0]) ||
//                (($dn == 3 ||$dn == 12 ||$dn == 13 || $dn == 17) && $m == 11)
//                ||
//                (($d == 9 || $d == 15) && $m == 12 && $m != $hebrewStart[0]) ||
//                (($dn == 9 ||$dn == 15) && $m == 12)
//                ||
//               ($d == 18 && $m == 13 && $m != $hebrewStart[0]) ||
//                ($dn == 18 && $m == 13)

?>