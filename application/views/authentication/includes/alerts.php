<div class="row">
    <?php
    $alertclass = "";
    if ($this->session->flashdata('message-success')) {
        $alertclass = "success";
    } else if ($this->session->flashdata('message-warning')) {
        $alertclass = "warning";
    } else if ($this->session->flashdata('message-info')) {
        $alertclass = "info";
    } else if ($this->session->flashdata('message-danger')) {
        $alertclass = "danger";
    } else if ($this->session->flashdata('message-primary')) {
        $alertclass = "primary";
    } else if ($this->session->flashdata('message-secondary')) {
        $alertclass = "secondary";
    } else if ($this->session->flashdata('message-light')) {
        $alertclass = "light";
    } else if ($this->session->flashdata('message-dark')) {
        $alertclass = "dark";
    }
    if ($this->session->flashdata('message-' . $alertclass)) { ?>
        <div class="col-lg-12" id="alerts">
            <div class="text-center alert alert-<?php echo $alertclass; ?>" role="alert">
                <?php
                echo $this->session->flashdata('message-' . $alertclass);
                ?>
            </div>
        </div>
    <?php } ?>
</div>
