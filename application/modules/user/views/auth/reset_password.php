<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo (getConfig('company_name')==''?'Company Name':getConfig('company_name')); ?> | </title>

    <!-- Bootstrap -->
    <link href="<?php echo base_url('assets/vendors/bootstrap/dist/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="<?php echo base_url('assets/vendors/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
    <!-- NProgress -->
    <link href="<?php echo base_url('assets/vendors/nprogress/nprogress.css'); ?>" rel="stylesheet">
    <!-- Animate.css -->
    <link href="<?php echo base_url('assets/vendors/animate.css/animate.min.css'); ?>" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="<?php echo base_url('assets/build/css/custom.min.css'); ?>" rel="stylesheet">
    <style type="text/css">
        .login_content form input[type="submit"]{
            margin-left: inherit;
        }
        .users-info{
            text-align: left;
        }
        #company_logo{
            margin: 0 auto;
        }
    </style>
  </head>

  <body class="login">
    <div>
      <a class="hiddenanchor" id="signup"></a>
      <a class="hiddenanchor" id="signin"></a>

      <div class="login_wrapper">
        <div class="animate form login_form">
            <?php
            if (getConfig('logo_to_use')=='logo') : ?>
                <div id="preview" class="center">
                    <img src="<?php echo base_url('view_image/?path='.getConfig('logo')); ?>" alt="<?php echo (getConfig('company_name')==''?'Company Name':getConfig('company_name')); ?>" class="img-responsive" id="company_logo">
                </div>
            <?php endif; ?>
          <section class="login_content">
            <?php if (getConfig('logo_to_use')=='company_name') : ?>
                <h2>
                <?php echo (getConfig('company_name')==''?'Company Name':getConfig('company_name')); ?>
                </h2>
            <?php endif; ?>
            
            <h1><?php echo lang('reset_password_heading');?></h1>

            <?php if ($message) : ?>
                  <div class="alert alert-warning alert-dismissible fade in" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
                    </button>
                    <?php echo $message;?>
                  </div>
            <?php endif; ?>

            <?php echo form_open('user/auth/reset_password/' . $code);?>
                
                    
                <p>
                    <label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label> <br />
                    <?php echo form_input($new_password, null, 'form-control');?>
                </p>

                <p>
                    <?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?> <br />
                    <?php echo form_input($new_password_confirm, null, 'form-control');?>
                </p>

                <?php echo form_input($user_id, null, 'form-control');?>
                <?php echo form_hidden($csrf); ?>

                <p><?php echo form_submit('submit', lang('reset_password_submit_btn'), 'class="btn btn-info btn-block"');?></p>

            <?php echo form_close();?>
          </section>
          <p><a href="<?php echo base_url('user/auth/forgot_password'); ?>"><?php echo lang('login_forgot_password');?></a></p>
        </div>

        
      </div>
    </div>
  </body>
</html>


