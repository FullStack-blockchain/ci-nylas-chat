<?php
/**
* ROLE PERMISSIONS
**/
?>
<div id="view-permissions" class="anchor"></div>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-default box-solid">
            <div class="box-header with-border">
                <i class="fa fa-balance-scale"></i> <?php print $encodeExplorer->getString('permissions'); ?>
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
            </div>
            <div class="box-body">
                <h4>Admin</h4>

                <div class="row">
                    <div class="col-sm-6">
                        <div class="checkbox checkbox-big">
                            <label>
                                <input type="checkbox" name="upload_enable" 
                                <?php
                                if ($setUp->getConfig('upload_enable')) {
                                    echo "checked";
                                } ?>> 
                                    <span class="fa-stack">
                                      <i class="fa fa-file-o fa-stack-2x"></i>
                                      <i class="fa fa-upload fa-stack-1x"></i>
                                    </span>
                                <?php print $encodeExplorer->getString("admin_can_upload"); ?>
                            </label>
                        </div>

                        <div class="checkbox checkbox-big">
                            <label>
                                <input type="checkbox" name="delete_enable" 
                                <?php
                                if ($setUp->getConfig('delete_enable')) {
                                    echo "checked";
                                } ?>> 
                                    <span class="fa-stack">
                                      <i class="fa fa-file-o fa-stack-2x"></i>
                                      <i class="fa fa-trash fa-stack-1x"></i>
                                    </span>
                                <?php print $encodeExplorer->getString("admin_can_del_files"); ?>
                            </label>
                        </div>

                        <div class="checkbox checkbox-big">
                            <label>
                                <input type="checkbox" name="rename_enable" 
                                <?php
                                if ($setUp->getConfig('rename_enable')) {
                                    echo "checked";
                                } ?>> 
                                    <span class="fa-stack">
                                      <i class="fa fa-file-o fa-stack-2x"></i>
                                      <i class="fa fa-pencil-square-o fa-stack-1x"></i>
                                    </span>
                                <?php print $encodeExplorer->getString("admin_can_rename_files"); ?>
                            </label>
                        </div>

                        <div class="checkbox checkbox-big">
                            <label>
                                <input type="checkbox" name="move_enable" 
                                <?php
                                if ($setUp->getConfig('move_enable')) {
                                    echo "checked";
                                } ?>> 
                                    <span class="fa-stack">
                                      <i class="fa fa-file-o fa-stack-2x"></i>
                                      <i class="fa fa-arrow-right fa-stack-1x"></i>
                                    </span>
                                <?php print $encodeExplorer->getString("admin_can_move_files"); ?>
                            </label>
                        </div>

                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="copy_enable" 
                                <?php
                                if ($setUp->getConfig('copy_enable')) {
                                    echo "checked";
                                } ?>> 
                                    <span class="fa-stack">
                                      <i class="fa fa-file-o fa-stack-2x"></i>
                                      <i class="fa fa-files-o fa-stack-1x"></i>
                                    </span>
                                <?php print $encodeExplorer->getString("admin_can_copy_files"); ?>
                            </label>
                        </div>
                    </div>

                    <div class="col-sm-6">
                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="newdir_enable" 
                                    <?php
                                    if ($setUp->getConfig('newdir_enable')) {
                                        echo "checked";
                                    } ?>> 
                                        <span class="fa-stack">
                                          <i class="fa fa-folder fa-stack-2x"></i>
                                          <i class="fa fa-plus fa-stack-1x fa-inverse"></i>
                                        </span>
                                    <?php print $encodeExplorer->getString("admin_can_add_dirs"); ?>
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="delete_dir_enable" 
                                    <?php
                                    if ($setUp->getConfig('delete_dir_enable')) {
                                        echo "checked";
                                    } ?>> 
                                        <span class="fa-stack">
                                          <i class="fa fa-folder fa-stack-2x"></i>
                                          <i class="fa fa-trash fa-stack-1x fa-inverse"></i>
                                        </span>
                                    <?php print $encodeExplorer->getString("admin_can_del_dirs"); ?>
                                </label>
                            </div>

                            <div class="checkbox">
                                <label>
                                    <input type="checkbox" name="rename_dir_enable" 
                                    <?php
                                    if ($setUp->getConfig('rename_dir_enable')) {
                                        echo "checked";
                                    } ?>> 
                                        <span class="fa-stack">
                                          <i class="fa fa-folder fa-stack-2x"></i>
                                          <i class="fa fa-pencil-square-o fa-stack-1x fa-inverse"></i>
                                        </span>
                                    <?php print $encodeExplorer->getString("admin_can_rename_dirs"); ?>
                                </label>
                            </div>
                    </div>
                </div>

<?php
if (GateKeeper::isMasterAdmin()) { ?>
<hr>
                <h4>SuperAdmin</h4>

                <div class="row">
                    <div class="col-sm-6">
<div class="form-group">
                        <div class="checkbox checkbox-big">
                            <label>
                                <input type="checkbox" name="superadmin_can_preferences" 
                                <?php
                                if ($setUp->getConfig('superadmin_can_preferences')) {
                                    echo "checked";
                                } ?>><i class="fa fa-dashboard fa-lg"></i>
                                <?php print $encodeExplorer->getString("preferences"); ?>
                            </label>
                        </div>
</div>
<div class="form-group">
                        <div class="checkbox checkbox-big">
                            <label>
                                <input type="checkbox" name="superadmin_can_users" 
                                <?php
                                if ($setUp->getConfig('superadmin_can_users')) {
                                    echo "checked";
                                } ?>><i class="fa fa-users fa-lg"></i>
                                <?php print $encodeExplorer->getString("users"); ?>
                            </label>
                        </div>
</div>
</div>
                    <div class="col-sm-6">

<div class="form-group">
                        <div class="checkbox checkbox-big">
                            <label>
                                <input type="checkbox" name="superadmin_can_translations" 
                                <?php
                                if ($setUp->getConfig('superadmin_can_translations')) {
                                    echo "checked";
                                } ?>><i class="fa fa-language fa-lg"></i>
                                <?php print $encodeExplorer->getString("translations"); ?>
                            </label>
                        </div>
</div>
<div class="form-group">
                        <div class="checkbox checkbox-big">
                            <label>
                                <input type="checkbox" name="superadmin_can_statistics" 
                                <?php
                                if ($setUp->getConfig('superadmin_can_statistics')) {
                                    echo "checked";
                                } ?>><i class="fa fa-pie-chart fa-lg"></i>
                                <?php print $encodeExplorer->getString("statistics"); ?>
                            </label>
                        </div>
</div>



                    </div>
                </div>

<?php } ?>


            </div> <!-- box-body -->

            <div class="box-footer">
                <button type="submit" class="btn btn-default pull-right" 
                data-toggle="tooltip" data-placement="left"
                title="<?php print $encodeExplorer->getString("save_settings"); ?>">
                    <i class="fa fa-save"></i>
                </button>
            </div>
            
        </div> <!-- box -->
    </div>



</div>