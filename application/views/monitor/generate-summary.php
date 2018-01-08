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
                    for ($i=strtotime($date_from); $i<=strtotime($date_to); $i+=86400) {
                    $inc++;

                    $timed_in = ($this->Monitor_New->get_timed_in($row->stud_no, date("Y-m-d", $i)) != 0) ? $this->Monitor_New->get_timed_in($row->stud_no, date("Y-m-d", $i)) : '';
                    $timed_out = ($this->Monitor_New->get_timed_out($row->stud_no,date("Y-m-d", $i)) != 0) ? $this->Monitor_New->get_timed_out($row->stud_no,date("Y-m-d", $i)) : '';
                    $timed_late = ($this->Monitor_New->get_timed_late($row->stud_no,date("Y-m-d", $i)) != '00:00:00') ? $this->Monitor_New->get_timed_late($row->stud_no, date("Y-m-d", $i)) : '';
            ?>  

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
                                <?php echo $in = $timed_in; ?>
                            </strong>

                        </h5>
                    </td>
                    <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                        <h5>
                            <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                               <?php echo $out = $timed_out; ?>
                            </strong>
                        </h5>
                    </td>

                    <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                        <h5 class="c-red">
                            <strong>
                                <?php 
                                    $a = new DateTime('00:00:00');
                                    $b = new DateTime($timed_late);
                                    $c = $a->diff($b);
                                    $minutes = 0;
                                    if($timed_late != ''){
                                        $minutes = $c->days * 24;
                                        $minutes += $c->h * 60;
                                        $minutes += $c->i;
                                        $totallate += $minutes;
                                    } else {
                                        $totallate += $minutes;
                                    }
                                    $hour = $c->format('%H');
                                    $min = $c->format('%i');

                                    if($timed_late != '')
                                    echo $hour.':'.(($min > 9) ? $min : '0'.$min);
                                    else 
                                    echo '';
                                ?>
                            </strong>
                        </h5>
                    </td>
                    <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                        <h5>
                            <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                            <?php 
                                $a = new DateTime($in);
                                $b = new DateTime($out);
                                $c = $a->diff($b);
                                $minutes = 0;
                                if($in > 0 && $out > 0){
                                    $minutes = $c->days * 24;
                                    $minutes += $c->h * 60;
                                    $minutes += $c->i;
                                    $totaltime += $minutes;
                                } else {
                                    $totaltime += $minutes;
                                }
                                $hour = $c->format('%H');
                                $min = $c->format('%i');

                                if($in > 0 && $out > 0)
                                echo $hour.':'.(($min > 9) ? $min : '0'.$min);
                                else 
                                echo '';
                            ?>
                            </strong>
                        </h5>
                    </td>
                </tr>

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
                            // echo date('H:i', mktime(0, $totallate));
                        ?>
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
                            // echo date('H:i', mktime(0, $totaltime));
                        ?>
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