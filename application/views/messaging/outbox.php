<section id="content">
    <div class="container">
        <?php if(!is_null($this->session->flashdata('message'))) : ?>
            <div class="alert alert-<?php echo $this->session->flashdata('message')['type'] ?>" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                <?php echo $this->session->flashdata('message')['message']; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <?php echo breadcrumbs('', 'Outbox'); ?>

            <div class="table-responsive">
                <table id="outbox-table" class="table table-condensed table-vmiddle table-hover">
                    <thead>
                        <tr>    
                            <!-- <th data-column-id="count_id" data-visible="true" data-type="numeric" data-sortable="false">#</th> -->
                            <th data-column-id="id" data-type="numeric" data-identifier="true">#</th>
                            <th data-column-id="id"        data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Member ID</th>
                            <th data-column-id="member" data-css-class="member" data-sortable="sort">Member</th>
                            <th data-column-id="msisdn"     data-css-class="msisdn" data-order="asc">Phone</th>
                            <th data-column-id="message" data-css-class="message" data-sortable="false">Message</th>
                            <th data-column-id="smsc"      data-css-class="smsc" data-order="asc">SMSC</th>
                            <th data-column-id="status"     data-css-class="status" data-order="asc">Status</th>
                        
                         </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</section>
