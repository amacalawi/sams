<section id="content" class="clearfix">
    <div class="col-sm-12">
        <div class="row">
            <div class="gridster-disabled">
                <div class="cards">
                    <div class="card-item col-sm-4" data-row="1" data-col="1" data-sizex="1" data-sizey="1">
                        <div class="card blog-post">
                            <div class="bp-header">
                                <img src="<?php echo base_url('assets/img/headers/1.png') ?>" class="img-wide">
                                <div class="bp-title bgm-orange">
                                    <h2>Members Manager</h2>
                                    <small>Add, update, or remove Members</small>
                                </div>
                                <div class="btn-group btn-group-justified">
                                    <a href="<?php echo base_url('members') ?>" class="btn btn-default">Manage</a>
                                    <a href="<?php echo base_url('members/import') ?>" class="btn btn-default">Import</a>
                                    <a href="<?php echo base_url('members/export') ?>" class="btn btn-default">Export</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-item col-sm-4" data-row="1" data-col="2" data-sizex="1" data-sizey="1">
                        <div class="card blog-post">
                            <div class="bp-header">
                                <img src="<?php echo base_url('assets/img/headers/2.png') ?>" alt="">
                                <a href="<?php echo base_url('groups') ?>" class="bp-title">
                                    <h2>Group Manager</h2>
                                    <small>Add, update or remove Groups</small>
                                </a>
                                <div class="btn-group btn-group-justified">
                                    <a href="<?php echo base_url('groups') ?>" class="btn btn-default">Manage</a>
                                    <!-- <a href="<?php echo base_url('groups/import') ?>" class="btn btn-default">Import</a>
                                    <a href="<?php echo base_url('groups/export') ?>" class="btn btn-default">Export</a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-item col-sm-4" data-row="1" data-col="3" data-sizex="1" data-sizey="1">
                        <div class="card blog-post">
                            <div class="bp-header">
                                <img src="<?php echo base_url('assets/img/headers/sm/messaging.jpg') ?>">
                                <a href="<?php echo base_url('messaging/new') ?>" class="bp-title bgm-amber">
                                    <h2>Messaging Manager</h2>
                                    <small>Send or view messages</small>
                                </a>
                                <div class="btn-group btn-group-justified">
                                    <a href="<?php echo base_url('messaging/inbox') ?>" class="btn btn-default">Inbox</a>
                                    <a href="<?php echo base_url('messaging/outbox') ?>" class="btn btn-default">Outbox</a>
                                    <a href="<?php echo base_url('messaging/new') ?>" class="btn btn-default">Send</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-item col-sm-4" data-row="2" data-col="1" data-sizex="2" data-sizey="2">
                        <div class="card blog-post">
                            <a href="<?php echo base_url('messaging/inbox'); ?>" class="bp-header">
                                <div class="bp-title bgm-amber">
                                    <h2>Inbox</h2>
                                    <small>Last <?php echo $inbox_limit; ?> messages</small>
                                </div>
                            </a>
                            <div class="bp-body">
                                <div class="list-group">
                                    <?php foreach ($inbox_list as $message) { ?>
                                        <div class="list-group-item">
                                            <?php echo $message->body; ?>
                                            <em><?php echo $message->msisdn; ?></em>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>