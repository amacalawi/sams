<div class="modal fade" id="edit-privileges-level" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="privileges-level-edit-table-command-list" value="<?php echo base_url('privileges-levels/listing') ?>">

            <?php echo form_open("privileges-levels/update", array('id'=>'edit-privileges-level-form', 'class'=>'m-t-25 card'), array('id'=>'AJAX_CALL_ONLY')); ?>
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Privileges Level</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Privileges Level Details</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="pad-zero-left">
                                        <div class="form-group fg-float form-privileges-level-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('name', set_value('name'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Name', 'name', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-privileges-level-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('code', set_value('code'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Code', 'code', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pad-zero-right">
                                <div class="form-group fg-float form-privileges-level-validation">
                                    <div class="fg-line">
                                        <?php echo form_textarea(['name'=>'description', 'rows'=>2], set_value('description'), array('class'=>'form-control auto-size')) ?>
                                    </div>
                                    <?php echo form_label('Description', 'description', array('class'=>'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 m-b-20">
                            <div class="pad-zero-right">
                                <div class="form-group fg-float form-group-validation">
                                    <?php echo form_label('Modules', 'modules[]', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                    <?php echo form_dropdown('modules[]',
                                        $form['modules_list'],
                                        set_value('modules'), 'class="tag-select" multiple'
                                    ) ?>
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