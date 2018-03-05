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
            $female_display = 0; $male_display = 0; $incs = 0;
            foreach ($results as $row) { $incs++;
        ?>         
                <?php if($row->gender == 'Female' && $incs == 1 && $female_display == 0) { $female_display = 1; ?>
                    <tr class="tborder">
                        <td class="bgm-green-1 text-center dtr-level" colspan="31">
                        <?php echo $row->gender; ?>
                        </td>
                    </tr>
                <?php } else if($row->gender == 'Male' && $male_display == 0) { $male_display = 1;?>
                    <tr class="tborder">
                        <td class="bgm-green-1 text-center dtr-level" colspan="31">
                        <?php echo $row->gender; ?>
                        </td>
                    </tr>
                <?php } ?>    

                <?php $inc = 0; 
                for ($i=$date_from; $i<=$date_to; $i+=86400) { $inc++; 
                ?>
                <?php $absent = $this->Monitor_New->select_absent_logs($date_from, $date_to, $_GET['category'],$_GET['category_level'],$_GET['type'],$_GET['type_order'], $time_from, $time_to, $row->id, date("Y-m-d", $i)); ?>
                <?php if ( (date("D", $i) != 'Sat') && (date("D", $i) != 'Sun') ) { ?>                    
                    <?php if(!($absent > 0)) { ?>                        
                        <?php if($inc == 1 && $incs == 1) { ?>
                            <tr class="tborder">
                                <th class="bgm-green-2 c-black">DATE</th>
                                <th class="bgm-green-2 c-black">FULLNAME</th>
                                <th class="bgm-green-2 c-black">TIMELOGS</th>
                            </tr>
                        <?php } ?>                        
                        <tr class="tborder">
                            <td>
                                <h5>
                                    <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                        <?php echo date("Y-m-d", $i); ?>
                                    </strong>

                                </h5>
                            </td>
                            <td>
                                <h5>
                                    <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                        <?php echo $this->Monitor->get_fullname($row->id); ?>
                                    </strong>

                                </h5>
                            </td>
                            <td>
                                <h5>
                                    <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                        NO TIMELOGS FOUND
                                    </strong>
                                </h5>
                            </td>
                        </tr>
                    <?php } // if absent ?> 

                    <?php } // if not sat and sunday ?>                    

                <?php } // forloop ?>

                
        <?php } //for loop ?>
    </tbody>
</table>