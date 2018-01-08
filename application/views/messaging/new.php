<section id="content">
    <div class="container">
        <div class="block-header">
            <h2>Create New Message</h2>
        </div>
        <?php echo form_open("messaging/bulk-send", array('id'=>'send-new-message-form', 'class'=>'card wall-posting')); ?>
            <div class="card-body card-padding">
                <div class="pad-zero-right">
                    <div id="phone-field-container" class="form-group form-group-validation">
                        <label for="msisdn-input">Mobile Phone</label>
                        <select id="msisdn-input" class="input-selectize" name="msisdn[members][]" multiple>
                            <?php foreach ($form['contacts'] as $contact) {
                                echo "<option value='$contact->id'><strong class='name'>$contact->msisdn</strong><div>$contact->fullname</div></option>";
                            } ?>
                        </select>
                    </div>
                    <div class="form-group form-group-validation">
                        <label for="msisdn-group-input">Send to Group</label>
                        <select id="msisdn-group-input" class="input-selectize" name="msisdn[groups][]" data-selectize-ajax="<?php echo base_url('messaging/groups'); ?>" multiple>
                           <!--  <?php foreach ($form['contacts'] as $contact) {
                                echo "<option value='$contact->id'><strong class='name'>$contact->msisdn</strong><div>$contact->fullname</div></option>";
                            } ?> -->
                        </select>
                    </div>
                </div>
            </div>
            <hr>
            <div class="card-body card-padding">
                <div class="form-group-validation">
                    <textarea id="new-sms-textarea" maxlength="320" class="wp-text auto-size" name="body" data-auto-size data-target="#textarea-count" placeholder="Write SMS..."></textarea>
                </div>
            </div>

            <ul class="list-unstyled clearfix wpb-actions">
                <li class="pull-left">
                    <small class="btn btn-link" id="textarea-count">320</small>
                </li>
                <li>
                    <button id="insert-template-message" type="button" class="btn btn-link" data-toggle="modal" href="#send-templates-modal">Templates...</button>
                </li>
                <li class="pull-right">
                    <?php echo form_button(array('name'=>'submit', 'id'=>'submit', 'type'=>'submit'), 'Send', 'class="btn btn-primary btn-sm"') ?>
                    <button type="button" id="send-later-btn-trigger" title="Send this message later" class="btn btn-default" data-toggle="modal" href="#send-later-modal">Send Later...</button>
                </li>
            </ul>
        <?php echo form_close(); ?>
    </div>
</section>
<?php $this->load->view('messaging/send-templates'); ?>
<?php $this->load->view('messaging/send-later'); ?>