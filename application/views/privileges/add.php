<div class="modal fade" id="add-privilege" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="privilege-add-table-command-list" value="<?php echo base_url('privileges/listing') ?>">

            <?php echo form_open("privileges/add", array('id'=>'add-new-privilege-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New Privilege</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Privilege Details</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="pad-zero-left">
                                        <div class="form-group fg-float form-privilege-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('name', set_value('name'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Privilege name', 'name', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-privilege-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('code', set_value('code'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Privilege code', 'code', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pad-zero-right">
                                <div class="form-group fg-float form-privilege-validation">
                                    <div class="fg-line">
                                        <?php echo form_textarea(['name'=>'description', 'rows'=>2], set_value('description'), array('class'=>'form-control auto-size')) ?>
                                    </div>
                                    <?php echo form_label('Description', 'description', array('class'=>'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 m-b-20">
                            <div class="pad-zero-right">
                                <div class="form-group fg-float form-group-validation">
                                    <?php echo form_label('Privilege Level', 'level', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                    <?php echo form_dropdown('level',
                                        $form['privileges_levels_list'],
                                        set_value('level'), 'class="tag-select"'
                                    ) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-link">Add</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>