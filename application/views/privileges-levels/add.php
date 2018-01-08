<div class="modal fade" id="add-privileges-level" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="privileges-level-add-table-command-list" value="<?php echo base_url('privileges-levels/listing') ?>">

            <?php echo form_open("privileges-levels/add", array('id'=>'add-new-privileges-level-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New Privilege</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Privilege Details</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="pad-zero-left">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('name', set_value('name'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Privilege Level name', 'name', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('code', set_value('code'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Privilege Level code', 'code', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pad-zero-right">
                                <div class="form-group fg-float form-group-validation">
                                    <div class="fg-line">
                                        <?php echo form_textarea(['name'=>'description', 'rows'=>2], set_value('description'), array('class'=>'form-control auto-size')) ?>
                                    </div>
                                    <?php echo form_label('Description', 'description', array('class'=>'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Modules</strong></p>
                    <!-- <div class="row">
                        <div class="col-md-12 m-b-20">
                            <div class="pad-zero-right">
                                <div class="lists">
                                    <div class="list-content">
                                        <?php foreach ($form['modules_list'] as $module): ?>
                                            <div class="list-content-item">
                                                <?php echo $module; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="row">
                        <div class="col-md-12 m-b-20">
                            <div class="pad-zero-right">
                                <div class="form-group fg-float form-group-validation">
                                    <?php echo form_dropdown('modules[]',
                                        $form['modules_list'],
                                        set_value('modules'), 'class="tag-select" multiple'
                                    ) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-link">Add</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>