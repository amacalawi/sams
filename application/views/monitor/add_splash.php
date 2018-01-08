<div class="modal fade" id="add-splash" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <form enctype="multipart/form-data" id="add-new-splash-form" class="m-t-25 card" method="post">
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New Splash Page</h2>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">

                                <div class="pad-zero-right">
                                    <div class="form-group form-group-validation">   
                                        <p class="c-black f-500 m-b-0">Splash Page Title</p> 
                                        <div class="fg-line">
                                            <?php echo form_input('video_title', set_value('video_title'), array('class'=>'form-control fg-input', 'id' => 'video_title')) ?>
                                        </div>
                                    </div>
                                </div>             

                                <div class="pad-zero-right">
                                    <div class="form-group form-group-validation">
                                            <p class="c-black f-500 m-b-10">Splash Page Source</p>
                                            
                                            <div class="fileinput fileinput-new" data-provides="fileinput">
                                                <span class="btn bgm-green btn-file m-r-10">
                                            <span class="fileinput-new">Select File</span>
                                            <span class="fileinput-exists">Change</span>
                                            <input type="file" id="splash-source">
                                        </span>
                                        <span class="fileinput-filename"></span>
                                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput">&times;</a>
                                            </div>                     
                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer">
                    <?php echo form_button(array('name'=>'submit', 'id'=>'submit', 'type'=>'submit'), 'Add', 'class="btn btn-link"') ?>
                    <?php echo form_button('close', 'Close', 'class="btn btn-link" data-dismiss="modal"') ?>
                </div>
            <?php echo form_close() ?>

            
        </div>
    </div>
</div>