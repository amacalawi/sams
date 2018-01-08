<section id="content">
    <div class="container">
        <?php $this->load->view('partials/messages') ?>

        <div class="toolbar-group text-right">
            <a href="<?php echo base_url('users/trash'); ?>" class="btn btn-danger btn-link toolbar-item"><i class="fa fa-trash">&nbsp;</i>Trashed (<span class="trash-count"><?php echo $trash['count'] ?></span>)</a>
        </div>

        <div class="card"><?php

            echo breadcrumbs(); ?>

            <div class="table-responsive">
                <table id="user-table-command" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id"        data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">User ID</th>
                            <th data-column-id="username"     data-css-class="username" data-order="asc">Username</th>
                            <th data-column-id="fullname" data-css-class="fullname" data-order="asc">Name</th>
                            <th data-column-id="email"     data-css-class="email" data-order="asc">Email</th>
                            <th data-column-id="privilege"    data-css-class="role" data-order="asc">Role</th>
                            <th data-column-id="privilege_level"    data-css-class="role" data-order="asc">Role Level</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <button id="delete-user-btn" title="Delete all selected Users" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-user-btn" title="Add new User" class="btn btn-float bgm-green add-new m-btn" data-toggle="modal" href="#add-user"><i class="zmdi zmdi-plus-square"></i></button>
</section>

<?php
$this->load->view('users/add');
$this->load->view('users/edit') ?>