<section id="content">
    <div class="container">

        <?php $this->load->view('partials/messages') ?>

        <div class="toolbar-group text-right">
            <a href="<?php echo base_url('privileges/trash'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-trash">&nbsp;</i>Trashed (<span class="trash-count"><?php echo $trash['count'] ?></span>)</a>
        </div>

        <div class="card"><?php
            echo breadcrumbs(); ?>
            <div class="table-responsive">
                <table id="privilege-table-command" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id" data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Privilege ID</th>
                            <th data-column-id="name" data-css-class="name" data-order="asc">Name</th>
                            <th data-column-id="code" data-css-class="code" data-order="asc">Code</th>
                            <th data-column-id="description" data-css-class="description" data-order="asc">Description</th>
                            <th data-column-id="level" data-css-class="level" data-order="asc">Level</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <button id="delete-privilege-btn" title="Delete all selected Privileges" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-privilege-btn" title="Add new Privilege" class="btn btn-float bgm-green add-new m-btn" data-toggle="modal" href="#add-privilege"><i class="zmdi zmdi-plus-square"></i></button>
</section>

<?php
$this->load->view('privileges/add');
$this->load->view('privileges/edit'); ?>