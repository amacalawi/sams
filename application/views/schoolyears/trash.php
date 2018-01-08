<section id="content">
    <div class="container">
        <?php if(!is_null($this->session->schoolyear)) : ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php echo $this->session->schoolyear->message; ?>
            </div>
        <?php endif; ?>

        <div class="toolbar-group text-right">
            <a href="<?php echo base_url('schoolyears'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-arrow-left">&nbsp;</i>Go Back</a>
        </div>

        <div class="card">
            <?php echo breadcrumbs(); ?>
            <div class="table-responsive">
                <table id="trashed-schoolyear-table-command" class="table table-condensed table-vmiddle table-hover" data-trash-bootgrid data-bootgrid-options='{"url":"<?php echo base_url('schoolyears/listing'); ?>", "restore": "<?php echo base_url('schoolyears/restore/'); ?>", "remove": "<?php echo base_url('schoolyears/remove/'); ?>"}'>
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id"        data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">School Year ID</th>
                            <th data-column-id="name" data-css-class="name" data-order="asc">Name</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</section>