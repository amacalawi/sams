<section id="content">
    <div class="container">

        <?php $this->load->view('partials/messages'); ?>

        <div class="toolbar-privileges-level text-right">
            <a href="<?php echo base_url('privileges-levels'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-arrow-left">&nbsp;</i>Go Back</a>
        </div>

        <div class="card">
            <?php echo breadcrumbs('', 'All Trashed Privileges Levels'); ?>
            <div class="table-responsive">
                <table id="trashed-privileges-level-table-command" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id" data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Privileges Level ID</th>
                            <th data-column-id="name" data-css-class="name" data-order="asc">Privileges Level Name</th>
                            <th data-column-id="description" data-css-class="description" data-order="asc">Description</th>
                            <th data-column-id="code" data-css-class="code" data-order="asc">Slug</th>
                            <th data-column-id="modules" data-css-class="modules" data-order="asc">Modules</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</section>