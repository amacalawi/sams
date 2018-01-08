<div class="modal fade" id="add-type" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <input type="hidden" id="type-add-table-command-list" value="<?php echo base_url('contacts/listing') ?>">

            <?php echo form_open("types/add", array('id'=>'add-new-type-form', 'class'=>'m-t-25 card')); ?>
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>New Type</h2>
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
                <!-- <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Type Contacts</strong></p>
                </div>
                <div class="row">
                    <div class="col-sm-12 m-b-0">
                        <div class="table-responsive">
                            <table id="contacts-table-command-add" class="contacts-table-command table table-condensed table-vmiddle">
                                <thead>
                                    <tr>
                                        <th data-column-id="count_id" data-type="numeric" data-sortable="false">#</th>
                                        <th data-column-id="id" data-order="asc" data-identifier="true" data-visible="false">Members ID</th>
                                        <th data-column-id="fullname">Name</th>
                                        <th data-column-id="type" data-visible="false">Type</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php /*<!--/-no-data-available-/-->*/ ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div> -->
                <div class="modal-footer">
                    <button type="submit" class="btn btn-link">Add</button>
                    <button type="button" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>
