<div class="modal fade" id="edit-announcement" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="announcement-edit-table-command-list" value="<?php echo base_url('monitor/announcement_listing') ?>">


            <?php echo form_open("monitor/update_announcement", array('id'=>'edit-announcement-form', 'class'=>'m-t-25 card'), array('announcement_id'=>'AJAX_CALL_ONLY')); ?>
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Announcement</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p class="c-black f-500 m-b-10 text-uppercase"><strong>Announcement Details</strong></p>

                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('announcement_name', set_value('announcement_name'), array('class'=>'form-control fg-input')) ?>
                                        </div>
                                        <?php echo form_label('Announcement Name', 'announcement_name', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>

                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                        <?php echo form_textarea(['name'=>'announcement_text', 'rows'=>5], set_value('announcement_text'), array('class'=>'form-control auto-size')) ?>
                                        </div>
                                        <?php echo form_label('Announcement Description', 'announcement_text', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>

                                

                            </div>
                        </div>

                        
                </div>
                <div class="modal-footer">
                    <?php echo form_button(array('name'=>'submit', 'id'=>'submit', 'type'=>'submit'), 'Update', 'class="btn btn-link"') ?>
                    <?php echo form_button('close', 'Close', 'class="btn btn-link" data-dismiss="modal"') ?>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>