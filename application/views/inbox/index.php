<section id="content">
    <div class="container">
        <div class="block-header">
            <h2>Messages</h2>
        </div>

        <div class="card m-b-0" id="messages-main">

            <div class="ms-menu">
                <div class="ms-block">
                    <div class="ms-user">
                        <!-- <img src="//placeimg.com/80/80/people" alt=""> -->
                        <h4 class="h5">Signed in as <br/> <?php echo get_fullname(); ?></h4>
                    </div>
                </div>

                <div class="ms-block">
                    <div>
                        <a href="#" class="btn btn-link active">Contacts</a>
                        <a href="#" class="btn btn-link ">Groups</a>
                    </div>
                </div>
                <div class="listview lv-user m-t-20">

                    <!-- loop -->
                    <?php foreach ($contacts as $msisdn => $contacts_arr) { ?>
                        <a data-contact class="lv-item media" href="#" data-msisdn="<?php echo $msisdn; ?>">
                            <div class="lv-avatar pull-left">
                                <div class="lv-avatar-inner">
                                    <?php echo !empty(acronymify(array($contacts_arr[0]->firstname, $contacts_arr[0]->lastname))) ? acronymify(array($contacts_arr[0]->firstname, $contacts_arr[0]->lastname)) : '+'; ?>
                                </div>
                            </div>
                            <div class="media-body">
                                <?php foreach ($contacts_arr as $contact) { ?>
                                    <div class="lv-title contact-name"><?php echo !empty($contact->fullname) ? $contact->fullname : '<small><em class="text-muted">Unrecognized number</em></small>'; ?></div>
                                <?php } ?>
                                <div class="lv-small contact-msisdn"><?php echo $msisdn; ?></div>
                            </div>
                        </a>
                    <?php } ?>
                    <!-- /loop -->

                </div>


            </div>

            <div class="ms-body">
                <div id="inbox-conversation-viewer" class="listview lv-message" data-contact-msisdn="INVALID">
                    <div class="lv-header-alt clearfix">
                        <div id="ms-menu-trigger">
                            <div class="line-wrap">
                                <div class="line top"></div>
                                <div class="line center"></div>
                                <div class="line bottom"></div>
                            </div>
                        </div>

                        <div class="lvh-label hidden-xs">
                            <span id="inbox-conversation-viewer-contact-name" class="c-black fullname"></span>
                        </div>

                        <ul class="lv-actions actions">
                            <li>
                                <a href="">
                                    <i class="zmdi zmdi-delete"></i>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <i class="zmdi zmdi-check"></i>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <i class="zmdi zmdi-time"></i>
                                </a>
                            </li>
                            <li class="dropdown">
                                <a href="" data-toggle="dropdown" aria-expanded="true">
                                    <i class="zmdi zmdi-sort"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a href="">Latest</a>
                                    </li>
                                    <li>
                                        <a href="">Oldest</a>
                                    </li>
                                </ul>
                            </li>
                            <li class="dropdown">
                                <a href="" data-toggle="dropdown" aria-expanded="true">
                                    <i class="zmdi zmdi-more-vert"></i>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-right">
                                    <li>
                                        <a href="">Refresh</a>
                                    </li>
                                    <li>
                                        <a href="">Message Settings</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                    <div id="display-messages" class="lv-body">
                        <?php if( empty($inbox) ) { ?>
                            <div class="lv-item media bg-gray">
                                <div class="media-body">
                                    <em class="text-muted">No message</em>
                                </div>
                            </div>
                        <?php } ?>
                    </div>

                    <form id="reply-box-form" action="<?php echo base_url('messaging/send'); ?>" class="lv-footer ms-reply" method="POST">
                        <input type="hidden" data-msisdn name="msisdn" value="INVALID">
                        <textarea id="reply-box" name="body" disabled class="auto-size" placeholder="Send SMS"></textarea>
                        <button id="reply-box-submit-button" type="submit" role="button" class="btn bxsh-n"><i class="zmdi zmdi-mail-send"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>