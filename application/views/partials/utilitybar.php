    <header id="header">
        <ul class="header-inner">
            <li id="menu-trigger" data-trigger="#sidebar">
                <div class="line-wrap">
                    <div class="line top"></div>
                    <div class="line center"></div>
                    <div class="line bottom"></div>
                </div>
            </li>

            <li class="logo hidden-xs">
                <a href="<?php echo base_url(); ?>"><?php echo $Headers->Page_Title ?></a>
            </li>

            <li class="pull-right">
                <ul class="top-menu">
                    <li id="toggle-width">
                        <div class="toggle-switch">
                            <input id="tw-switch" type="checkbox" hidden="hidden">
                            <label for="tw-switch" class="ts-helper"></label>
                        </div>
                    </li>
                    <!-- <li id="top-search">
                        <a class="tm-search" href=""></a>
                    </li> -->

                    <li class="dropdown">
                        <a data-toggle="dropdown" class="tm-settings" href=""></a>
                        <ul class="dropdown-menu dm-icon pull-right">
                            <li class="hidden-xs">
                                <a data-action="fullscreen" href=""><i class="zmdi zmdi-fullscreen"></i> Toggle Fullscreen</a>
                            </li>
                            <li>
                                <a data-action="clear-localstorage" href=""><i class="zmdi zmdi-delete"></i> Clear Local Storage</a>
                            </li>
                            <!-- <li>
                                <a href=""><i class="zmdi zmdi-settings"></i> Settings</a>
                            </li> -->
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>

        <!-- Top Search Content -->
        <?php $this->load->view('partials/search') ?>
    </header>