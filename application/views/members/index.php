<section id="content">
    <div class="container">
        <?php if(!is_null($this->session->flashdata('message'))) : ?>
            <div class="alert alert-<?php echo $this->session->flashdata('message')['type'] ?>" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php echo $this->session->flashdata('message')['message']; ?>
            </div>
        <?php endif; ?>

        <div class="toolbar-group text-right">
            <a href="<?php echo base_url('members/trash'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-trash">&nbsp;</i>Trashed (<span class="trash-count"><?php echo $trash['count'] ?></span>)</a>
        </div>

        <div class="card">
            <?php echo breadcrumbs(); ?>

            <div class="table-responsive">
                <table id="member-table-command" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id"        data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">ID</th>
                            <th data-column-id="avatar"        data-css-class="avatar" data-order="asc" data-visible="true" data-identifier="true">Photo</th>
                            <th data-column-id="stud_no" data-css-class="stud_no" data-order="asc" data-visible="true">Student No.</th>
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
                    <!-- <tbody>
                        <?php foreach ($members as $i => $member) { ?>
                            <tr>
                                <td><?php echo $i+1; ?></td>
                                <td><?php echo $member->id; ?></td>
                                <td><?php echo arraytostring([$member->firstname, $member->middlename ? substr($member->middlename, 0,1) . '.' : '', $member->lastname], ' '); ?></td>
                                <td><?php echo $member->level; ?></td>
                                <td><?php echo $member->type; ?></td>
                                <td><?php echo $member->groups; ?></td>
                                <td><?php echo $member->email; ?></td>
                                <td><?php echo $member->msisdn; ?></td>
                                <td><?php echo $member->telephone; ?></td>
                                <td><?php echo arraytostring([$member->address_blockno, $member->address_street, $member->address_brgy, $member->address_city, $member->address_zip]); ?></td>
                                <td></td>
                            </tr>
                        <?php } ?>
                    </tbody> -->
                </table>
            </div>
        </div>
    </div>
    <button id="delete-member-btn" title="Delete all selected Members" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-member-btn" title="Add new Member" class="btn btn-float bgm-green add-new m-btn" data-toggle="modal" href="#add-member"><i class="zmdi zmdi-plus-square"></i></button>
</section>

<?php
$this->load->view('members/add');
$this->load->view('members/edit') ?>
