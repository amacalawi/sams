<section id="content">
    <div class="container">
        <?php if(!is_null($this->session->flashdata('message'))) : ?>
            <div class="alert alert-<?php echo $this->session->flashdata('message')['type']; ?>" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php echo $this->session->flashdata('message')['message']; ?>
            </div>
        <?php endif; ?>

        <div class="toolbar-group text-right">
            <a href="<?php echo base_url('groups'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-arrow-left">&nbsp;</i>Go Back</a>
        </div>

        <div class="card">
            <?php echo breadcrumbs('', 'All Trashed Groups'); ?>
            <div class="table-responsive">
                <table id="trashed-group-table-command" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="groups_id"          data-css-class="groups_id" data-order="asc" data-visible="false" data-identifier="true">Group ID</th>
                            <th data-column-id="groups_name"        data-css-class="groups_name" data-order="asc">Group Name</th>
                            <th data-column-id="groups_description" data-css-class="groups_description" data-order="asc">Description</th>
                            <th data-column-id="groups_code"        data-css-class="groups_code" data-order="asc">Code</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</section>