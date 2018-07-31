<?php $this->load->view('authentication/includes/head.php'); ?>


<body class="login_admin layout layout-vertical" <?php if (is_rtl()) {
    echo ' dir="rtl"';
} ?>>
<main>
    <div id="wrapper">
        <div class="content-wrapper">
            <div class="content custom-scrollbar">

                <div id="login" class="p-8">
                    <div class="company-logo">
                        <?php get_company_logo(); ?>
                    </div>
                    <div class="form-wrapper md-elevation-8 p-8" style="width:100%;max-width:40%;">
                        <?php $url = json_decode($file->url); 

                            foreach ($url as $key => $value) {?>
                        <l1>                         
                              <a download class="btn btn-primary" href="<?php echo $value;?>">
                            <span class="pull-left small">
                                <i class="icon icon-picture icon-lg"></i><?php echo basename($value, ".d").PHP_EOL; ?></span>
                            <span class="pull-right small">
                                <i class="icon icon-download icon-lg"></i>
                            </span>
                        </a>                    
                                                	
                        </l1>                       
                         <?php  }?>

                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
</body>

</html>
