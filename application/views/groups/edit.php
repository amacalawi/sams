<div class="modal fade" id="edit-group" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-fluid">
        <div class="modal-content">

            <input id="group-edit-table-command-list" type="hidden" value="<?php echo base_url('members/listing') ?>">
            <input id="group-table-command-update-button" type="hidden" value="<?php echo base_url('groups/update') ?>">
            <input id="members-table-command-update-button" type="hidden" value="<?php echo base_url('members/update') ?>">

            <?php echo form_open("groups/update", array('id'=>'edit-group-form', 'class'=>'m-t-25 card', 'method'=>'POST'), array('groups_id'=>'AJAX_CALL_ONLY')); ?>
                <div class="card-header bgm-amber">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Edit Group</h2>
                </div>
                <div class="modal-body">
                    <p class="c-black f-500 m-b-10 text-uppercase"><strong>Group Details</strong></p>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="pad-zero-left">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('groups_name', set_value('groups_name'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Group name', 'groups_name', array('class'=>'fg-label')) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-5">
                                    <div class="pad-zero-right">
                                        <div class="form-group fg-float form-group-validation">
                                            <div class="fg-line">
                                                <?php echo form_input('groups_code', set_value('groups_code'), array('class'=>'form-control fg-input')) ?>
                                            </div>
                                            <?php echo form_label('Group code', 'groups_code', array('class'=>'fg-label')) ?>
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
                                        <?php echo form_textarea(['name'=>'groups_description', 'rows'=>2], set_value('groups_description'), array('class'=>'form-control auto-size')) ?>
                                    </div>
                                    <?php echo form_label('Group description', 'groups_description', array('class'=>'fg-label')) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr/>
                <div class="row">
                    <div class="col-md-12">
                        <div class="modal-body">
                            <p class="c-black f-500 m-b-10 text-uppercase"><strong>Current Members</strong></p>
                            <small>List of Members in this Group</small>
                        </div>
                        <div class="row">
                            <div class="col-md-12 m-b-0">
                                <div class="table-responsive">
                                    <table id="current-members-group" class="members-table-command table table-condensed table-vmiddle">
                                        <thead>
                                            <tr>
                                                <th data-column-id="count_id" data-sortable="false" data-type="numeric">#</th>
                                                <th data-column-id="id" data-identifier="true" data-order="asc" data-visible="false">Members ID</th>
                                                <th data-column-id="fullname">Name</th>
                                                <th data-column-id="level">Level</th>
                                                <th data-column-id="groups">Current Group</th>
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
                </div>

                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <div class="modal-body">
                            <p class="c-black f-500 m-b-10 text-uppercase"><strong>Availale List</strong></p>
                            <small>List of Members to Add in this Group</small>
                        </div>
                        <div class="row">
                            <div class="col-md-12 m-b-0">
                                <div class="table-responsive">
                                    <table id="available-members-group" class="members-table-command table table-condensed table-vmiddle">
                                        <thead>
                                            <tr>
                                                <th data-column-id="count_id" data-sortable="false" data-type="numeric">#</th>
                                                <th data-column-id="id" data-identifier="true" data-order="asc" data-visible="false">Members ID</th>
                                                <th data-column-id="fullname">Name</th>
                                                <th data-column-id="level">Level</th>
                                                <th data-column-id="groups">Current Group</th>
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
                </div>

                <div class="modal-footer">
                    <button type="submit" name="groups_submit" class="btn btn-link">Update</button>
                    <button type="button" name="groups_close" class="btn btn-link" data-dismiss="modal">Close</button>
                </div>
            <?php echo form_close() ?>
        </div>
    </div>
</div>