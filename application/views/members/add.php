<div class="modal fade" id="add-member" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card m-t-25">
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New Member</h2>
                </div>
                <?php echo form_open_multipart("members/upload_photo", array('id'=>'dropzoneMember', 'class'=>'dropzone m-t-25 member-photo-upload')); ?>
                    <p class="text-center"><strong>Click/Drop Photo to upload</strong></p>
                    <div class="fileinput fileinput-new fallback" data-provides="fileinput">
                        <span class="btn btn-primary btn-file m-r-10">
                            <span class="fileinput-new">Select file</span>
                            <span class="fileinput-exists">Change</span>
                            <div class="file-preview-other"></div>
                            <?php echo form_upload( 'file', set_value('file'), ['class'=>'file-input-field', 'accept'=>'image/*'] ) ?>
                        </span>
                        <span class="fileinput-filename"></span>
                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput">&times;</a>
                        <button type="submit" class="btn btn-primary hidden">Submit</button>
                    </div>
                <?php echo form_close() ?>
                <?php echo form_open("members/add", array('role'=>"form", 'id'=>'add-new-member-form', 'class'=>'')); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <p class="c-black f-500 m-b-10 text-uppercase"><strong>Enrollment Status</strong></p>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <!-- <label role="button" for="schoolyear_id">Current School Year</label> -->
                                            <select id="schoolyear_id" name="schoolyear_id" class="tag-select">
                                                <?php foreach ($schoolyears_list as $key => $sy) { ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $sy; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <label role="button" for="new_status">
                                                <input id="new_status" type="radio" name="enrollment_status" value="NEW" checked="checked"> NEW
                                            </label>
                                            &nbsp;
                                            <label role="button" for="old_status">
                                                <input id="old_status" type="radio" name="enrollment_status" value="OLD"> OLD
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <p class="c-black f-500 m-b-10 text-uppercase"><strong>Personal Details</strong></p>
                            <!-- <hr class="m-t-10 m-b-15"/> -->
                            <div class="row">
                                <div class="col-lg-12 col-md-12">
                                    <div class="pad-zero-right">
                                        <?php echo form_label('Student number', 'stud_no', array('class'=>'fg-label d-b')) ?>
                                        <div class="form-group form-group-validation d-ib">
                                            <select class="input-selectize" name="stud_no">
                                                <?php foreach ($form['studentsnumber_list'] as $key => $value) { ?>
                                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <button data-checker data-target="select[name=stud_no]" type="button" class="btn btn-secondary d-ib">Load</button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4 col-md-12">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('firstname', set_value('firstname'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('First name', 'firstname', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('middlename', set_value('middlename'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Middle name', 'middlename', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-12">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('lastname', set_value('lastname'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Last name', 'lastname', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="col-md-6 m-b-20">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <?php echo form_label('Gender', 'gender', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                            <?php echo form_dropdown('gender',
                                                $form['gender_list'],
                                                set_value(''), 'class="tag-select"'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 m-b-20">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <?php echo form_label('Level', 'level', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                            <?php echo form_dropdown('level',
                                                $form['levels_list'],
                                                set_value('level'), 'class="tag-select"'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 m-b-20">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <?php echo form_label('Type', 'type', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                            <?php echo form_dropdown('type',
                                                $form['types_list'],
                                                set_value('type'), 'class="tag-select"'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 m-b-20">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <?php echo form_label('Group', 'groups[]', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                            <?php echo form_dropdown('groups[]',
                                                $form['groups_list'],
                                                set_value('groups'), 'class="tag-select" multiple'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>
                                <!-- Schedules -->
                                <div class="col-md-12 m-b-20">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <?php echo form_label('Schedule', 'schedule_id', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                            <?php echo form_dropdown('schedule_id',
                                                $form['schedules_list'],
                                                set_value('schedule_id'), 'class="tag-select"'
                                            ) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                        </div>

                        <div class="form-group">
                            <p class="c-black f-500 m-b-10 text-uppercase"><strong>Address Details</strong></p>
                            <!-- <hr class="m-t-10 m-b-15"/> -->
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('address_blockno', set_value('address_blockno'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Block No.', 'address_blockno', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('address_street', set_value('address_street'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Street', 'address_street', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('address_brgy', set_value('address_brgy'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Subdivision / Brgy', 'address_brgy', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('address_city', set_value('address_city'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Town / City', 'address_city', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('address_zip', set_value('address_zip'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('ZIP Code', 'address_zip', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <p class="c-black f-500 m-b-10 text-uppercase"><strong>Member Details</strong></p>
                            <!-- <hr class="m-t-10 m-b-15"/> -->
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('telephone', set_value('telephone'), array('class'=>'bfh-phone form-control fg-input', 'data-format'=>'ddd-ddd dddd')) ?>
                                            </div>
                                            <?php echo form_label('Telephone No.', 'telephone', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('msisdn', set_value('msisdn'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Mobile No.', 'msisdn', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('email', set_value('email'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Email', 'email', array('class'=>'fg-label')) ?>
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
</div>

<style>
    .d-b {
        display: block;
    }
    .d-ib {
        display: inline-block;
        vertical-align: top;
    }
    .form-group.d-ib {
        width: 230px;
        display: inline-block;
        margin-right: 1rem;
    }
</style>