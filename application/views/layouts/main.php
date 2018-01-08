<?php

$this->load->view('partials/header');
$this->load->view('partials/utilitybar'); ?>

<section id="main"><?php

    $this->load->view('partials/sidebar');

    $this->load->view($Headers->Page); ?>

</section><?php

$this->load->view('partials/footer');
 ?>