<section id="content" class="<?php echo $this->router->fetch_method(); ?>">

<?php if($_GET['type']=="Monthly") { ?>
    <div class="container-fluid invoice-landscape">
<?php } else { ?>
    <div class="container invoice">
<?php } ?>
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
                        <?php include __DIR__ . '/generate-summary.php' ?>
                    <?php elseif (isset($_GET['type']) &&($_GET['type']=="Monthly")): ?>
                        <?php include __DIR__ . '/generate-monthly.php' ?>
                    <?php elseif (isset($_GET['type']) &&($_GET['type']=="Absents_Only")): ?>
                        <?php include __DIR__ . '/generate-absent.php' ?>

                    <?php elseif (isset($_GET['type']) &&($_GET['type']=="Late_Only")): ?>
                        <?php include __DIR__ . '/generate-late.php' ?>

                    <?php else: ?>
                        <?php include __DIR__ . '/generate-detailed.php' ?>
                    <?php endif; ?>
                <div class="clearfix"></div>


            </div>


        </div>

    </div>


    <button id="download-csv" class="btn btn-float bgm-green m-btn d-btn waves-effect waves-circle waves-float" ><i class="zmdi zmdi-download"></i></button>

    <button class="btn btn-float bgm-red m-btn" data-action="print"><i class="zmdi zmdi-print"></i></button>


</section>