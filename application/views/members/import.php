<section id="content">
      <div class="container">
     

        <div class="card">
            <?php echo breadcrumbs('', 'Import Member') ?>
            <div class="card-body card-padding">

                <?php echo form_open_multipart("members/import", array('id'=>'dropzone', 'class'=>'dropzone m-t-25', 'accept-charset'=>'utf-8')); ?>
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
                <table id="member-import-table-command" class="hidden table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id"        data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Member ID</th>
                            <th data-column-id="fullname" data-css-class="fullname" data-order="asc">Name</th>
                            <th data-column-id="gender"     data-css-class="gender" data-order="asc">Gender</th>
                            <th data-column-id="level"     data-css-class="level" data-order="asc">Level</th>
                            <th data-column-id="type"      data-css-class="type" data-order="asc">Type</th>
                            <th data-column-id="groups"     data-css-class="groups" data-order="asc">Group</th>
                            <th data-column-id="email"     data-css-class="email" data-order="asc">Email</th>
                            <th data-column-id="msisdn"    data-css-class="msisdn" data-order="asc">Mobile</th>
                            <th data-column-id="telephone" data-css-class="telephone" data-order="asc">Telephone</th>
                            <th data-column-id="address"   data-css-class="address" data-sortable="false">Address</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
</section>