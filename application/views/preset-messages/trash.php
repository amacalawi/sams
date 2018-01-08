<section id="content">
    <div class="container">
        <?php if(!is_null($this->session->flashdata('message'))) : ?>
            <div class="alert alert-<?php echo $this->session->flashdata('message')['type']; ?>" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php echo $this->session->flashdata('message')['message']; ?>
            </div>
        <?php endif; ?>

        <div class="toolbar-group text-right">
            <a href="<?php echo base_url('schedules/preset-messages'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-arrow-left">&nbsp;</i>Go Back</a>
        </div>

        <div class="card">
            <?php echo breadcrumbs('', 'All Trashed Preset-messages'); ?>
            <div class="table-responsive">
                <table id="trashed-message-templates-table-command" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id" data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Preset Msg ID</th>
                            <th data-column-id="name" data-css-class="name" data-order="asc">Name</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</section>