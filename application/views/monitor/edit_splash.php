<div class="modal fade" id="edit-splash" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="splash-edit-table-command-list" value="<?php echo base_url('monitor/splash_listing') ?>">


            <?php echo form_open("monitor/update_splash_source/", array('id'=>'edit-splash-form', 'class'=>'m-t-25 card'), array('id'=>'AJAX_CALL_ONLY')); ?>
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit splash</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <p class="c-black f-500 m-b-10 text-uppercase"><strong>splash Details</strong></p>

                                <div class="pad-zero-right">
                                    <div class="form-group fg-float form-group-validation">
                                        <div class="fg-line">
                                            <?php echo form_input('video_titles', set_value('video_titles'), array('class'=>'form-control fg-input', 'id' => 'video_titles')) ?>
                                        </div>
                                        <?php echo form_label('Video Title', 'video_titles', array('class'=>'fg-label')) ?>
                                    </div>
                                </div>

                                <div class="pad-zero-right">
                                    <div class="form-group form-group-validation">
                                            <p class="c-black f-500 m-b-10">Splash Page Source</p>
                                            
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <span class="btn bgm-green btn-file m-r-10">
                                            <span class="fileinput-new">Select File</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" id="splash-sources">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput">&times;</a>
                                            </div>                     
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