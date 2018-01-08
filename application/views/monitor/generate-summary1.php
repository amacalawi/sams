<table class="table i-table m-t-25 m-b-25">
    <tbody>
        <thead>
            <tr class="tborder">
                <td class="bgm-green-1 text-center dtr-level" colspan="6">
                    <?php if(urldecode($this->input->get('category_level')) != 'null'): ?>
                        <?php if (urldecode($this->input->get('category')) == 'Contact'): ?>
                          (<?php echo $this->Monitor_New->get_levels(urldecode($this->input->get('category_level'))); ?>)
                        <?php else : ?>
                          (<?php echo $this->Monitor_New->get_filter(urldecode($this->input->get('category')), urldecode($this->input->get('category_level'))); ?>)
                        <?php endif; ?>
                    <?php endif; ?>
                </td>
            </tr>
            <?php
                foreach ($results as $row) {

                    $date_from = str_replace('/', '-', urldecode($this->input->get('date_from')));
                    $date_to = str_replace('/', '-', urldecode($this->input->get('date_to')));
                    $time_from = strtotime(urldecode($this->input->get('time_from')));
                    $time_to = strtotime(urldecode($this->input->get('time_to')));
                    $totaltime = 0;
                    $totallate = 0;
            ?>

                <tr class="tborder">
                    <td class="text-center dtr-name" colspan="6">
                        <strong><?php echo ucwords(strtolower($this->Monitor->get_fullname($row->id))); ?></strong>
                    </td>
                </tr>
                <tr class="tborder">
                    <th class="bgm-green-2 c-black">DAY</th>
                    <th class="bgm-green-2 c-black">DATE</th>
                    <th class="bgm-green-2 c-black">TIMED IN </span></th>
                    <th class="bgm-green-2 c-black">TIMED OUT </span></th>
                    <th class="bgm-green-2 c-black">TIME LATE</th>
                    <th class="bgm-green-2 c-black">TIME RENDERED</span></th>
                </tr>

            <?php
                    $inc = 0;
                    for ($i=$date_from; $i<=$date_to; $i+=86400) {
                    $inc++
            ?>

                <?php
                    $has_time_in = $this->Monitor_New->get_dtrlog_in($row->stud_no, date("Y-m-d", $i), date("H:i:s", $time_from), date("H:i:s", $time_to));
                    $has_time_out = $this->Monitor_New->get_dtrlog_out($row->stud_no,date("Y-m-d", $i),date("H:i:s", $time_from),date("H:i:s", $time_to));
                ?>
                <?php if($has_time_in || $has_time_out) { ?>

                <tr class="tborder">
                    <td class="bgm-green-2">
                        <strong><?php echo date("D", $i); ?></strong>
                    </td>
                    <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                        <h5>
                            <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                <?php echo date("Y-m-d", $i); ?>
                            </strong>

                        </h5>
                    </td>

                    <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                        <h5>
                            <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                <?php echo $in = $this->Monitor_New->get_dtrlog_in($row->stud_no, date("Y-m-d", $i), date("H:i:s", $time_from), date("H:i:s", $time_to)); ?>
                            </strong>

                        </h5>
                    </td>
                    <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                        <h5>
                            <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                <?php echo $out = $this->Monitor_New->get_dtrlog_out($row->stud_no,date("Y-m-d", $i),date("H:i:s", $time_from),date("H:i:s", $time_to)); ?>
                            </strong>
                        </h5>
                    </td>

                    <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                        <h5 class="c-red">
                            <strong>
                            <?php
                                if(isset($in) && $in > 0)
                                {
                                    $time_in_hr = Date("H",strtotime($in))* 60 ;
                                    $time_in_min  = Date("i",strtotime($in));
                                    $reg_in_hr = Date("H",strtotime($this->Monitor_New->get_late_in_by_member_id($row->id)))* 60 ;
                                    $reg_in_min  = (Date("i",strtotime($this->Monitor_New->get_late_in_by_member_id($row->id))) - 1);

                                    if($time_in_hr > $reg_in_hr)
                                    {
                                        $minutes = ($time_in_hr + $time_in_min) - ($reg_in_hr + $reg_in_min);
                                        $totallate = $totallate + $minutes;
                                        $hours = 0;

                                        while($minutes>=60){
                                            $minutes -= 60;
                                            $hours++;
                                        }
                                        echo ($hours>=1) ? ($hours>1) ? $hours."hrs" : $hours."hr" : "";
                                        echo " ";
                                        echo ($minutes>=1) ? ($minutes>1) ? $minutes."mins" : $minutes."min" : "0min";

                                    } else if (($time_in_hr == ($reg_in_hr)) && $time_in_min > 0)
                                    {
                                        echo $minutes = ($time_in_min > 1) ? $time_in_min."mins" : $time_in_min."min";
                                        $totallate = $totallate + $time_in_min;
                                        
                                    }

                                }
                            ?>
                            </strong>
                        </h5>
                    </td>
                    <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                        <h5>
                            <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                            <?php
                                    $total_in = ((Date("H",strtotime($in))*60) + Date("i",strtotime($in)));
                                    $total_out = ((Date("H",strtotime($out))*60) + Date("i",strtotime($out)));

                                    $minutes = ($total_out - $total_in);
                                    $totaltime = $totaltime + $minutes;
                                    $hours = 0;

                                    while($minutes>=60){
                                        $minutes -= 60;
                                        $hours++;
                                    }
                                    echo ($hours>=1) ? ($hours>1) ? $hours."hrs" : $hours."hr" : "";
                                    echo " ";
                                    echo ($minutes>=1) ? ($minutes>1) ? $minutes."mins" : $minutes."min" : "0min";

                            ?>
                            </strong>
                        </h5>
                    </td>
                </tr>

                <?php } else { ?>
                    <tr class="tborder">
                        <td class="bgm-green-2">
                            <strong><?php echo date("D", $i); ?></strong>
                        </td>
                        <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                            <h5>
                                <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                    <?php echo date("Y-m-d", $i); ?>
                                </strong>

                            </h5>
                        </td>
                        <td width="20%" class="text-center <?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>"></td>
                        <td width="20%" class="text-center <?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>"></td>
                        <td width="20%" class="text-center <?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                        </td>
                        <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                            <h5>
                                <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                    0mins
                                </strong>
                            </h5>
                        </td>
                    </tr>
                <?php } ?>
            <?php
                }
            ?>
                <tr class="tborder">
                    <td colspan="4" class="text-right">
                        <h5>
                            <strong>
                                TOTAL COMPUTED TIME
                            </strong>
                        </h5>
                    </td>
                    <td>
                        <h5 class="c-red">
                        <strong>
                        <?php
                            $totalhours = 0;
                            while($totallate>=60){
                                $totalhours++;
                                $totallate -= 60;
                            }
                            echo ($totalhours>=1) ? ($totalhours>1) ? $totalhours."hrs" : $totalhours."hr" : "";
                            echo " ";
                            echo ($totallate>=1) ? ($totallate>1) ? $totallate."mins" : $totallate."min" : "0min";
                        ?>
                        </strong>
                        </h5>
                    </td>
                    <td>
                        <h5>
                        <strong>
                        <?php
                            $totalhours = 0;
                            while($totaltime>=60){
                                $totalhours++;
                                $totaltime -= 60;
                            }
                            echo ($totalhours>=1) ? ($totalhours>1) ? $totalhours."hrs" : $totalhours."hr" : "";
                            echo " ";
                            echo ($totaltime>=1) ? ($totaltime>1) ? $totaltime."mins" : $totaltime."min" : "0min";
                        ?>
                        </strong>
                        </h5>
                    </td>
                </tr>
            <?php
                }
            ?>
        </thead>
    </tbody>
</table>