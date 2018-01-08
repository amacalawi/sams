<section id="content">
    <div class="container">
        <div class="card">

            <?php echo breadcrumbs('', 'All Gates'); ?>

            <div class="table-responsive">
                <table id="gate-table-command" class="table table-condensed table-hover table-vmiddle" data-bootgrid data-bootgrid-options='{"url":"<?php echo base_url('monitor/gates/listing'); ?>", "edit": "<?php echo base_url('monitor/gates/edit/'); ?>", "remove": "<?php echo base_url('monitor/gates/remove/'); ?>", "permanent_delete":true}'>
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id" data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Gate ID</th>
                            <th data-column-id="name" data-css-class="name" data-order="asc">Gate Name</th>
                            <th data-column-id="description" data-css-class="description" data-order="asc">Description</th>
                            <th data-column-id="code" data-css-class="code" data-order="asc">Code</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Actions</th>
                        </tr>
                    </thead>
                    <!-- <tbody><?php
                        foreach ($gates as $gate) { ?>
                            <tr>
                                <td><?php echo $gate->id ?></td>
                                <td><?php echo $gate->name ?></td>
                                <td><?php echo $gate->description ?></td>
                                <td><?php echo $gate->code ?></td>
                            </tr><?php
                        } ?>
                    </tbody> -->
                </table>
            </div>
        </div>
    </div>

    <button id="delete-gate-btn" type="button" title="Delete all selected Gates" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-gate-btn" type="button" title="Add new Gate" class="btn btn-float bgm-green m-btn" data-toggle="modal" href="#add-gate"><i class="zmdi zmdi-accounts-add"></i></button>

</section>

<?php $this->load->view('monitor/add_gate') ?>
<?php $this->load->view('monitor/edit_gate') ?>