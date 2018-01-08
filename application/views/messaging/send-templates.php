<div class="modal fade" id="send-templates-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="card m-t-25">
                <div class="card-header bgm-green">
                    <button type="button" class="close c-white" data-dismiss="modal">&times;</button>
                    <h2>Insert Template</h2>
                </div>
                <div id="message-template-list" class="list-group">
                    <?php foreach ($form['templates'] as $template): ?>
                        <div data-id="<?php echo $template->id ?>" class="list-group-item clearfix"><span><?php echo $template->name; ?></span> <button class="pull-right btn btn-success" type="button"><i class="fa fa-check"></i></button></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>