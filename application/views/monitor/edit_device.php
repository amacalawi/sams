<div class="modal fade" id="edit-device" tabindex="-1" role="dialog" aria-hidden="true" data-modal-edit>
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="device-edit-table-command-list" value="<?php echo base_url('monitor/devices/listing') ?>">

            <?php echo form_open(base_url("monitor/devices/update"), array('id'=>'edit-new-device-form', 'class'=>'m-t-25 card', 'data-form-edit' => base_url('monitor/devices/update'))); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Device</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="c-black f-500 m-b-10 text-uppercase"><strong>Device Details</strong></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('name', set_value('name'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Device Name', 'name', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('code', set_value('code'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Code', 'code', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                        <?php echo form_textarea(['name'=>'description', 'rows'=>5], set_value('description'), array('class'=>'form-control auto-size')) ?>
                                        </div>
                                        <?php echo form_label('Device Description', 'text', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <?php echo form_label('Gate', 'gate_id', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                        <?php echo form_dropdown('gate_id',
                                            $form['gates_list'],
                                            set_value('gate_id'), 'class="tag-select"'
                                        ) ?>
                                    </div>
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
