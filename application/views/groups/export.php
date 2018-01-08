<section id="content">
    <div class="container">
        <?php if( isset($messages) ): ?>
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <?php echo $messages['error']; ?>
            </div>
        <?php endif; ?>
        <div class="card">
            <?php echo breadcrumbs('', 'Export Group') ?>
            <div class="card-body card-padding">

                <div class="clearfix m-b-25"></div>

                <?php echo form_open('groups/export', array('id'=>'export-groups-form', 'class'=>'export-groups-form m-t-25')) ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group form-group-validation">
                                <?php echo form_label('Date From', 'export_start', array('class'=>'c-black f-500 m-b-20')) ?>
                                <div class="dtp-container fg-line">
                                    <?php echo form_input('export_start', set_value('export_start'), array('id'=>'export_start', 'class'=>'form-control date-picker', 'placeholder'=>'MM/DD/YYYY')) ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 m-b-25">
                            <div class="form-group form-group-validation">
                                <?php echo form_label('Date To', 'export_end', array('class'=>'c-black f-500 m-b-20')) ?>
                                <div class="dtp-container fg-line">
                                    <?php echo form_input('export_end', set_value('export_end'), array('id'=>'export_end', 'class'=>'form-control date-picker', 'placeholder'=>'MM/DD/YYYY')) ?>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-sm-6 m-b-15">
                            <input type="hidden" name="export_format" value="CSV">
                        </div>
                        <div class="clearfix"></div>

                        <div class="col-sm-6 m-b-10">
                            <button id="export-btn" name="export_submit" type="submit"class="btn bgm-red waves-effect m-t-10"><i class="zmdi zmdi-case-download"></i> &nbsp; Export Now</button>
                        </div>
                    </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</section>