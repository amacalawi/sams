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
                <?php  $inc = 0; for ($i=$date_from; $i<=$date_to; $i+=86400) { ?>
                <?php $absent = $this->Monitor->select_absent_logs($_GET['date_from'],$_GET['date_to'],$_GET['category'],$_GET['category_level'],$_GET['type'],$_GET['type_order'],$_GET['time_from'],$_GET['time_to'],$row->id, date("Y-m-d", $i)); ?>
                <?php if(!($absent > 0)) { ?>
                    <?php if($inc == 1) { ?>
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
                    <?php } ?>
                    <?php if ( (date("D", $i) != 'Sat') && (date("D", $i) != 'Sun') ) { ?>
                    <?php $inc++ ?>
                        <tr class="tborder">
                            <td class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                                <h5>
                                    <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                        <?php echo date("Y-m-d", $i); ?>
                                    </strong>

                                </h5>
                            </td>
                            <td class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                                <h5>
                                    <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                        <?php echo $this->Monitor->get_fullname($row->id); ?>
                                    </strong>

                                </h5>
                            </td>
                            <td class="<?php echo ($inc%2 == 0) ? 'bgm-green-2' : '' ?>">
                                <h5>
                                    <strong class="<?php echo ($inc%2 == 0) ? 'c-black' : '' ?>">
                                        <?php echo 'NO TIMED IN AND OUT' ?>
                                    </strong>

                                </h5>
                            </td>
                        </tr>
                    <?php } ?>
                <?php } } ?>
        <?php
            }
        ?>
    </tbody>
</table>