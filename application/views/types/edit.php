<div class="modal fade" id="edit-type" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-fluid">
        <div class="modal-content">

            <input id="type-edit-table-command-list" type="hidden" value="<?php echo base_url('contacts/listing') ?>">
            <input id="type-table-command-update-button" type="hidden" value="<?php echo base_url('types/update') ?>">
            <input id="contacts-table-command-update-button" type="hidden" value="<?php echo base_url('contacts/update') ?>">

            <?php echo form_open("types/update", array('id'=>'edit-type-form', 'class'=>'m-t-25 card', 'method'=>'POST'), array('types_id'=>'AJAX_CALL_ONLY')); ?>
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Type</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Type Details</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="pad-zero-left">
                                        <div class="form-type fg-float form-type-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('types_name', set_value('types_name'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Type name', 'types_name', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="pad-zero-right">
                                        <div class="form-type fg-float form-type-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('types_code', set_value('types_code'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Type code', 'types_code', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="pad-zero-right">
                                <div class="form-type fg-float form-type-validation">
                                    <div class="fg-line">
                                        <?php echo form_textarea(['name'=>'types_description', 'rows'=>2], set_value('types_description'), array('class'=>'form-control auto-size')) ?>
                                    </div>
                                    <?php echo form_label('Type description', 'types_description', array('class'=>'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<hr>-->
                <!-- <div class="row">
                    <div class="col-md-12">
                        <div class="modal-body">
                            <p class="c-black f-500 m-b-10 text-uppercase"><strong>Type Contacts List</strong></p>
                            <small>List of Contacts to Add / Remove in this Type</small>
                        </div>
                        <div class="row">
                            <div class="col-md-12 m-b-0">
                                <div class="table-responsive">
                                    <table id="contacts-table-command-edit" class="contacts-table-command table table-condensed table-vmiddle">
                                        <thead>
                                            <tr>
                                                <th data-column-id="count_id" data-sortable="false" data-type="numeric">#</th>
                                                <th data-column-id="id" data-identifier="true" data-order="asc" data-visible="false">Contacts ID</th>
                                                <th data-column-id="fullname">Name</th>
                                                <th data-column-id="type" data-visible="false">Type</th>
                                                <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php /*<!--/-no-data-available-/-->*/ ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="modal-footer">
                    <button type="submit" name="types_submit" class="btn btn-link">Update</button>
                    <button type="button" name="types_close" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
