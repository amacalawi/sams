<section id="content">
    <div class="container">

        <?php $this->load->view('partials/messages'); ?>

        <div class="toolbar-module text-right">
            <a href="<?php echo base_url('modules'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-arrow-left">&nbsp;</i>Go Back</a>
        </div>

        <div class="card">
            <?php echo breadcrumbs('', 'All Trashed Modules'); ?>
            <div class="table-responsive">
                <table id="trashed-module-table-command" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id" data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Module ID</th>
                            <th data-column-id="name" data-css-class="name" data-order="asc">Module Name</th>
                            <th data-column-id="description" data-css-class="description" data-order="asc">Description</th>
                            <th data-column-id="slug" data-css-class="slug" data-order="asc">Slug</th>
                            <th data-column-id="level" data-css-class="level" data-order="asc">Level</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</section>