<section id="content">
    <div class="container">

        <?php if(!is_null($this->session->contact)) : ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php echo $this->session->contact->message; ?>
            </div>
        <?php endif; ?>

        <div class="toolbar-group text-right">
            <a href="<?php echo base_url('contacts/trash'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-trash">&nbsp;</i>Trash</a>
        </div>

        <div class="card">
            <?php echo breadcrumbs(); ?>

            <div class="table-responsive">
                <table id="contact-table-command" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="contacts_id"        data-css-class="contacts_id" data-order="asc" data-visible="false" data-identifier="true">Contact ID</th>
                            <th data-column-id="contacts_firstname" data-css-class="contacts_firstname" data-order="asc">Name</th>
                            <th data-column-id="contacts_level"     data-css-class="contacts_level" data-order="asc">Level</th>
                            <th data-column-id="contacts_type"      data-css-class="contacts_type" data-order="asc">Type</th>
                            <th data-column-id="contacts_group"     data-css-class="contacts_group" data-order="asc">Group</th>
                            <th data-column-id="contacts_email"     data-css-class="contacts_email" data-order="asc">Email</th>
                            <th data-column-id="contacts_mobile"    data-css-class="contacts_mobile" data-order="asc">Mobile</th>
                            <th data-column-id="contacts_telephone" data-css-class="contacts_telephone" data-order="asc" data-visible="false">Telephone</th>
                            <th data-column-id="contacts_address"   data-css-class="contacts_address" data-sortable="false" data-visible="false">Address</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <button id="delete-contact-btn" title="Delete all selected Contacts" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-contact-btn" title="Add new Contact" class="btn btn-float bgm-green add-new m-btn" data-toggle="modal" href="#add-contact"><i class="zmdi zmdi-plus-square"></i></button>
</section>

<?php
$this->load->view('contacts/add');
$this->load->view('contacts/edit') ?>