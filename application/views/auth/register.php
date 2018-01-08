<div class="lc-block" id="l-register">
    <?php echo form_open("auth/register", array('id'=>'register-form', 'method'=>'POST')); ?>
        <div class="form-group-validation">
            <div class="input-group m-b-20">
                <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
                <div class="fg-line">
                    <?php echo form_input('username', set_value('username'), array('class'=>'form-control fg-input', 'placeholder'=>"Username")) ?>
                </div>
            </div>
        </div>

        <div class="form-group-validation">
            <div class="input-group m-b-20">
                <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
                <div class="fg-line">
                    <?php echo form_input('email', set_value('email'), array('class'=>'form-control fg-input', 'placeholder'=>"Email")) ?>
                </div>
            </div>
        </div>

        <div class="form-group-validation">
            <div class="input-group m-b-20">
                <span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
                <div class="fg-line">
                    <?php echo form_password('password', set_value('password'), array('class'=>'form-control fg-input', 'placeholder'=>"Password")) ?>
                </div>
            </div>
        </div>

        <div class="form-group-validation">
            <div class="input-group m-b-20">
                <span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
                <div class="fg-line">
                    <?php echo form_password('retype_password', set_value('retype_password'), array('class'=>'form-control fg-input', 'placeholder'=>"Retype Password")) ?>
                </div>
            </div>
        </div>

        <button type="submit" name="submit" class="btn btn-login btn-danger btn-float"><i class="zmdi zmdi-arrow-forward"></i></button>
    <?php echo form_close() ?>

    <ul class="login-navigation">
        <li data-block="#l-login" class="bgm-green">Login</li>
        <li data-block="#l-forget-password" class="bgm-orange">Forgot Password?</li>
    </ul>

</div>