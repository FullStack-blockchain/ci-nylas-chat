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
          <section class="login_content">
            <?php if (getConfig('logo_to_use')=='company_name') : ?>
                <h2>
                <?php echo (getConfig('company_name')==''?'Company Name':getConfig('company_name')); ?>
                </h2>
            <?php endif; ?>
            
            <h1><?php echo lang('reset_password_heading');?></h1>

            <div id="infoMessage"><?php echo $message;?></div>

            <?php echo form_open('auth/reset_password/' . $code);?>
                <h1> Please Login
                <?php
                if (getConfig('logo_to_use')=='logo') : ?>
                    <div id="preview" class="center">
                        <img src="<?php echo base_url('view_image/?path='.getConfig('logo')); ?>" alt="<?php echo (getConfig('company_name')==''?'Company Name':getConfig('company_name')); ?>" class="img-responsive" id="company_logo">
                    </div>
                <?php endif; ?>
                    
              </h1>
                <p>
                    <label for="new_password"><?php echo sprintf(lang('reset_password_new_password_label'), $min_password_length);?></label> <br />
                    <?php echo form_input($new_password);?>
                </p>

                <p>
                    <?php echo lang('reset_password_new_password_confirm_label', 'new_password_confirm');?> <br />
                    <?php echo form_input($new_password_confirm);?>
                </p>

                <?php echo form_input($user_id);?>
                <?php echo form_hidden($csrf); ?>

                <p><?php echo form_submit('submit', lang('reset_password_submit_btn'));?></p>

            <?php echo form_close();?>
          </section>
          <p><a href="<?php echo base_url('user/auth/forgot_password'); ?>"><?php echo lang('login_forgot_password');?></a></p>
        </div>

        
      </div>
    </div>
  </body>
</html>


