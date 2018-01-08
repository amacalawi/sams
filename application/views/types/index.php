<section id="content">
    <div class="container">
        <div class="card">

            <?php echo breadcrumbs(); ?>

            <div class="table-responsive">
                <table id="type-table-command" class="table table-condensed table-hover table-vmiddle">
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="types_id"          data-css-class="types_id" data-order="asc" data-visible="false" data-identifier="true">Type ID</th>
                            <th data-column-id="types_name"        data-css-class="types_name" data-order="asc">Type Name</th>
                            <th data-column-id="types_description" data-css-class="types_description" data-order="asc">Description</th>
                            <th data-column-id="types_code"        data-css-class="types_code" data-order="asc">Code</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Actions</th>
                        </tr>
                    </thead>
                    <tbody><?php
                        foreach ($types as $type) { ?>
                            <tr>
                                <td><?php echo $type->types_id ?></td>
                                <td><?php echo $type->types_name ?></td>
                                <td><?php echo $type->types_description ?></td>
                                <td><?php echo $type->types_code ?></td>
                            </tr><?php
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <button id="delete-type-btn" type="button" title="Delete all selected Types" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-type-btn" type="button" title="Add new Type" class="btn btn-float bgm-green m-btn" data-toggle="modal" href="#add-type"><i class="zmdi zmdi-accounts-add"></i></button>

</section>

<?php $this->load->view('types/add') ?>
<?php $this->load->view('types/edit') ?>