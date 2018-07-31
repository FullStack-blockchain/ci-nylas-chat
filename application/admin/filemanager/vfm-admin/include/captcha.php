<?php
/**
 * VFM - veno file manager: include/captcha.php
 * CAPTCHA code
 *
 * PHP version >= 5.3
 *
 * @category  PHP
 * @package   VenoFileManager
 * @author    Nicola Franchini <support@veno.it>
 * @copyright 2013 Nicola Franchini
 * @license   Exclusively sold on CodeCanyon
 * @link      http://filemanager.veno.it/
 */ 

if (SetUp::getConfig('recaptcha') && SetUp::getConfig('recaptcha_site')) : ?>

    <script type="text/javascript">
      var onloadCallback = function() {
        grecaptcha.render('grecaptcha', {
          'sitekey' : '<?php echo SetUp::getConfig('recaptcha_site');?>',
        });
      };
    </script>
    <div class="form-group">
        <div id="grecaptcha"></div>
    </div>
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit&hl=<?php echo $encodeExplorer->lang; ?>"></script>

<?php
else : ?>

    <div class="form-group captcha-group">
        <div class="input-group">
            <span class="input-group-addon captchadd">
                <img src="<?php echo $capath; ?>captcha/img.php" id="captcha" />
            </span>

            <input class="form-control input" id="inputc" type="text" name="captcha" 
            placeholder="<?php echo $encodeExplorer->getString('enter_captcha'); ?>" />
            <span class="input-group-btn">
                <button class="btn btn-default btn" type="button" id="capreload">
                    <i class="fa fa-refresh"></i>
                </button>
            </span>
        </div>
    </div>
    <script>
        $(function() {
            $('#capreload').click(function(){  
                $('#captcha').attr('src', '<?php echo $capath; ?>captcha/img.php?' + (new Date).getTime());
                $('#inputc').val('');
            });
        });
    </script>

<?php 
endif;
