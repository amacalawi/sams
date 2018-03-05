<div class="table-responsive">
<table class="table i-table m-t-25 m-b-25">
    <tbody>
        <thead>            
            <?php 
                $date_from = str_replace('/', '-', urldecode($this->input->get('date_from')));
                $date_to = str_replace('/', '-', urldecode($this->input->get('date_to')));
            ?>            
            <?php
                $female_display = 0; $male_display = 0; $inc = 0;
                foreach ($results as $row) {
                    $date_from = str_replace('/', '-', urldecode($this->input->get('date_from')));
                    $date_to = str_replace('/', '-', urldecode($this->input->get('date_to')));
                    $time_from = strtotime(urldecode($this->input->get('time_from')));
                    $time_to = strtotime(urldecode($this->input->get('time_to')));
                    $totaltime = 0;
                    $totallate = 0;
                    $inc++;
                    $p = 0; $a = 0;
                    $t = 0; $c = 0;
            ?>      
                <?php if($row->gender == 'Female' && $inc == 1 && $female_display == 0) { $female_display = 1; ?>
                    <tr class="tborder">
                        <td class="bgm-green-1 text-center dtr-level" colspan="40">
                        <?php echo $row->gender; ?>
                        </td>
                    </tr>
                <?php } else if($row->gender == 'Male' && $male_display == 0) { $male_display = 1;?>
                    <tr class="tborder">
                        <td class="bgm-green-1 text-center dtr-level" colspan="40">
                        <?php echo $row->gender; ?>
                        </td>
                    </tr>
                <?php } ?>
                <?php if($inc == 1) { ?>
                <tr class="tborder">
                    <th class="bgm-green-2 c-black text-center">FULLNAME</th>
                    <?php for ($i=strtotime($date_from); $i<=strtotime($date_to); $i+=86400) { ?>
                        <?php if ( (date("D", $i) != 'Sat') && (date("D", $i) != 'Sun') ) { ?>     
                        <th class="bgm-green-2 c-black align-horizontal-holder text-center"><div><?php echo date("D", $i); ?><br/><?php echo date("d", $i); ?></div></th>
                        <?php } ?>
                    <?php } ?>
                    <th class="legends c-black align-horizontal-holder text-center">P</th>
                    <th class="legends c-black align-horizontal-holder text-center">A</th>
                    <th class="legends c-black align-horizontal-holder text-center">T</th>
                    <th class="legends c-black align-horizontal-holder text-center">INC</th>
                </tr>
                <?php } ?>
                <tr class="tborder">
                    <td class="text-center c-black">
                        <strong><?php echo ucwords(strtolower($this->Monitor->get_fullname($row->id))); ?></strong>
                    </td>
                    <?php $inc = 0; for ($i=strtotime($date_from); $i<=strtotime($date_to); $i+=86400) { $inc++; ?>
                    <?php
                        $timed_in = ($this->Monitor_New->get_timed_in($row->stud_no, date("Y-m-d", $i)) != 0) ? $this->Monitor_New->get_timed_in($row->stud_no, date("Y-m-d", $i)) : '';
                        $timed_out = ($this->Monitor_New->get_timed_out($row->stud_no,date("Y-m-d", $i)) != 0) ? $this->Monitor_New->get_timed_out($row->stud_no,date("Y-m-d", $i)) : '';
                        $timed_late = ($this->Monitor_New->get_timed_late($row->stud_no,date("Y-m-d", $i)) != '00:00:00') ? $this->Monitor_New->get_timed_late($row->stud_no, date("Y-m-d", $i)) : '';
                    ?>  
                        <?php if ( (date("D", $i) != 'Sat') && (date("D", $i) != 'Sun') ) { ?>  
                            <?php if(!empty($timed_late)) { $t++; ?>
                            <td class="text-center">
                                T
                            </td>
                            <?php } else if(!empty($timed_in) && !empty($timed_out)) { $p++; ?>
                            <td class="bgm-green-2 text-center">
                                P
                            </td>
                            <?php }else if((!empty($timed_in) && empty($timed_out)) || (empty($timed_in) && !empty($timed_out))) { $c++; ?>
                            <td class="text-center">
                                INC
                            </td>
                            <?php } else { $a++; ?>
                            <td class="text-center">
                                A
                            </td>
                            <?php } ?>
                        <?php } ?>                    
                    <?php } ?>                      
                    <td class="text-center legends align-horizontal-holder"><?php echo $p; ?></td>
                    <td class="text-center legends align-horizontal-holder"><?php echo $a; ?></td>
                    <td class="text-center legends align-horizontal-holder"><?php echo $t; ?></td>
                    <td class="text-center legends align-horizontal-holder"><?php echo $c; ?></td>
                </tr>
            <?php
                }
            ?>
        </thead>
    </tbody>
</table>
</div>