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
            <a href="<?php echo base_url('enrollments/trash'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-trash">&nbsp;</i>Trashed (<span class="trash-count"><?php echo @$trash['count']; ?></span>)</a>
            <?php endif; ?>
        </div>

        <div class="card">
            <?php echo breadcrumbs('', 'All Enrollments'); ?>
            <div class="table-responsive">
                <table id="enrollment-table-command" class="table table-condensed table-vmiddle table-hover" data-bootgrid data-bootgrid-options='{"url":"<?php echo base_url('enrollments/listing/'); ?>", "edit": "<?php echo base_url('members/edit/'); ?>", "remove": "<?php echo base_url('enrollments/remove/'); ?>"}'>
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id" data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">ID</th>
                            <th data-column-id="member_id" data-css-class="member_id" data-order="asc" data-visible="false" data-identifier="true">Member ID</th>
                            <th data-column-id="name" data-css-class="name">Member</th>
                            <th data-column-id="levelsection" data-css-class="levelsection">Level / Section</th>
                            <th data-column-id="schoolyear" data-css-class="schoolyear">School Year</th>
                            <th data-column-id="status" data-css-class="status">Status</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
    <button id="delete-enrollment-btn" title="Delete all selected Enrollments" class="btn btn-float bgm-red delete-all m-btn" data-fab-destroy><i class="zmdi zmdi zmdi-delete"></i></button>
    <a id="add-new-enrollment-btn" title="Add new Enrollment" class="btn btn-float bgm-green add-new m-btn" data-toggle="modal" href="#add-enrollment"><i class="zmdi zmdi-plus-square"></i></a>
</section>

<?php
$this->load->view('enrollments/add');
$this->load->view('members/edit') ?>