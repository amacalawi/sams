<section id="content">
    <div class="container">
        <?php $this->load->view('partials/messages') ?>

        
        <div class="card">
        <div class="card-header m-b-25">
            <h2>All Splash Page
                <ul class="pull-right breadcrumb">
                    <li><a href="#">Monitor</a></li> 
                    <li>Splash Page</li> 
                </ul>
            </h2>
        </div>    

            <div class="table-responsive">
                <table id="splash-table-command" class="table table-condensed table-vmiddle table-striped table-hover">
                    <thead>
                        <tr>
                            <th data-column-id="count_id" data-visible="true" data-type="numeric" data-sortable="false">#</th>
                            <th data-column-id="id"  data-css-class="id" data-order="asc" data-visible="false" data-identifier="true">Splash Page ID</th>
                            <th data-column-id="video_title" data-css-class="video_title" data-order="asc">Splash Page Title</th>
                            <th data-column-id="video_source" data-css-class="video_source" data-formatter="video_source" data-order="asc">Splash Page Source</th>  
                            <th data-column-id="commands" data-formatter="commands" data-sortable="false" data-header-css-class="fixed-width">Actions</th>
                        </tr>
                    </thead>

                </table>
            </div>
        </div>
    </div>
    <button id="delete-splash-btn" title="Delete all selected Users" class="btn btn-float bgm-red delete-all m-btn"><i class="zmdi zmdi zmdi-delete"></i></button>
    <button id="add-new-splash-btn" title="Add new User" class="btn btn-float bgm-green add-new m-btn" data-toggle="modal" href="#add-splash"><i class="zmdi zmdi-plus-square"></i></button>
</section>


<?php
$this->load->view('monitor/add_splash'); 
$this->load->view('monitor/edit_splash'); 
