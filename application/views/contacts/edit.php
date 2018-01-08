<div class="modal fade" id="edit-contact" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <?php echo form_open("contacts/update", array('id'=>'edit-contact-form', 'class'=>'m-t-25 card', 'method'=>'POST'), array('contacts_id'=>'AJAX_CALL_ONLY')); ?>
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Contact</h2>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <p class="c-black f-500 m-b-10 text-uppercase"><strong>Personal Details</strong></p>
                        <!-- <hr class="m-t-10 m-b-15"/> -->
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('contacts_firstname', set_value('contacts_firstname'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('First name', 'contacts_firstname', array('class'=>'fg-label')) ?>
                                        <?php echo form_error('contacts_firstname', '<p class="error">', '</p>'); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('contacts_middlename', set_value('contacts_middlename'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Middle name', 'contacts_middlename', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-12">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('contacts_lastname', set_value('contacts_lastname'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Last name', 'contacts_lastname', array('class'=>'fg-label')) ?>
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
                                        <?php echo form_label('Level', 'contacts_level', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                        <?php echo form_dropdown('contacts_level',
                                            $form['levels_list'],
                                            set_value('contacts_level'), 'class="tag-select"'
                                        ) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 m-b-20">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <?php echo form_label('Type', 'contacts_type', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                        <?php echo form_dropdown('contacts_type',
                                            $form['types_list'],
                                            set_value('contacts_type'), 'class="tag-select"'
                                        ) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 m-b-20">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <?php echo form_label('Group', 'contacts_group[]', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                        <?php echo form_dropdown('contacts_group[]',
                                            $form['groups_list'],
                                            set_value('contacts_group'), 'class="tag-select" multiple'
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
                                            <?php echo form_input('contacts_blockno', set_value('contacts_blockno'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Block No.', 'contacts_blockno', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('contacts_street', set_value('contacts_street'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Street', 'contacts_street', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('contacts_brgy', set_value('contacts_brgy'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Subdivision / Brgy', 'contacts_brgy', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('contacts_city', set_value('contacts_city'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Town / City', 'contacts_city', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('contacts_zip', set_value('contacts_zip'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('ZIP Code', 'contacts_zip', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <p class="c-black f-500 m-b-10 text-uppercase"><strong>Contact Details</strong></p>
                        <!-- <hr class="m-t-10 m-b-15"/> -->
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('contacts_telephone', set_value('contacts_telephone'), array('class'=>'bfh-phone form-control fg-input', 'data-format'=>'ddd-ddd dddd')) ?>
                                        </div>
                                        <?php echo form_label('Telephone No.', 'contacts_telephone', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('contacts_mobile', set_value('contacts_mobile'), array('class'=>'bfh-phone form-control fg-input', 'data-format'=>'d(ddd) ddd-dddd')) ?>
                                        </div>
                                        <?php echo form_label('Mobile No.', 'contacts_mobile', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('contacts_email', set_value('contacts_email'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Email', 'contacts_email', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php echo form_button(array('name'=>'contacts_submit', 'id'=>'contacts_submit', 'type'=>'submit'), 'Update', 'class="btn btn-link"') ?>
                    <?php echo form_button('contacts_close', 'Cancel', 'class="btn btn-link" data-dismiss="modal"') ?>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>