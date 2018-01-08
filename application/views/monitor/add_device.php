<div class="modal fade" id="add-device" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <?php echo form_open("monitor/devices/add", array('id'=>'add-new-device-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New Device</h2>
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
                    <?php echo form_button(array('name'=>'submit', 'id'=>'submit', 'type'=>'submit'), 'Add', 'class="btn btn-link"') ?>
                    <?php echo form_button('close', 'Close', 'class="btn btn-link" data-dismiss="modal"') ?>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>