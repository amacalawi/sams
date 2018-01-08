<?php if(!is_null($this->session->flashdata('message'))) : ?>
    <div class="alert alert-<?php echo $this->session->flashdata('message')['type'] ?>" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        <?php
        if( is_array( $this->session->flashdata('message')['message'] ) ) {
            foreach ($this->session->flashdata('message')['message'] as $message) {
                echo "<p>".$message."</p>";
            }
        } else {
            echo $this->session->flashdata('message')['message'];
        } ?>
    </div>
<?php endif; ?>