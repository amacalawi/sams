<section id="content">
    <div class="container">
        <div class="card">

            <?php echo breadcrumbs('', 'All Devices'); ?>

            <div class="table-responsive">
                <table id="device-table-command" class="table table-condensed table-hover table-vmiddle" data-bootgrid data-bootgrid-options='{"url":"<?php echo base_url('monitor/devices/listing'); ?>", "edit": "<?php echo base_url('monitor/devices/edit/'); ?>", "remove": "<?php echo base_url('monitor/devices/remove/'); ?>", "permanent_delete":true}'>
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id" data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Device ID</th>
                            <th data-column-id="name" data-css-class="name" data-order="asc">Device Name</th>
                            <th data-column-id="description" data-css-class="description" data-order="asc">Description</th>
                            <th data-column-id="code" data-css-class="code" data-order="asc">Code</th>
                            <th data-column-id="gate" data-css-class="gate" data-order="asc">Gate</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Actions</th>
                        </tr>
                    </thead>
                    <!-- <tbody><?php
                        foreach ($devices as $device) { ?>
                            <tr>
                                <td><?php echo $device->id ?></td>
                                <td><?php echo $device->name ?></td>
                                <td><?php echo $device->description ?></td>
                                <td><?php echo $device->code ?></td>
                            </tr><?php
                        } ?>
                    </tbody> -->
                </table>
            </div>
        </div>
    </div>

    <button id="delete-device-btn" type="button" title="Delete all selected Devices" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-device-btn" type="button" title="Add new Device" class="btn btn-float bgm-green m-btn" data-toggle="modal" href="#add-device"><i class="zmdi zmdi-accounts-add"></i></button>

</section>

<?php $this->load->view('monitor/add_device') ?>
<?php $this->load->view('monitor/edit_device') ?>