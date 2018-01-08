<div class="modal fade" id="add-schoolyear" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="schoolyear-add-table-command-list" value="<?php echo base_url('contacts/listing') ?>">

            <?php echo form_open("schoolyears/add", array('id'=>'add-new-schoolyear-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New School year</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>School year Details</strong></p>
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
                                        <?php echo form_textarea(['name'=>'description', 'rows'=>2], set_value('schoolyears_description'), array('class'=>'form-control auto-size')) ?>
                                    </div>
                                    <?php echo form_label('Description', 'description', array('class'=>'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="pad-zero-left">
                                <div class="form-group fg-float form-group-validation">
                                    <div class="fg-line">
                                        <?php echo form_input('year_start', set_value('year_start'), array('class' => 'form-control fg-input year-picker picker-one')) ?>
                                    </div>
                                    <?php echo form_label('Year Start', 'year_start', array('class' => 'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="pad-zero-right">
                                <div class="form-group fg-float form-group-validation">
                                    <div class="fg-line">
                                        <?php echo form_input('year_end', set_value('year_end'), array('class' => 'form-control fg-input year-picker picker-two')) ?>
                                    </div>
                                    <?php echo form_label('Year End', 'year_end', array('class' => 'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="pad-zero-left">
                                <div class="form-group fg-float form-group-validation">
                                    <div class="fg-line">
                                        <?php echo form_dropdown('status',
                                            $form['status_list'],
                                            set_value('status'), 'class="tag-select form-control fg-input"'
                                        ) ?>
                                    </div>
                                    <?php echo form_label('Status', 'status', array('class'=>'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-link">Add</button>
                    <button type="reset" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>