<section id="content">
    <div class="container">
        <?php if(!is_null($this->session->flashdata('message'))) : ?>
            <div class="alert alert-<?php echo $this->session->flashdata('message')['type'] ?>" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php echo $this->session->flashdata('message')['message']; ?>
            </div>
        <?php endif; ?>

        <div class="toolbar-group text-right">
            <?php if (isset($trash) && isset($trash['count'])): ?>
            <a href="<?php echo base_url('schoolyears/trash'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-trash">&nbsp;</i>Trashed (<span class="trash-count"><?php echo @$trash['count']; ?></span>)</a>
            <?php endif; ?>
        </div>

        <div class="card">
            <?php echo breadcrumbs('', 'All School Years'); ?>
            <div class="table-responsive">
                <table id="schoolyear-table-command" class="table table-condensed table-vmiddle table-hover" data-bootgrid data-bootgrid-options='{"url":"<?php echo base_url('schoolyears/listing'); ?>", "edit": "<?php echo base_url('schoolyears/edit/'); ?>", "remove": "<?php echo base_url('schoolyears/remove/'); ?>"}'>
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id" data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">ID</th>
                            <th data-column-id="name" data-css-class="name">Name</th>
                            <th data-column-id="code" data-css-class="code">Code</th>
                            <th data-column-id="years" data-css-class="years">Years</th>
                            <th data-column-id="status" data-css-class="status">Status</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
    <button id="delete-schoolyear-btn" title="Delete all selected School Years" class="btn btn-float bgm-red delete-all m-btn" data-fab-destroy><i class="zmdi zmdi zmdi-delete"></i></button>
    <a id="add-new-schoolyear-btn" title="Add new School Year" class="btn btn-float bgm-green add-new m-btn" data-toggle="modal" href="#add-schoolyear"><i class="zmdi zmdi-plus-square"></i></a>
</section>

<?php
$this->load->view('schoolyears/add');
$this->load->view('schoolyears/edit') ?>