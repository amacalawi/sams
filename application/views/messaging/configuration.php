<section id="content">
    <div class="container">

        <div class="row">
            <?php if(!is_null($this->session->flashdata('message'))) : ?>
                <div class="alert alert-<?php echo $this->session->flashdata('message')['type'] ?>" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    <?php echo $this->session->flashdata('message')['message']; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="card row">
            <?php echo breadcrumbs('', 'Configuration'); ?>

            <div class="col-sm-12">
                <p class="text-muted">Select a Sending Configuration</p>
                <?php echo form_open("messaging/post-configuration", array('role'=>"form", 'id'=>'messaging-config-form', 'method'=>'POST')); ?>
                    <div class="form-group">
                        <!-- <hr class="m-t-10 m-b-15"/> -->
                        <div class="row">
                            <div class="col-lg-4 col-md-12">
                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <?php foreach ($form['dtr_sending_config'] as $conf):
                                            $is_conf_null[] = $conf->enabled ?>
                                            <div class="radio m-b-15">
                                                <label>
                                                    <input name="config" value="<?php echo $conf->config; ?>" type="radio" <?php echo (1==$conf->enabled) ? 'checked="checked"' : '' ?>>
                                                    <i class="input-helper"></i>
                                                    <?php echo $conf->description; ?>
                                                </label>
                                            </div>
                                        <?php endforeach; ?>
                                        <div class="radio m-b-15">
                                            <label>
                                                <input name="config" value="0" type="radio" <?php echo (!in_array(1, $is_conf_null)) ? 'checked="checked"' : '' ?>>
                                                <i class="input-helper"></i>
                                                Do not send message.
                                            </label>
                                        </div>
                                    </div>
                                    <?php echo form_button(array('name'=>'submit', 'id'=>'submit', 'type'=>'submit'), 'Save', 'class="btn btn-success"') ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php echo form_close(); ?>
            </div>

        </div>

    </div>
</section>