<?php

$this->load->view('partials/header'); ?>

<?php
$this->load->view($Headers->Page); ?>
<!-- Login -->
<div class="lc-block toggled" id="l-login">
    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
        <div class="fg-line">
            <input type="text" class="form-control" placeholder="Username">
        </div>
    </div>

    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
        <div class="fg-line">
            <input type="password" class="form-control" placeholder="Password">
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="checkbox">
        <label>
            <input type="checkbox" value="">
            <i class="input-helper"></i>
            Keep me signed in
        </label>
    </div>

    <a href="" class="btn btn-login btn-danger btn-float"><i class="zmdi zmdi-arrow-forward"></i></a>

    <ul class="login-navigation">
        <li data-block="#l-register" class="bgm-red">Register</li>
        <li data-block="#l-forget-password" class="bgm-orange">Forgot Password?</li>
    </ul>
</div>

<!-- Register -->
<div class="lc-block" id="l-register">
    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="zmdi zmdi-account"></i></span>
        <div class="fg-line">
            <input type="text" class="form-control" placeholder="Username">
        </div>
    </div>

    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
        <div class="fg-line">
            <input type="text" class="form-control" placeholder="Email Address">
        </div>
    </div>

    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="zmdi zmdi-male"></i></span>
        <div class="fg-line">
            <input type="password" class="form-control" placeholder="Password">
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="checkbox">
        <label>
            <input type="checkbox" value="">
            <i class="input-helper"></i>
            Accept the license agreement
        </label>
    </div>

    <a href="" class="btn btn-login btn-danger btn-float"><i class="zmdi zmdi-arrow-forward"></i></a>

    <ul class="login-navigation">
        <li data-block="#l-login" class="bgm-green">Login</li>
        <li data-block="#l-forget-password" class="bgm-orange">Forgot Password?</li>
    </ul>
</div>

<!-- Forgot Password -->
<div class="lc-block" id="l-forget-password">
    <p class="text-left">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla eu risus. Curabitur commodo lorem fringilla enim feugiat commodo sed ac lacus.</p>

    <div class="input-group m-b-20">
        <span class="input-group-addon"><i class="zmdi zmdi-email"></i></span>
        <div class="fg-line">
            <input type="text" class="form-control" placeholder="Email Address">
        </div>
    </div>

    <a href="" class="btn btn-login btn-danger btn-float"><i class="zmdi zmdi-arrow-forward"></i></a>

    <ul class="login-navigation">
        <li data-block="#l-login" class="bgm-green">Login</li>
        <li data-block="#l-register" class="bgm-red">Register</li>
    </ul>
</div>
    <?php

$this->load->view('partials/footer');
 ?>