<section id="content">
    <div class="container">
        <?php $this->load->view('partials/messages') ?>

        
        <div class="card">
        <div class="card-header m-b-25">
            <h2>All Announcement
                <ul class="pull-right breadcrumb">
                    <li><a href="#">Monitor</a></li> 
                    <li>Announcement</li> 
                </ul>
            </h2>
        </div>    

            <div class="table-responsive">
                <table id="announcement-table-command" class="table table-condensed table-vmiddle table-striped table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id"           data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="announcement_id"        data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Announcement ID</th>
                            <th data-column-id="announcement_name"     data-css-class="announcement_name" data-order="asc">Announcement Name</th>
                            <th data-column-id="announcement_text" data-css-class="announcement_text" data-formatter="announcement" data-order="asc">Announcement</th>
                            
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <button id="delete-announcement-btn" title="Delete all selected Users" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-announcement-btn" title="Add new User" class="btn btn-float bgm-green add-new m-btn" data-toggle="modal" href="#add-announcement"><i class="zmdi zmdi-plus-square"></i></button>
</section>


<?php
$this->load->view('monitor/add_announcement'); 
$this->load->view('monitor/edit_announcement'); 
