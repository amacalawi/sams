<section id="content">
    <div class="container">
 
        <?php $this->load->view('partials/messages') ?>

        <div class="card"><?php
            echo breadcrumbs('', 'Messages Tracking'); ?>
            <div class="table-responsive">
                <table id="messaging-tracking-table" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id" data-css-class="id" data-order="desc" data-visible="true" data-identifier="true">Track ID</th>
                            <th data-column-id="message" data-css-class="message" data-order="asc">Message</th>
                            <th data-column-id="contacts" data-css-class="contacts" data-order="asc" data-sortable="false">Number of Contacts</th>
                            <th data-column-id="successful" data-css-class="successful" data-order="asc" data-sortable="false">Successful</th>
                            <th data-column-id="pending" data-css-class="pending" data-order="asc">Pending</th>
                            <th data-column-id="failure" data-css-class="failure" data-order="asc" data-visible="true">Failure</th>
                            <th data-column-id="rejected" data-css-class="rejected" data-order="asc">Rejected</th>
                            <th data-column-id="buffered" data-css-class="buffered" data-order="asc">Buffered</th>
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                        </tr>
                    </thead>
                </table><hr class="m-t-0">
                <table id="table-track-text" class="table table-vmiddle table-condensed">
                    <tbody>
                        <tr>
                            <td id="td-contacts" class="p-t-20"><i class="fa fa-envelope">&nbsp;</i>Total Messages: <span><?php echo count($scheduled) ?></span></td>
                            <td id="td-pending" class="p-t-20"><i class="fa fa-paper-plane">&nbsp;</i>Total Pending: <span><?php echo @$pending; ?></span></td>
                            <td id="td-success" class="p-t-20"><i class="fa fa-check">&nbsp;</i>Total Success: <span><?php echo @$success; ?></span></td>
                            <td id="td-failed" class="p-t-20"><i class="fa fa-close">&nbsp;</i>Total Failed: <span><?php echo @$failed; ?></span></td>
                            <td id="td-rejected" class="p-t-20"><i class="zmdi zmdi-disc-full">&nbsp;</i>Total Rejected: <span><?php echo @$rejected; ?></span></td>
                            <td id="td-buffered" class="p-t-20"><i class="fa fa-signal">&nbsp;</i>Total Buffered: <span><?php echo @$buffered; ?></span></td>
                        </tr>
                    </tbody>
                </table><hr class="m-t-0">
            </div>
        </div>
    </div>
</section>

<button id="send-tracking-btn" title="Send All Pending Message" class="btn btn-float bgm-green add-new m-btn waves-effect waves-circle waves-float" data-toggle="modal" href="#add-member"><i class="fa fa-paper-plane"></i></button>