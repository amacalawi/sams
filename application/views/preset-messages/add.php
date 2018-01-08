<div class="modal fade" id="add-preset-message" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <?php echo form_open("messaging/templates/add", array('id'=>'add-new-preset-message-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New Preset Message</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Preset Message Details</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <div class="btn-block">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="pad-zero-left">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_textarea(['name'=>'name', 'rows'=>2], set_value('name'), array('class'=>'form-control auto-size')) ?>
                                            </div>
                                            <?php echo form_label('Message', 'name', array('class'=>'fg-label')) ?>
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
                    <button type="submit" class="btn btn-link">Add</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>