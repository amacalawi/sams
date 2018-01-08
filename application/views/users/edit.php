<div class="modal fade" id="edit-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="user-edit-table-command-list" value="<?php echo base_url('users/listing') ?>">

            <?php echo form_open("users/update", array('id'=>'edit-user-form', 'class'=>'m-t-25 card'), array('id'=>'AJAX_CALL_ONLY')); ?>
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit User</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <p class="c-black f-500 m-b-10 text-uppercase"><strong>Account Details</strong></p>

                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('username', set_value('username'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Username', 'username', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>

                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_password('password', set_value('password'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Password', 'password', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>

                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_password('retype_password', set_value('retype_password'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Retype Password', 'retype_password', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>

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

                        <div class="col-md-6">
                            <div class="form-group">
                                <p class="c-black f-500 m-b-10 text-uppercase"><strong>Personal Details</strong></p>

                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('firstname', set_value('firstname'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('First name', 'firstname', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>

                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('middlename', set_value('middlename'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Middle name', 'middlename', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>

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

                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Roles Details</strong></p>
                    <div class="row">
                        <div class="col-md-6 m-b-20">
                            <div class="pad-zero-right">
                                <div class="form-group fg-float form-group-validation">
                                    <?php echo form_label('Privilege', 'privilege', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                    <?php echo form_dropdown('privilege',
                                        $form['privileges_list'],
                                        set_value('privilege'), 'class="tag-select"'
                                    ) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 m-b-20">
                            <div class="pad-zero-right">
                                <div class="form-group fg-float form-group-validation">
                                    <?php echo form_label('Privilege Level', 'privilege_level', array('class'=>'c-black f-500 text-uppercase m-b-5')) ?>
                                    <?php echo form_dropdown('privilege_level',
                                        $form['privileges_levels_list'],
                                        set_value('privilege_level'), 'class="tag-select"'
                                    ) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="submit" class="btn btn-link">Update</button>
                    <button type="button" name="close" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>