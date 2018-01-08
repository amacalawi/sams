<div class="modal fade" id="edit-module" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <?php echo form_open("modules/update", array('id'=>'edit-module-form', 'class'=>'m-t-25 card', 'method'=>'POST'), array('id'=>'AJAX_CALL_ONLY')); ?>
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Module</h2>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p class="c-black f-500 m-b-10 text-uppercase"><strong>Module Details</strong></p>
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('name', set_value('name'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Name', 'Name', array('class'=>'fg-label')) ?>
                                        <?php echo form_error('name', '<p class="error">', '</p>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('slug', set_value('slug'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Slug', 'slug', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_textarea(['name'=>'description', 'rows'=>2], set_value('description'), array('class'=>'form-control auto-size')) ?>
                                        </div>
                                        <?php echo form_label('Module description', 'description', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php echo form_button(array('name'=>'submit', 'id'=>'submit', 'type'=>'submit'), 'Update', 'class="btn btn-link"') ?>
                    <?php echo form_button('close', 'Cancel', 'class="btn btn-link" data-dismiss="modal"') ?>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>