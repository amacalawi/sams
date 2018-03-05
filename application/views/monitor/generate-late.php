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
            foreach ($results as $row) { $incs++
        ?>      
   
                <?php $level['result'] = $this->Monitor->select_late_logs($_GET['date_from'],$_GET['date_to'],$_GET['category'],$_GET['category_level'],$_GET['type'],$_GET['type_order'],$_GET['time_from'],$_GET['time_to'],$row->id); ?>
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