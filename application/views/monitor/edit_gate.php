<div class="modal fade" id="edit-gate" tabindex="-1" role="dialog" aria-hidden="true" data-modal-edit>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="gate-edit-table-command-list" value="<?php echo base_url('monitor/gates/listing') ?>">

            <?php echo form_open(base_url("monitor/gates/update"), array('id'=>'edit-new-gate-form', 'class'=>'m-t-25 card', 'data-form-edit' => base_url('monitor/gates/update'))); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Gate</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Gate Details</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="pad-zero-left">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('name', set_value('name'), array('class' => 'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Name', 'name', array('class' => 'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('code', set_value('code'), array('class' => 'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Code', 'code', array('class' => 'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pad-zero-right">
                                <div class="form-group fg-float form-group-validation">
                                    <div class="fg-line">
                                        <?php echo form_textarea(['name'=>'description', 'rows'=>2], set_value('description'), array('class'=>'form-control auto-size')) ?>
                                    </div>
                                    <?php echo form_label('Description', 'description', array('class'=>'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-link">Update</button>
                    <button type="reset" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
