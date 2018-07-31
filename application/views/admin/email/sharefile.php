<body bgcolor="#e1e8ed" style="-webkit-text-size-adjust:none; margin:0; padding:0;"> 
<!-- send files email template: do not replace %these_values% -->
<table width="100%" border="0" cellpadding="10" cellspacing="0" align="center" bgcolor="#FFFFFF" style="border-radius: 4px; max-width: 600px;">
        <tr>
            <th align="center" style="border-bottom: 1px solid #e1e8ed;">
                <?php get_company_logo(); ?>
            </th>
        </tr>
        <tr>
            <td>
                <h2 style="text-align: center; font-weight: 300;"><strong style="color:#15c"><?php echo $from_email;?></strong> sent you some files</h2>
            </td>
        </tr>
        
        <tr>
            <td style="text-align:center;">
                <a style="padding:16px 30px; background:#4ECF7E; color:#fff; text-decoration:none; border-radius:4px; display: inline-block;" href="<?php echo "https://";echo $copylink; ?>">
                    Download
                </a>
            </td>
        </tr>
        <tr>
            <td style="text-align:center; border-bottom: 1px solid #e1e8ed;"></td>
        </tr>

        <?php $files = json_decode($filename); foreach ($files as $key => $value) {?>
        <tr>
            <td></td>
        </tr>
    <?php } ?>
        <tr>
            <td></td>
        </tr>
</table>
</body>