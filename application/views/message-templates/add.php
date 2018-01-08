<div class="modal fade" id="add-message-template" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <?php echo form_open("messaging/templates/add", array('id'=>'add-new-message-template-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New Message Template</h2>
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
                                                <?php echo form_textarea(['name'=>'name', 'rows'=>2], set_value('name'), array('class'=>'form-control auto-size')) ?>
                                            </div>
                                            <?php echo form_label('Message', 'name', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="pad-zero-right">
                                <div class="form-message-template fg-float form-message-template-validation">
                                    <?php echo form_label('Code', 'code', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                    <div class="fg-line">
                                        <?php echo form_input('code', set_value('code'), array('class'=>'form-control fg-input')) ?>
                                    </div>
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

                <div class="modal-footer">
                    <button id="message-template-add" type="submit" class="btn btn-link">Add</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>