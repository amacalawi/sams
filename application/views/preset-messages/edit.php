<div class="modal fade" id="edit-preset-message" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-fluid">
        <div class="modal-content">

            <?php echo form_open("schedules/preset-messages/update", array('id'=>'edit-preset-message-form', 'class'=>'m-t-25 card', 'method'=>'POST'), array('id'=>'AJAX_CALL_ONLY')); ?>
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Preset Message</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Preset Message Details</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="pad-zero-left">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('name', set_value('name'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Name', 'name', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default keys-add-stud_no">Add &lt;student number&gt;</button>
                    <button type="button" class="btn btn-default keys-add-stud_name">Add &lt;student name&gt;</button>
                    <button type="button" class="btn btn-default keys-add-date">Add &lt;Date&gt;</button>
                    <button type="button" class="btn btn-default keys-add-time">Add &lt;Time&gt;</button>
                    <button type="submit" name="submit" class="btn btn-link">Update</button>
                    <button type="button" name="close" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
