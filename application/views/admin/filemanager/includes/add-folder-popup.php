<!-- Client send file modal -->
<div class="modal fade" id="add_folder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open($current_url_folder_change,['id'=>'submit_here']); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?php echo "Add Folder" ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
					<div class="form-group" app-field-wrapper="new_sub_directory">
						<label for="new_sub_directory">Folder Name</label>
						<input type="text" id="new_sub_directory" name="new_sub_directory" class="form-control"  value="">
					</div>
                        <?php //echo render_input('new_sub_directory','new_sub_directory'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo "Add Folder"; ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>

<div class="modal fade" id="add_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open_multipart($current_url_folder_change,['id'=>'submit_here']); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?php echo "Add Files" ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group" app-field-wrapper="new_sub_directory">
                        <label for="new_sub_directory">Select Files to Upload</label>
                       <input type="file" name="files[]" multiple/>
                    </div>
                        <?php //echo render_input('new_sub_directory','new_sub_directory'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo "Submit"; ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="edit_folder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open_multipart($current_url_folder_change,['id'=>'submit_here']); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?php echo "Rename Folder" ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group" app-field-wrapper="new_sub_directory">
                        <label for="new_sub_directory">Rename Folder</label>
                       <input type="text" name="rename" />
                       <input type="hidden" name="dir_name" id="bookId">
                    </div>
                        <?php //echo render_input('new_sub_directory','new_sub_directory'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo "Submit"; ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="zip_download" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open_multipart($current_url_folder_change,['id'=>'submit_here']); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?php echo "Download Folder" ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body download">
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group" app-field-wrapper="new_sub_directory">
                        <label for="new_sub_directory">You are about to download the whole folder </label>
                       <input type="hidden" name="download" id="download">
                    </div>
                        <?php //echo render_input('new_sub_directory','new_sub_directory'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo "Ok"; ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="share_file" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open_multipart($current_url_folder_change,['id'=>'submit_here']); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?php echo "Share Link" ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body share">
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group" app-field-wrapper="new_sub_directory">
                       <label for="new_sub_directory">Copy link to share file Press ok close this popup</label>
                       <input id="copylink" class="sharelink form-control" type="text" name="copylink" onclick="this.select()" readonly>
                       <div id="append_string"></div>
                       <input type="hidden" name="number_count" id="share_cpount">
                    </div>
                    <div class="form-group" app-field-wrapper="new_sub_directory" id="dvStats" style="display:none">
                       <label for="new_sub_directory">Form Email</label>                       
                       <input type="email" name="from_email">
                       <label for="new_sub_directory">To Email</label> 
                       <input type="email" name="to_email">
                    </div>
                        <?php //echo render_input('new_sub_directory','new_sub_directory'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer" id="ok">            
                <button type="submit" class="btn btn-info"><?php echo "Ok"; ?></button>
            </div>
            <div class="modal-footer" style="display:none;">            
                <button type="submit" class="btn btn-info"><?php echo "Send Email"; ?></button>
            </div> 
            <div class="modal-footer"> 
                <a id="hh" class="btn btn-info" onclick ="myFunction()" >Click here to share with email</a>         
                
            </div>         
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<div class="modal fade" id="lock_file_folder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?php echo form_open_multipart($current_url_folder_change,['id'=>'submit_here']); ?>
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel"><?php echo "Lock Foldeer/File" ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body lock">
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group" app-field-wrapper="new_sub_directory">
                       <label for="new_sub_directory">Are you sure to lock this file/folder?</label>

                       <input  type="hidden" name="mydirect" id="mydirect">
                       <input type="hidden" name="lock_count" id="lock_count">
                    </div>
                     <?php //echo render_input('new_sub_directory','new_sub_directory'); ?>
                    </div>
                </div>
            </div>
            <div class="modal-footer"> 
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>           
                <button type="submit" class="btn btn-info"><?php echo "Yes"; ?></button>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div>
</div>
<script>
function myFunction() {
    $("#dvStats").css("display","block");
    $("#hh").css("display","none");
    $(".modal-footer").css("display","block");
    $("#ok").css("display","none");
}
</script>
