    <section id="content">
         <!--pre>
         <?php //$totallate = 0; ?>
         <?php //var_dump($in = $this->Monitor_New->get_dtrlog_in_2('2006141', '2017-10-08')); ?>

         <?php //var_dump( $time_in_hr = Date("H",strtotime($in))* 60) ; ?>
         <?php //var_dump( $time_in_min = Date("i",strtotime($in))* 60) ; ?>

         
          <?php //var_dump($reg_in_hr = Date("H",strtotime($this->Monitor_New->get_late_in_by_member_id(4096)))* 60); ?>

          <?php //var_dump($reg_in_min  = (Date("i",strtotime($this->Monitor_New->get_late_in_by_member_id(4096))) - 1)); ?>

          <?php //var_dump(date("H:i", strtotime('00:00:00')) ); ?>

          <?php  /*if($time_in_hr > $reg_in_hr)
                    {
                        $minutes = ($time_in_hr + $time_in_min) - ($reg_in_hr + $reg_in_min);
                        $totallate = $totallate + $minutes;

                    } 
                    else if (($time_in_hr == ($reg_in_hr)) && $time_in_min > 0)
                    {
                        $totallate = $totallate + $time_in_min;
                    }   
*/
                    ?>

          <?php //echo $totallate = ($totallate * 60); ?>

        <?php //echo date("H:i", strtotime( ( $totallate > 0) ? gmdate("H:i:s", ($totallate)) : '00:00:00' )  ); ?>

         


          <?php //var_dump( ); ?>
      </pre-->

        <div class="container">
            <div class="card">
                <div class="card-header m-b-25">
                    <h2>Daily Time Record
                        <ul class="pull-right breadcrumb">
                            <li><a href="all-groups.html">Monitor</a></li>
                            <li class="active">Daily Time Record</li>
                        </ul>
                    </h2>
                </div>
                <div class="card-body card-padding">
    <!--                Cras leo sem, egestas a accumsan eget, euismod at nunc. Praesent vel mi blandit, tempus ex gravida, accumsan dui. Sed sed aliquam augue. Nullam vel suscipit purus, eu facilisis ante. Mauris nec commodo felis.-->
                    <div class="clearfix m-b-25"></div>

                    <div class="row">
                        <div class="col-sm-6">
                            <p class="c-black f-500 m-b-20">Date Start</p>
                            <div class="dtp-container fg-line">
                            <input type='text' id="date_from" class="form-control date-picker" placeholder="Click here...">
                            </div>
                        </div>

                        <div class="col-sm-6 m-b-25">
                            <p class="c-black f-500 m-b-20">Date End</p>
                            <div class="dtp-container fg-line">
                            <input type='text' id="date_to" class="form-control date-picker" placeholder="Click here...">
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-sm-6">
                            <p class="c-black f-500 m-b-20">Time Start</p>
                            <div class="dtp-container fg-line">
                                <input type='text' id="time_from" class="form-control time-picker" placeholder="Click here...">
                            </div>
                        </div>

                        <div class="col-sm-6 m-b-25">
                            <p class="c-black f-500 m-b-20">Time End</p>
                            <div class="dtp-container fg-line">
                                <input type='text' id="time_to" class="form-control time-picker" placeholder="Click here...">
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-sm-6 m-b-25">
                            <p class="f-500 c-black m-b-25">Select Category</p>
                            <select id="category" class="tag-select">
                                <option value="">Select</option>
                                <option value="All">All</option>
                                <option value="Contact">Contact</option>
                                <option value="Level">Level</option>
                                <option value="Group">Group</option>
                            </select>
                        </div>
                        <div class="col-sm-6">
                            <p class="f-500 c-black m-b-25">Select Category Level</p>
                            <select id="category_level" class="tag-select">
                            </select>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-sm-6 m-b-15">
                            <p class="f-500 c-black m-b-25">Select Report Type</p>
                            <select id="type" class="tag-select">
                                <option value="">Select</option>
                                <option value="Detailed">Detailed</option>
                                <option value="Summary">Summary</option>
                                <option value="Absents_Only">Absents Only</option>
                                <option value="Late_Only">Late Only</option>
                            </select>
                        </div>
                        <div class="col-sm-6 m-b-15">
                            <p class="f-500 c-black m-b-25">Select Order Type</p>
                            <select id="type_order" class="tag-select" style="display:none">
                                <option value="">Select</option>
                                <option value="ASC">A-z</option>
                                <option value="DESC">z-A</option>
                            </select>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-sm-6 m-b-10">
                            <button id="generate-report" class="btn bgm-red waves-effect m-t-10"><i class="zmdi zmdi-case-download">&nbsp;</i>Generate Now</button>
                        </div>

                    </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
