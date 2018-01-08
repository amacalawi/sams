<section id="content">
    <div class="container">
        <div class="card">

            <?php echo breadcrumbs(); ?>

            <div class="table-responsive">
                <table id="level-table-command" class="table table-condensed table-hover table-vmiddle">
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="levels_id"          data-css-class="levels_id" data-order="asc" data-visible="false" data-identifier="true">Level ID</th>
                            <th data-column-id="levels_name"        data-css-class="levels_name" data-order="asc">Level Name</th>
                            <th data-column-id="levels_description" data-css-class="levels_description" data-order="asc">Description</th>
                            <th data-column-id="levels_code"        data-css-class="levels_code" data-order="asc">Code</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody><?php
                        foreach ($levels as $level) { ?>
                            <tr>
                                <td><?php echo $level->levels_id ?></td>
                                <td><?php echo $level->levels_name ?></td>
                                <td><?php echo $level->levels_description ?></td>
                                <td><?php echo $level->levels_code ?></td>
                            </tr><?php
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <button id="delete-level-btn" type="button" title="Delete all selected Levels" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-level-btn" type="button" title="Add new Level" class="btn btn-float bgm-green m-btn" data-toggle="modal" href="#add-level"><i class="zmdi zmdi-accounts-add"></i></button>

</section>

<?php $this->load->view('levels/add') ?>
<?php $this->load->view('levels/edit') ?>