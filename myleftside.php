<?php
/**
* Created by PhpStorm.
* User: ishay
* Date: 19.01.2022
* Time: 21:33
*/

?>


                <div id="alot" class="white">
                    <div class="leftside-title">
                        <span><?php echo $localNames['Dawn'] ?></span>
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

                <div id="netz" class="white">
                    <div class="leftside-title">
                        <span><?php echo $localNames['Sunrise'] ?></span>
                    </div>
                    <div class="leftside-time">
                        <?php echo $complexZmanimCalendar->sunrise->format("H:i") ?>
                    </div>
                </div>

                <div id="shma" class="white">
                    <div class="leftside-title">
                        <span><?php echo $localNames['Latest shema'] ?></span>
                    </div>
                    <div class="leftside-time">
                        <?php echo $complexZmanimCalendar->sofZmanShmaBaalHatanya->format("H:i") ?>
                    </div>
                </div>

                <div id="hatzot" class="white">
                    <div class="leftside-title">
                        <span><?php echo $localNames['Midday'] ?></span>
                    </div>
                    <div class="leftside-time">
                        <?php echo $complexZmanimCalendar->chatzos->format("H:i") ?>
                        <?php //echo $complexZmanimCalendar->minchaGedolaBaalHatanya->format("H:i") ?>
                        <?php //echo $complexZmanimCalendar->minchaGedolaBaalHatanyaGreaterThan30->format("H:i") ?>
                    </div>
                </div>

                <div id="shkia" class="white">
                    <div class="leftside-title">
                        <span><?php echo $localNames['Sunset'] ?></span>
                    </div>
                    <div class="leftside-time">
                        <?php echo $complexZmanimCalendar->sunset->format("H:i") ?>
                    </div>
                </div>

                <div id="zeit" class="white">
                    <div class="leftside-title">
                        <span><?php echo $localNames['Nightfall'] ?></span>
                    </div>
                    <div class="leftside-time">
                        <?php echo $complexZmanimCalendar->tzaisBaalHatanya->format("H:i") ?>
                    </div>
                </div>

        <!-- Следующие блоки зависят от наличия праздника -->
        <?php
            if($erevYomTov){
        ?>
                        <div class="white">
                            <div class="leftside-title">
                                <span><?php echo $localNames['Candle lighting'] ?></span>
                            </div>
                            <div class="leftside-time">
                                <?php echo $firstDayCandleLigtning->candleLighting->format("H:i") ?>
                            </div>
                        </div>

                        <div class="white">
                            <div class="leftside-title">
                                <span><?php echo $localNames['Shacharit yom tov'] ?></span>
                            </div>
                            <div class="leftside-time">10:00</div>
                        </div>

        <?php
            }elseif ($firstDayOfYomTov){
        ?>
                        <div class="white">
                            <div class="leftside-title">
                                <span><?php echo $localNames['Candle lighting'] ?></span>
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

                        <div class="white">
                            <div class="leftside-title">
                                <span><?php echo $localNames['Shacharit yom tov'] ?></span>
                            </div>
                            <div class="leftside-time">10:00</div>
                        </div>
        <?php
            }elseif ($mozeiYomTov){
        ?>
                        <div class="white">
                            <div class="leftside-title">
                                <span><?php echo $localNames['Yom tov ends'] ?></span>
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

        <?php
            }else{
        ?>
                        <div class="white">
                            <div class="leftside-title">
                                <span><?php echo $localNames['Candle lighting'] ?></span>
                            </div>
                            <div class="leftside-time">
                                <?php echo $fridayComplexZmanimCalendar->candleLighting->format("H:i") ?>
                            </div>
                        </div>

                        <div class="white">
                            <div class="leftside-title">
                                <span><?php echo $localNames['Shabbat ends'] ?></span>
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

                    <div class="white">
                        <div class="leftside-title">
                            <span><?php echo $localNames['Kabbalat shabbat'] ?></span>
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

                    <div class="white">
                        <div class="leftside-title">
                            <span><?php echo $localNames['Shacharit shabbat'] ?></span>
                        </div>
                        <div class="leftside-time">10:00</div>
                    </div>
                <?php
            }
        ?>
