<div class="modal fade" id="add-enrollment" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="enrollment-add-table-command-list" value="<?php echo base_url('contacts/listing') ?>">

            <?php echo form_open("enrollments/add", array('id'=>'add-new-enrollment-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New Enrollment</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Enrollment Details</strong></p>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="pad-zero-left">
                                <div class="form-group fg-float form-group-validation">
                                    <div class="fg-line">
                                        <?php echo form_dropdown('member_id',
                                            $form['members_list'],
                                            set_value('member_id'), 'class="tag-select form-control fg-input"'
                                        ) ?>
                                    </div>
                                    <?php #echo form_label('Member', 'member_id', array('class'=>'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="pad-zero-left">
                                <div class="form-group fg-float form-group-validation">
                                    <div class="fg-line">
                                        <?php echo form_dropdown('schoolyear_id',
                                            $form['schoolyears_list'],
                                            set_value('schoolyear_id'), 'class="tag-select form-control fg-input"'
                                        ) ?>
                                    </div>
                                    <?php #echo form_label('School Year', 'schoolyear_id', array('class'=>'fg-label')) ?>
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