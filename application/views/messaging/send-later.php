<div class="modal fade" id="send-later-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <?php echo form_open("messaging/bulk-send/later", array('id'=>'send-later-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Message Scheduler</h2>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <!-- <hr class="m-t-10 m-b-15"/> -->
                        <div class="row">
                            <div class="col-lg-12 col-md-12">
                                <p class="c-black f-500 m-b-10 text-uppercase"><strong>Send at</strong></p>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group form-group-validation">
                                            <div class="dtp-container fg-line">
                                                <?php echo form_input('send_at_date', set_value('send_at_date'), array('id'=>'send_at_date', 'class'=>'form-control date-picker', 'placeholder'=>'MM/DD/YYYY')) ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group form-group-validation">
                                            <div class="dtp-container fg-line">
                                                <?php echo form_input('send_at_time', set_value('send_at_time'), array('id'=>'send_at_time', 'class'=>'form-control time-picker', 'placeholder'=>'hh:mm')) ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <?php echo form_button(array('name'=>'submit', 'id'=>'submit', 'type'=>'submit'), 'Send Later', 'class="btn btn-link"') ?>
                    <?php echo form_button('close', 'Cancel', 'id="send-later-close-btn" class="btn btn-link" data-dismiss="modal"') ?>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>