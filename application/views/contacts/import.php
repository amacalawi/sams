<section id="content">
    <div class="container">
        <div class="card">
            <?php echo breadcrumbs('', 'Import Contact') ?>
            <div class="card-body card-padding">

                <?php echo form_open_multipart("contacts/import", array('id'=>'dropzone', 'class'=>'dropzone m-t-25')); ?>
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

            <div class="table-responsive">
                <table id="contact-import-table-command" class="hidden table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="contacts_id"        data-css-class="contacts_id" data-order="asc" data-visible="false" data-identifier="true">Contact ID</th>
                            <th data-column-id="contacts_firstname" data-css-class="contacts_firstname" data-order="asc">Name</th>
                            <th data-column-id="contacts_level"     data-css-class="contacts_level" data-order="asc">Level</th>
                            <th data-column-id="contacts_type"      data-css-class="contacts_type" data-order="asc">Type</th>
                            <th data-column-id="contacts_group"     data-css-class="contacts_group" data-order="asc">Group</th>
                            <th data-column-id="contacts_email"     data-css-class="contacts_email" data-order="asc">Email</th>
                            <th data-column-id="contacts_mobile"    data-css-class="contacts_mobile" data-order="asc">Mobile</th>
                            <th data-column-id="contacts_telephone" data-css-class="contacts_telephone" data-order="asc">Telephone</th>
                            <th data-column-id="contacts_address"   data-css-class="contacts_address" data-sortable="false">Address</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
</section>