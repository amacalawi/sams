<section id="content">
    <div class="container">
        <?php if(!is_null($this->session->flashdata('message'))) : ?>
            <div class="alert alert-<?php echo $this->session->flashdata('message')['type'] ?>" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php echo $this->session->flashdata('message')['message']; ?>
            </div>
        <?php endif; ?>

        <div class="toolbar-group text-right">
            <a href="<?php echo base_url('messaging/templates/trash'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-trash">&nbsp;</i>Trashed (<span class="trash-count"><?php echo @$trash['count'] ?></span>)</a>
        </div>

        <div class="card">

            <?php echo breadcrumbs('', 'All Message Templates'); ?>

            <div class="table-responsive">
                <table id="message-templates-command" class="table table-condensed table-hover table-vmiddle">
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id" data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Template ID</th>
                            <th data-column-id="name" data-css-class="name" data-order="asc">Name</th>
                            <th data-column-id="code" data-css-class="code" data-order="asc">Code</th>
                            <th data-column-id="type" data-css-class="type" data-order="asc" data-visible="false">Type</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <button id="delete-template-btn" type="button" title="Delete all selected Levels" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-template-btn" type="button" title="Add new Level" class="btn btn-float bgm-green m-btn" data-toggle="modal" href="#add-message-template"><i class="zmdi zmdi-accounts-add"></i></button>

</section>

<?php $this->load->view('message-templates/add') ?>
<?php $this->load->view('message-templates/edit') ?>