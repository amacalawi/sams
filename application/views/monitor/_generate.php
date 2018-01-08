<section id="content" class="<?php echo $this->router->fetch_method(); ?>">

    <div class="container invoice">

        <div class="block-header">
            <h2>
                Monitor
                <small>
                    Print ready simple and sleek invoice template. Please use Google Chrome or any other Webkit browsers for better printing.
                </small>
            </h2>
        </div>

        <div class="card">
            <div class="card-header ch-alt text-center">
                <img class="i-logo" src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
            </div>

            <div class="card-body card-padding p-t-10">
                <div class="row m-b-10">
                    <div class="col-xs-12">
                        <h3 class="text-center text-uppercase">
                            GENERATED TIME REPORT
                            BY <span id="category_val"><?php echo $_GET['category']; ?></span>
                            <span id="category_level_val" style="display:none">
                            <?php echo $_GET['category_level']; ?>
                            </span>
                            <?php if($_GET['category']== "Contact"): ?>

                            <?php else : ?>
                                <?php if($_GET['category_level']!="null"): ?>
                                 (<?php echo $this->Monitor->get_filter($_GET['category'],$_GET['category_level']); ?>)
                                <?php endif; ?>

                            <?php endif; ?>
                        </h3>
                    </div>
                </div>
                <div class="row m-b-15">

                    <div class="col-xs-6">
                        <div class="text-right">
                            <p class="c-gray">Start Date &amp; Time</p>

                            <h4>
                                <?php
                                $str = str_replace('/', '-', $_GET['date_from']);
                                ?>
                                <span id="datefrom_val"><?php echo date("Y-m-d", strtotime($str)); ?></span>
                                 (<span id="timefrom_val"><?php echo $_GET['time_from']; ?></span>)
                            </h4>

                        </div>
                    </div>

                    <div class="col-xs-6">
                        <div class="i-to">
                            <p class="c-gray">End Date &amp; Time</p>
                            <h4 id="dateto_val">
                                <?php
                                $str = str_replace('/', '-', $_GET['date_to']);
                                ?>
                                <span id="dateto_val"><?php echo date("Y-m-d", strtotime($str)); ?></span>
                                 (<span id="timeto_val"><?php echo $_GET['time_to']; ?></span>)
                                <span id="type_val" class="hidden"><?php echo $_GET['type']; ?></span>
                                <span id="typeorder_val" class="hidden"><?php echo $_GET['type_order']; ?></span>
                            </h4>
                        </div>
                    </div>

                </div>

                <div class="clearfix"></div>
                    <?php
                        if(isset($_GET['type']) &&($_GET['type']=="Summary")):
                    ?>
                        <?php if(isset($_GET['category']) && $_GET['category']=="All"){ ?>
                            <?php $this->load->view('monitor/contact'); ?>
                        <?php } else { ?>
                                <table class="table i-table m-t-25 m-b-25">
                                    <tbody>
                                        <thead>
                                            <tr class="tborder">
                                                <td class="bgm-green-1 text-center dtr-level" colspan="6">
                                                    <?php if($_GET['category_level']!="null"): ?>

                                                        <?php if ($_GET['category']=="Contact"): ?>
                                                          (<?php echo $this->Monitor->get_levels($_GET['category_level']); ?>)
                                                        <?php else : ?>
                                                          (<?php echo $this->Monitor->get_filter($_GET['category'],$_GET['category_level']); ?>)
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php
                                                foreach ($results as $row) {

                                                    $date_from = str_replace('/', '-', $_GET['date_from']);
                                                    $date_from = strtotime($date_from);
                                                    $date_to = str_replace('/', '-', $_GET['date_to']);
                                                    $date_to = strtotime($date_to);
                                                    $time_from = strtotime($_GET['time_from']);
                                                    $time_to = strtotime($_GET['time_to']);
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
                                                    $has_time_in = $this->Monitor->get_dtrlog_am_in($row->stud_no, date("Y-m-d", $i), date("H:i:s", $time_from), date("H:i:s", $time_to));
                                                    $has_time_out = $this->Monitor->get_dtrlog_am_out($row->stud_no,date("Y-m-d", $i),date("H:i:s", $time_from),date("H:i:s", $time_to));
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
                                                                <?php echo $in = $this->Monitor->get_dtrlog_am_in($row->stud_no, date("Y-m-d", $i), date("H:i:s", $time_from), date("H:i:s", $time_to)); ?>
                                                            </strong>

                                                        </h5>
                                                    </td>
                                                    <td width="20%" class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                                                        <h5>
                                                            <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                                                <?php echo $out = $this->Monitor->get_dtrlog_am_out($row->stud_no,date("Y-m-d", $i),date("H:i:s", $time_from),date("H:i:s", $time_to)); ?>
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
                                                                    $reg_in_hr = Date("H",strtotime($this->Monitor->get_late_in_by_member_id($row->id)))* 60 ;
                                                                    $reg_in_min  = (Date("i",strtotime($this->Monitor->get_late_in_by_member_id($row->id))) - 1);

                                                                    if($time_in_hr > $reg_in_hr)
                                                                    {
                                                                        $minutes = ($time_in_hr + $time_in_min) - ($reg_in_hr + $reg_in_min);
                                                                        //echo ($mins > 1) ? $mins."mins" : $mins."min";
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
                                                                        //  $hours = 0;
                                                                        // while($minutes>=60){
                                                                        //     $minutes -= 60;
                                                                        //     $hours++;
                                                                        // }
                                                                        // echo ($hours>=1) ? ($hours>1) ? $hours."hrs" : $hours."hr" : "";
                                                                        // echo " ";
                                                                        // echo ($minutes>=1) ? ($minutes>1) ? $minutes."mins" : $minutes."min" : "0min";
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
                                                            <!-- <h5>
                                                                <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                                                    ABSENT
                                                                </strong>
                                                            </h5> -->
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
                        <?php } ?>

                    <?php elseif (isset($_GET['type']) &&($_GET['type']=="Absents_Only")): ?>
                        <?php include __DIR__ . '/generate-absents.php' ?>

                    <?php elseif (isset($_GET['type']) &&($_GET['type']=="Late_Only")): ?>
                        <?php include __DIR__ . '/generate-late.php' ?>

                    <?php else: ?>
                        <table class="table i-table m-t-25 m-b-25">
                            <tbody>
                                <?php
                                    $date_from = str_replace('/', '-', $_GET['date_from']);
                                    $date_from = strtotime($date_from);
                                    $date_to = str_replace('/', '-', $_GET['date_to']);
                                    $date_to = strtotime($date_to);
                                    $type = $_GET['type_order'];
                                    $time_from = date("h:i",strtotime($_GET['time_from']));
                                    $time_to = date("h:i",strtotime($_GET['time_to']));
                                    foreach ($results as $row) {
                                ?>

                                    <tr class="tborder">
                                        <td class="bgm-green-1 text-center dtr-level" colspan="5">
                                            <?php echo $this->Monitor->get_levels($row->id); ?>
                                        </td>
                                    </tr>
                                    <tr class="tborder">
                                        <th class="bgm-green-2 c-black">DATE</th>
                                        <th class="bgm-green-2 c-black">FULLNAME</th>
                                        <th class="bgm-green-2 c-black">TIMELOGS</th>
                                    </tr>
                                        <?php $level['result'] = $this->Monitor->select_members_from_level_logs($_GET['date_from'],$_GET['date_to'],$_GET['category'],$_GET['category_level'],$_GET['type'],$_GET['type_order'],$_GET['time_from'],$_GET['time_to'],$row->levels_id); ?>



                                        <?php $inc = 0; foreach ($level['result'] as $row1) { $inc++ ?>
                                            <tr class="tborder">
                                                <td class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                                                    <h5>
                                                        <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                                            <?php echo Date("Y-m-d",strtotime($row1->timelog)); ?>
                                                        </strong>

                                                    </h5>
                                                </td>
                                                <td class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                                                    <h5>
                                                        <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                                            <?php echo $this->Monitor->get_fullname($row1->id); ?>
                                                        </strong>

                                                    </h5>
                                                </td>
                                                <td class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                                                    <h5>
                                                        <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                                            <?php echo Date("H:i:s",strtotime($row1->timelog)); ?>
                                                        </strong>

                                                    </h5>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                <div class="clearfix"></div>


            </div>


        </div>

    </div>


    <button id="download-csv" class="btn btn-float bgm-green m-btn d-btn waves-effect waves-circle waves-float" ><i class="zmdi zmdi-download"></i></button>

    <button class="btn btn-float bgm-red m-btn" data-action="print"><i class="zmdi zmdi-print"></i></button>


</section>