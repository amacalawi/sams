<div class="modal fade" id="add-schedule" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <?php echo form_open("schedules/add", array('id'=>'add-new-schedule-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New Schedule</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Schedule Details</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <div class="pad-zero-left">
                                            <div class="form-type fg-float form-group-validation">
                                                <div class="fg-line">
                                                    <?php echo form_input('name', set_value('name'), array('class'=>'form-control fg-input')) ?>
                                                </div>
                                                <?php echo form_label('Name', 'name', array('class'=>'fg-label')) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <div class="pad-zero-right">
                                            <div class="form-type fg-float form-group-validation">
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
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-control-label"><strong>NORMAL_IN</strong><br>Usual Time in</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="normal_in_from">From</label>
                                                        <?php echo form_input('normal_in_from', set_value('normal_in_from'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="normal_in_to">To</label>
                                                        <?php echo form_input('normal_in_to', set_value('normal_in_to'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="normal_in_from">Normal In Preset Msg</label>
                                                        <?php echo form_dropdown('preset_message_normal_in_id',
                                                            $form['preset_message'],
                                                            set_value('preset_message_id'), 'class="tag-select"'
                                                        ) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-control-label"><strong>NORMAL_OUT</strong><br>Usual Time out</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="normal_out_from">From</label>
                                                        <?php echo form_input('normal_out_from', set_value('normal_out_from'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="normal_out_to">To</label>
                                                        <?php echo form_input('normal_out_to', set_value('normal_out_to'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="normal_in_from">Normal Out Preset Msg</label>
                                                        <?php echo form_dropdown('preset_message_normal_out_id',
                                                            $form['preset_message'],
                                                            set_value('preset_message_id'), 'class="tag-select"'
                                                        ) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- LATE -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-control-label"><strong>LATE_IN</strong><br>Late Time in</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="late_in_from">From</label>
                                                        <?php echo form_input('late_in_from', set_value('late_in_from'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="late_in_to">To</label>
                                                        <?php echo form_input('late_in_to', set_value('late_in_to'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="normal_in_from">Late In Preset Msg</label>
                                                        <?php echo form_dropdown('preset_message_late_in_id',
                                                            $form['preset_message'],
                                                            set_value('preset_message_id'), 'class="tag-select"'
                                                        ) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-control-label"><strong>LATE_OUT</strong><br>Late Time out</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="late_out_from">From</label>
                                                        <?php echo form_input('late_out_from', set_value('late_out_from'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="late_out_to">To</label>
                                                        <?php echo form_input('late_out_to', set_value('late_out_to'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="normal_in_from">Late Out Preset Msg</label>
                                                        <?php echo form_dropdown('preset_message_late_out_id',
                                                            $form['preset_message'],
                                                            set_value('preset_message_id'), 'class="tag-select"'
                                                        ) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- EARLY OUT -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-control-label"><strong>EARLY_IN</strong><br>Early Time in</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="early_in_from">From</label>
                                                        <?php echo form_input('early_in_from', set_value('early_in_from'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="early_in_to">To</label>
                                                        <?php echo form_input('early_in_to', set_value('early_in_to'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="normal_in_from">Early In Preset Msg</label>
                                                        <?php echo form_dropdown('preset_message_early_in_id',
                                                            $form['preset_message'],
                                                            set_value('preset_message_id'), 'class="tag-select"'
                                                        ) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-control-label"><strong>EARLY_OUT</strong><br>Early Time out</div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="early_out_from">From</label>
                                                        <?php echo form_input('early_out_from', set_value('early_out_from'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="early_out_to">To</label>
                                                        <?php echo form_input('early_out_to', set_value('early_out_to'), array('class'=>'form-control fg-input time-picker')) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <div class="form-type form-group-validation">
                                                    <div class="fg-line">
                                                        <label for="normal_in_from">Early Out Preset Msg</label>
                                                        <?php echo form_dropdown('preset_message_early_out_id',
                                                            $form['preset_message'],
                                                            set_value('preset_message_id'), 'class="tag-select"'
                                                        ) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
