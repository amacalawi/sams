<div class="modal fade" id="add-user" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <?php echo form_open("users/add", array('id'=>'add-new-user-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New User</h2>
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
                    <?php echo form_button(array('name'=>'submit', 'id'=>'submit', 'type'=>'submit'), 'Add', 'class="btn btn-link"') ?>
                    <?php echo form_button('close', 'Close', 'class="btn btn-link" data-dismiss="modal"') ?>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>