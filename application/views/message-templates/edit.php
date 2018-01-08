<div class="modal fade" id="edit-template" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-fluid">
        <div class="modal-content">

            <?php echo form_open("messaging/templates/update", array('id'=>'edit-message-template-form', 'class'=>'m-t-25 card', 'method'=>'POST'), array('id'=>'AJAX_CALL_ONLY')); ?>
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Message Template</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Template Details</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="pad-zero-left">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('name', set_value('name'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Name', 'name', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <!-- <div class="fg-line"> -->
                                                <?php echo form_label('Code', 'code', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                                <?php echo form_input('code', set_value('code'), array('class'=>'form-control fg-input')) ?>
                                            <!-- </div> -->
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-7">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <?php echo form_label('Type', 'type', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                            <?php echo form_dropdown('type',
                                                array(
                                                    'DEFAULT' => 'Default',
                                                    'NORMAL_IN' => 'NORMAL IN',
                                                    'NORMAL_OUT' => 'NORMAL OUT',
                                                    "LATE_IN" => 'LATE IN',
                                                    'LATE_OUT' => 'LATE OUT',
                                                    'EARLY_IN' => 'EARLY IN',
                                                    'EARLY_OUT' => 'EARLY OUT',
                                                ),
                                                set_value('type'), 'class="form-control tag-select"'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-link">Update</button>
                    <button type="button" name="close" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
