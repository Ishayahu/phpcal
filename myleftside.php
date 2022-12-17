<?php
/**
* Created by PhpStorm.
* User: ishay
* Date: 19.01.2022
* Time: 21:33
*/

?>

<table align="center" cellspacing="0" cellpadding="3" width="100%">
    <tbody>
        <tr>
            <td colspan="2">
                <div id="alot" class="white">
                    <div class="leftside-title">
                        <span>Рассвет</span><span>עלות השחר</span>
                    </div>
                    <div class="leftside-time">
                        <?php
                        if($complexZmanimCalendar->alosHashachar){
                            echo $complexZmanimCalendar->alosHashachar->format("H:i");
                        }else{
                            echo "--:--";
                        }
                         ?>
                    </div>
                </div>
                <div class="right-between-row-spacer"></div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="netz" class="white">
                    <div class="leftside-title">
                        <span>Восход солнца</span><span>נץ החמה</span>
                    </div>
                    <div class="leftside-time">
                        <?php echo $complexZmanimCalendar->sunrise->format("H:i") ?>
                    </div>
                </div>
                <div class="right-between-row-spacer"></div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="shma" class="white">
                    <div class="leftside-title">
                        <span>Конец времени Шма</span><span>סוף זמן קריאת שמע</span>
                    </div>
                    <div class="leftside-time">
                        <?php echo $complexZmanimCalendar->sofZmanShmaBaalHatanya->format("H:i") ?>
                    </div>
                </div>
                <div class="right-between-row-spacer"></div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="hatzot" class="white">
                    <div class="leftside-title">
                        <span>Полдень</span><span>חצות היום</span>
                    </div>
                    <div class="leftside-time">
                        <?php echo $complexZmanimCalendar->chatzos->format("H:i") ?>
                        <?php //echo $complexZmanimCalendar->minchaGedolaBaalHatanya->format("H:i") ?>
                        <?php //echo $complexZmanimCalendar->minchaGedolaBaalHatanyaGreaterThan30->format("H:i") ?>
                    </div>
                </div>
                <div class="right-between-row-spacer"></div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="shkia" class="white">
                    <div class="leftside-title">
                        <span>Заход солнца</span><span>שקיעת החמה</span>
                    </div>
                    <div class="leftside-time">
                        <?php echo $complexZmanimCalendar->sunset->format("H:i") ?>
                    </div>
                </div>
                <div class="right-between-row-spacer"></div>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <div id="zeit" class="white">
                    <div class="leftside-title">
                        <span>Выход звезд</span><span>צאת הכוכבים</span>
                    </div>
                    <div class="leftside-time">
                        <?php echo $complexZmanimCalendar->tzaisBaalHatanya->format("H:i") ?>
                    </div>
                </div>
                <div class="right-between-row-spacer"></div>
            </td>
        </tr>
        <tr>
            <td class='h20'>
            </td>
        </tr>
        <!-- Следующие блоки зависят от наличия праздника -->
        <?php
            if($erevYomTov){
        ?>
                <tr>
                    <td height="100px">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="white">
                            <div class="leftside-title">
                                <span>Зажигание свечей</span><span>הדלקת נרות</span>
                            </div>
                            <div class="leftside-time">
                                <?php echo $firstDayCandleLigtning->candleLighting->format("H:i") ?>
                            </div>
                        </div>
                        <div class="right-between-row-spacer"></div>
                    </td>
                </tr>
                <tr>
                    <td height="145px">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div class="white">
                            <div class="leftside-title">
                                <span>Шахарит в Йом Тов</span><span>שחרית יום טוב</span>
                            </div>
                            <div class="leftside-time">10:00</div>
                        </div>
                        <div class="right-between-row-spacer"></div>
                    </td>
                </tr>

        <?php
            }elseif ($firstDayOfYomTov){
        ?>
                <tr>
                    <td height="100px">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="white">
                            <div class="leftside-title">
                                <span>Зажигание свечей</span><span>הדלקת נרות</span>
                            </div>
                            <div class="leftside-time">
                                <?php
                                // Округляем время выхода шабата с учётом секунд
                                $shabatHours = intval($secondDayCandleLigtning->tzais->format("H"));
                                $shabatMinutes = intval($secondDayCandleLigtning->tzais->format("i"));
                                $shabatSeconds = intval($secondDayCandleLigtning->tzais->format("s"));
                                if($shabatSeconds>0){
                                    $shabatMinutes++;
                                }
                                echo FormatTime([$shabatHours, $shabatMinutes]);
                                //                        echo $shabatComplexZmanimCalendar->tzais->format("H:i")
                                ?>
                            </div>
                        </div>
                        <div class="right-between-row-spacer"></div>
                    </td>
                </tr>
                <tr>
                    <td height="145px">
                    </td>
                </tr>

                <tr>
                    <td colspan="2">
                        <div class="white">
                            <div class="leftside-title">
                                <span>Шахарит в Йом Тов</span><span>שחרית יום טוב</span>
                            </div>
                            <div class="leftside-time">10:00</div>
                        </div>
                        <div class="right-between-row-spacer"></div>
                    </td>
                </tr>
        <?php
            }elseif ($mozeiYomTov){
        ?>
                <tr>
                    <td height="350px">
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="white">
                            <div class="leftside-title">
                                <span>Исход Йом Това</span><span>מוצאי יום טוב</span>
                            </div>
                            <div class="leftside-time">
                                <?php
                                // Округляем время выхода шабата с учётом секунд
                                $shabatHours = intval($mozeiYomTovTime->tzais->format("H"));
                                $shabatMinutes = intval($mozeiYomTovTime->tzais->format("i"));
                                $shabatSeconds = intval($mozeiYomTovTime->tzais->format("s"));
                                if($shabatSeconds>0){
                                    $shabatMinutes++;
                                }
                                echo FormatTime([$shabatHours, $shabatMinutes]);
                                //                        echo $shabatComplexZmanimCalendar->tzais->format("H:i")
                                ?>
                            </div>
                        </div>
                        <div class="right-between-row-spacer"></div>
                    </td>
                </tr>
                <tr>
                    <td height="145px">
                    </td>
                </tr>

        <?php
            }else{
        ?>
                <tr>
                    <td colspan="2">
                        <div class="white">
                            <div class="leftside-title">
                                <span>Зажигание свечей</span><span>הדלקת נרות</span>
                            </div>
                            <div class="leftside-time">
                                <?php echo $fridayComplexZmanimCalendar->candleLighting->format("H:i") ?>
                            </div>
                        </div>
                        <div class="right-between-row-spacer"></div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="white">
                            <div class="leftside-title">
                                <span>Выход Шаббата</span><span>יציאת שבת</span>
                            </div>
                            <div class="leftside-time">
                                <?php
                                // Округляем время выхода шабата с учётом секунд
                                $shabatHours = intval($shabatComplexZmanimCalendar->tzais->format("H"));
                                $shabatMinutes = intval($shabatComplexZmanimCalendar->tzais->format("i"));
                                $shabatSeconds = intval($shabatComplexZmanimCalendar->tzais->format("s"));
                                if($shabatSeconds>0){
                                    $shabatMinutes++;
                                }
                                echo FormatTime([$shabatHours, $shabatMinutes]);
                                //                        echo $shabatComplexZmanimCalendar->tzais->format("H:i")
                                ?>
                            </div>
                        </div>
                        <div class="right-between-row-spacer"></div>
                    </td>
                </tr>

            <tr>
                <td class='h20'>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="white">
                        <div class="leftside-title">
                            <span>Кабалат Шаббат</span><span>קבלת שבת</span>
                        </div>
                        <div class="leftside-time">
                                <?php
                                $h = $fridayComplexZmanimCalendar->candleLighting->format("H");
                                $h = intval($h)+1;
                                $i = $fridayComplexZmanimCalendar->candleLighting->format("i");
                                $i = intval($i);
                                echo FormatTime([$h, $i]);
                                ?>
                        </div>
                    </div>
                    <div class="right-between-row-spacer"></div>
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <div class="white">
                        <div class="leftside-title">
                            <span>Шахарит в Шаббат</span><span>שחרית בשבת</span>
                        </div>
                        <div class="leftside-time">10:00</div>
                    </div>
                    <div class="right-between-row-spacer"></div>
                </td>
            </tr>
                <?php
            }
        ?>
    </tbody>
</table>