<section id="content">
    <div class="container">
        <?php if(!is_null($this->session->member)) : ?>
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php echo $this->session->member->message; ?>
            </div>
        <?php endif; ?>

        <div class="toolbar-group text-right">
            <a href="<?php echo base_url('members'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-arrow-left">&nbsp;</i>Go Back</a>
        </div>

        <div class="card">
            <?php echo breadcrumbs(); ?>
            <div class="table-responsive">
                <table id="trashed-member-table-command" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id"        data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Member ID</th>
                            <th data-column-id="fullname" data-css-class="fullname" data-order="asc">Name</th>
                            <th data-column-id="level"     data-css-class="level" data-order="asc">Level</th>
                            <th data-column-id="type"      data-css-class="type" data-order="asc">Type</th>
                            <th data-column-id="groups"     data-css-class="groups" data-order="asc">Group</th>
                            <th data-column-id="email"     data-css-class="email" data-order="asc">Email</th>
                            <th data-column-id="msisdn"    data-css-class="msisdn" data-order="asc">Mobile</th>
                            <th data-column-id="telephone" data-css-class="telephone" data-order="asc" data-visible="false">Telephone</th>
                            <th data-column-id="address"   data-css-class="address" data-sortable="false" data-visible="false">Address</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

    </div>
</section>