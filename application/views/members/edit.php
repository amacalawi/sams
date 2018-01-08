<div class="modal fade" id="edit-member" tabindex="-1" role="dialog" aria-hidden="true" data-modal-edit>
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="card m-t-25">
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Member</h2>
                </div>
                <?php echo form_open_multipart("members/upload_photo", array('id'=>'dropzoneMember', 'class'=>'dropzone m-t-25')); ?>
                    <p><strong>Upload Photo</strong></p>
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

                <?php echo form_open("members/update", array('id'=>'edit-member-form', 'class'=>'m-t-25 card', 'method'=>'POST', 'data-form-edit' => base_url('members/update')), array('id'=>'AJAX_CALL_ONLY')); ?>
                    <div class="modal-body">
                        <div class="form-group">
                            <p class="c-black f-500 m-b-10 text-uppercase"><strong>Personal Details</strong></p>
                            <!-- <hr class="m-t-10 m-b-15"/> -->
                			<div class="row">
                			    <div class="col-lg-4">
                				<div class="pad-zero-right">
                				    <div class="form-group fg-float form-group-validation">
                					<div class="fg-line">
                					    <?php echo form_input('stud_no', set_value('stud_no'), array('class'=>'form-control fg-input')) ?>
                					</div>
                					<?php echo form_label('Student No.', 'stud_no', array('class'=>'fg-label')); ?>
                					<?php echo form_error('stud_no', '<p class="error">', '</p>'); ?>
                				    </div>
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
                                            <?php echo form_error('firstname', '<p class="error">', '</p>'); ?>
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
                                <div class="col-md-4 m-b-20">
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
                                <div class="col-md-4 m-b-20">
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
                                <div class="col-md-4 m-b-20">
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
                                                <?php echo form_input('telephone', set_value('telephone'), array('class'=>'form-control fg-input')) ?>
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
                        <?php echo form_button(array('name'=>'submit', 'id'=>'submit', 'type'=>'submit'), 'Update', 'class="btn btn-link"') ?>
                        <?php echo form_button('close', 'Cancel', 'class="btn btn-link" data-dismiss="modal"') ?>
                    </div>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
