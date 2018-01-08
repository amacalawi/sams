<section id="content">
    <div class="container">
        <div class="card">
            <?php echo breadcrumbs('', 'Import Group') ?>
            <div class="card-body card-padding">
                <?php echo form_open_multipart("groups/import", array('id'=>'dropzone', 'class'=>'dropzone m-t-25')); ?>
                    <div class="fileinput fileinput-new fallback" data-provides="fileinput">
                        <span class="btn btn-primary btn-file m-r-10">
                            <span class="fileinput-new">Select file</span>
                            <span class="fileinput-exists">Change</span>
                            <div class="file-preview-other"></div>
                            <?php echo form_upload( 'file', set_value('file'), ['class'=>'file-input-field', 'accept'=>'.csv'] ) ?>
                        </span>
                        <span class="fileinput-filename"></span>
                        <a href="#" class="close fileinput-exists" data-dismiss="fileinput">&times;</a>
                        <button type="submit" class="btn btn-primary hidden">Submit</button>
                    </div>
                <?php echo form_close() ?>

            </div>
        </div>
    </div>
</section>