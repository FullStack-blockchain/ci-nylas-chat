<?php init_single_head(); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/client.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">


                <div id="file-manager" class="page-layout simple right-sidebar">

                    <div class="page-content-wrapper custom-scrollbar">

                        <!-- HEADER -->
                        <div class="page-header bg-primary text-auto p-6">

                            <!-- HEADER CONTENT-->
                            <div class="header-content d-flex flex-column justify-content-between">

                                <!-- TOOLBAR -->
                                <div class="toolbar row no-gutters justify-content-between">


                                </div>
                                <!-- / TOOLBAR -->

                                <!-- BREADCRUMB -->
                                <div class="breadcrumb text-truncate row no-gutters align-items-center pl-0 pl-sm-20">

                                    <span class="h4">My Files</span>

                                    <i class="icon-chevron-right separator"></i>

                                    <span class="h4">Documents</span>

                                </div>
                                <!-- / BREADCRUMB -->

                            </div>
                            <!-- / HEADER CONTENT -->
                            <div>
                                <nav class="k_menu_7">
                                    <input type="checkbox" href="#" class="k_menu-open_7" name="k_menu-open_7"
                                           id="k_menu-open_7"/>
                                    <label class="k_menu-open-button" for="k_menu-open_7">
                                        <span><i class="k_menu_material-icons md-48">menu</i></span>
                                    </label>
                                    <div class="k_menu-item" id="k_menu-one">
                                        <a id="add-file-button" data-toggle="modal" data-target="#add_folder" class=""
                                           aria-label="Add file">
                                            <i class="icon icon-plus"></i>
                                        </a>
                                    </div>
                                    <div class="k_menu-item" id="k_menu-two">
                                        <a id="share-file-button" data-toggle="modal" data-target="#share_file" class=""
                                           aria-label="Add file">
                                            <i class="icon icon-plane"></i>
                                        </a>
                                    </div>
                                    <div class="k_menu-item" id="k_menu-three">
                                        <a id="add-file-button_left" data-toggle="modal" data-target="#add_file"
                                           class="" aria-label="Add file">
                                            <i class="icon icon-upload"></i>
                                        </a>
                                    </div>
                                </nav>
                            </div>

                            <?php if ($_GET['directory']) { ?>
                                <a id="add-file-button_left_back" onclick="history.go(-1);"
                                   class="btn btn-danger btn-fab" aria-label="Add file">
                                    <i class="icon icon-arrow-left"></i>
                                </a>
                            <?php } ?>


                            <!-- / ADD FILE BUTTON -->

                        </div>
                        <!-- / HEADER -->
                        <?php echo form_open_multipart($current_url_folder_change, ['id' => 'myAwesomeDropzone', 'class' => 'dropzone', 'enctype' => 'multipart/form-data']); ?>

                        <input name="files[]" type="file" multiple/>

                        <?php echo form_close(); ?>
                        <!-- ADD FILE BUTTON -->
                        <!-- CONTENT -->
                        <div class="page-content custom-scrollbar">
                            <!-- LIST VIEW -->
                            <table class="table list-view">

                                <thead>

                                <tr>
                                    <th class="K_blank_width"></th>
                                    <th class="K_name_width">Name</th>
                                    <th class="d-none d-md-table-cell">Type</th>
                                    <th class="d-none d-sm-table-cell K_size_width">Size</th>
                                    <th class="d-none d-lg-table-cell">Last Modified</th>
                                    <th class="d-table-cell K_action_width">Action</th>
                                </tr>

                                </thead>

                                <tbody>
                                <?php foreach ($scanned_directories as $directoryy) { ?>
                                    <?php $directory = $directoryy['name']; ?>

                                    <?php
                                    $user_id = $this->session->userdata('staff_user_id');
                                    $this->db->select('*');
                                    $this->db->where('folder_name', $directory);
                                    $count = $this->db->get('tblfolder_lock')->row();

                                    if ($count->count == 0 || ($count->user_id == $user_id && $count->count == 1) || !$count) {
                                        ?>
                                        <tr>
                                            <td data-directory-url="<?php echo $current_url_folder_change . (strpos($current_url_folder_change, "directory") ? "/$directory" : "?directory=$directory") ?>"
                                                class="file-icon">
                                                <i class="icon-folder"></i>
                                            </td>
                                            <?php if ($_GET['directory'] == 'projects') {
                                                $id = $directory;
                                                $this->db->select('*');
                                                $this->db->where('id', $id);
                                                $project_name = $this->db->get('tblprojects')->row();
                                                $name = $project_name->name;
                                            }
                                            if ($_GET['directory'] == 'Clients') {
                                                $id = $directory;
                                                $this->db->select('*');
                                                $this->db->where('userid', $id);
                                                $company_name = $this->db->get('tblclients')->row();
                                                $name = $company_name->company;
                                            }

                                            ?>
                                            <td data-directory-url="<?php echo $current_url_folder_change . (strpos($current_url_folder_change, "directory") ? "/$directory" : "?directory=$directory") ?>"
                                                class="name folder_name"><?php echo substr($directory, 0, 20);
                                                if ($_GET['directory'] == 'projects') {
                                                    echo "-";
                                                    echo $name;
                                                }
                                                if ($_GET['directory'] == 'Clients') {
                                                    echo "-";
                                                    echo $name;
                                                } ?></td>
                                            <td data-directory-url="<?php echo $current_url_folder_change . (strpos($current_url_folder_change, "directory") ? "/$directory" : "?directory=$directory") ?>"
                                                class="type d-none d-md-table-cell"><?php if ($directoryy['type'] == 'dir') {
                                                    echo "Folder";
                                                } ?></td>
                                            <td data-directory-url="<?php echo $current_url_folder_change . (strpos($current_url_folder_change, "directory") ? "/$directory" : "?directory=$directory") ?>"
                                                class="size d-none d-sm-table-cell"><?php echo $directoryy['size']; ?></td>
                                            <td data-directory-url="<?php echo $current_url_folder_change . (strpos($current_url_folder_change, "directory") ? "/$directory" : "?directory=$directory") ?>"
                                                class="last-modified d-none d-lg-table-cell">July 8, 2015
                                            </td>
                                            <td class="d-table-cell k_button_postion_td">
                                                <nav id="k_menu_5" class="k_menu_5">
                                                    <?php echo form_open_multipart($current_url_folder_change, ['id' => 'submit_here']); ?>
                                                    <input type="checkbox" href="#" class="k_menu-open_5"
                                                           name="k_menu-open_5" id="k_menu-open_5"/>
                                                    <label class="k_menu-open-button" for="k_menu-open_5">
                                                        <span><i class="k_menu_material-icons md-48">menu</i></span>
                                                    </label>
                                                    <div class="k_menu-item1" id="k_menu-one">
                                                        <a id="download-folder" data-id="<?php echo $directory; ?>"
                                                           data-toggle="modal" type="button" class="btn btn-icon"
                                                           data-target="#zip_download"
                                                           data-fuse-bar-toggle="file-manager-info-sidebar"
                                                           aria-label="Edit file">
                                                            <i class="icon icon-cloud-download"></i>
                                                        </a>
                                                    </div>
                                                    <div class="k_menu-item1" id="k_menu-two">
                                                        <a id="rename-button" data-id="<?php echo $directory; ?>"
                                                           data-toggle="modal" type="button" class="btn btn-icon"
                                                           data-target="#edit_folder"
                                                           data-fuse-bar-toggle="file-manager-info-sidebar"
                                                           aria-label="Edit file">
                                                            <i class="icon icon-pencil"></i>
                                                        </a>
                                                    </div>

                                                    <div class="k_menu-item1" id="k_menu-three">
                                                        <input type="hidden" name="delete"
                                                               value="<?php echo $directory; ?>">
                                                        <button type="submit" class="btn btn-icon"
                                                                data-fuse-bar-toggle="file-manager-info-sidebar">
                                                            <i class="icon icon-delete"></i>
                                                        </button>
                                                    </div>
                                                    <div class="k_menu-item1" id="k_menu-four">
                                                        <?php if ($count->count == 0){ ?>
                                                        <a id="testtt" data-id="<?php echo $directory; ?>"
                                                           data-toggle="modal" type="button"
                                                           class="btn btn-icon wis_lock" data-target="#lock_file_folder"
                                                           data-fuse-bar-toggle="file-manager-info-sidebar" value="1">
                                                                <i class="icon icon-lock" style="color:#21a828"></i>
                                                            </a><?php }else{ ?>
                                                        <a id="testt" data-id="<?php echo $directory; ?>"
                                                           data-toggle="modal" type="button"
                                                           class="btn btn-icon wis_lock" data-target="#lock_file_folder"
                                                           data-fuse-bar-toggle="file-manager-info-sidebar" value="0">
                                                            <i class="icon icon-lock" style="color:#fff"></i>
                                                        </a>
                                                    </div>

                                                <?php } ?>
                                                    <?php echo form_close(); ?>
                                                </nav>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } ?>
                                <?php ?>
                                <?php foreach ($scanned_files as $directoryy) { ?>
                                    <?php $directory = $directoryy['name']; ?>
                                    <?php
                                    $user_id = $this->session->userdata('staff_user_id');
                                    $this->db->select('*');
                                    $this->db->where('folder_name', $directory);
                                    $count = $this->db->get('tblfolder_lock')->row();

                                    if ($count->count == 0 || ($count->user_id == $user_id && $count->count == 1) || !$count) {
                                        ?>
                                        <tr>
                                            <td data-download-url="<?php echo $current_url_folder_change . (strpos($current_url_folder_change, "directory") ? "/$directory" : "?directory=$directory") ?>"
                                                class="file-icon">
                                                <input type="checkbox" class="product" id="id_" name='ids[]'
                                                       value='<?php if ($_GET['directory']) {
                                                           $dir = $_GET['directory'];
                                                           echo site_url(); ?>uploads/<?php echo $dir;
                                                           echo '/';
                                                           echo $directory;
                                                       } else {
                                                           echo site_url(); ?>/uploads/<?php echo $directory;
                                                       } ?>'>

                                            </td>
                                            <td data-download-url="<?php echo $current_url_folder_change . (strpos($current_url_folder_change, "directory") ? "/$directory" : "?directory=$directory") ?>"
                                                class="name"><?php echo substr($directory, 0, 20) ?></td>
                                            <td data-download-url="<?php echo $current_url_folder_change . (strpos($current_url_folder_change, "directory") ? "/$directory" : "?directory=$directory") ?>"
                                                class="type d-none d-md-table-cell"><?php echo $directoryy['type'] ?></td>


                                            <td data-download-url="<?php echo $current_url_folder_change . (strpos($current_url_folder_change, "directory") ? "/$directory" : "?directory=$directory") ?>"
                                                class="size d-none d-sm-table-cell"><?php echo $directoryy['size'] ?></td>
                                            <td data-download-url="<?php echo $current_url_folder_change . (strpos($current_url_folder_change, "directory") ? "/$directory" : "?directory=$directory") ?>"
                                                class="last-modified d-none d-lg-table-cell">July 8, 2015
                                            </td>
                                            <td class="d-table-cell k_button_postion_td">
                                                <nav id="k_menu_5" class="k_menu_5">
                                                    <?php echo form_open_multipart($current_url_folder_change, ['id' => 'submit_here']); ?>
                                                    <input type="checkbox" href="#" class="k_menu-open_5"
                                                           name="k_menu-open_5" id="k_menu-open_5"/>
                                                    <label class="k_menu-open-button" for="k_menu-open_5">
                                                        <span><i class="k_menu_material-icons md-48">menu</i></span>
                                                    </label>
                                                    <div class="k_menu-item1" id="k_menu-one">
                                                        <a download href="<?php if ($_GET['directory']) {
                                                            $dir = $_GET['directory'];
                                                            echo site_url(); ?>uploads/<?php echo $dir;
                                                            echo '/';
                                                            echo $directory;
                                                        } else {
                                                            echo site_url(); ?>/uploads/<?php echo $directory;
                                                        } ?>" data-fuse-bar-toggle="file-manager-info-sidebar">
                                                            <i class="icon icon-cloud-download"></i>
                                                        </a>
                                                    </div>
                                                    <div class="k_menu-item1" id="k_menu-two">
                                                        <a id="rename-button" data-id="<?php echo $directory; ?>"
                                                           data-toggle="modal" type="button" data-target="#edit_folder"
                                                           data-fuse-bar-toggle="file-manager-info-sidebar"
                                                           aria-label="Edit file">
                                                            <i class="icon icon-pencil"></i>
                                                        </a>
                                                    </div>
                                                    <div class="k_menu-item1" id="k_menu-three">
                                                        <input type="hidden" name="delete_file"
                                                               value="<?php echo $directory; ?>">
                                                        <button type="submit"
                                                                data-fuse-bar-toggle="file-manager-info-sidebar">
                                                            <i class="icon icon-delete"></i>
                                                        </button>
                                                    </div>
                                                    <div class="k_menu-item1" id="k_menu-four">
                                                        <?php if ($count->count == 0) { ?>
                                                        <a id="testtt" data-id="<?php echo $directory; ?>"
                                                           data-toggle="modal" class="wis_lock"
                                                           data-target="#lock_file_folder"
                                                           data-fuse-bar-toggle="file-manager-info-sidebar" value="1">
                                                                <i class="icon icon-lock" style="color:#21a828;"></i>
                                                            </a><?php } else { ?>
                                                            <a id="testt" data-id="<?php echo $directory; ?>"
                                                               data-toggle="modal" class="wis_lock"
                                                               data-target="#lock_file_folder"
                                                               data-fuse-bar-toggle="file-manager-info-sidebar"
                                                               value="0">
                                                                <i class="icon icon-lock" style="color:#fff"></i>
                                                            </a>
                                                        <?php } ?>
                                                    </div>
                                                    <div class="k_menu-item1" id="k_menu-five">
                                                        <a id="share-link" data-id="<?php if ($_GET['directory']) {
                                                            $dir = $_GET['directory'];
                                                            echo site_url(); ?>uploads/<?php echo $dir;
                                                            echo '/';
                                                            echo $directory;
                                                        } else {
                                                            echo site_url(); ?>/uploads/<?php echo $directory;
                                                        } ?>" data-toggle="modal" type="button" class=""
                                                           data-target="#share_file"
                                                           data-fuse-bar-toggle="file-manager-info-sidebar"
                                                           aria-label="Edit file">
                                                            <i class="icon icon-plane"></i>
                                                        </a>
                                                    </div>
                                                    <?php echo form_close(); ?>
                                                </nav>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                                </tbody>
                            </table>
                            <!-- / LIST VIEW -->
                        </div>
                        <!-- / CONTENT -->
                    </div>

                    <aside class="page-sidebar custom-scrollbar" data-fuse-bar="file-manager-info-sidebar"
                           data-fuse-bar-position="right" data-fuse-bar-media-step="lg">
                        <!-- SIDEBAR HEADER -->
                        <div class="header bg-secondary text-auto d-flex flex-column justify-content-between p-6">

                            <!-- TOOLBAR -->
                            <div class="toolbar row no-gutters align-items-center justify-content-end">

                                <button type="button" class="btn btn-icon">
                                    <i class="icon-delete"></i>
                                </button>

                                <button type="button" class="btn btn-icon">
                                    <i class="icon icon-download"></i>
                                </button>

                                <button type="button" class="btn btn-icon">
                                    <i class="icon icon-dots-vertical"></i>
                                </button>

                            </div>
                            <!-- / TOOLBAR -->

                            <!-- INFO -->
                            <div>

                                <div class="title mb-2 head_wisdom"></div>

                                <div class="subtitle text-muted">
                                    <span>Edited</span>
                                    : May 8, 2017
                                </div>

                            </div>
                            <!-- / INFO-->

                        </div>
                        <!-- / SIDEBAR HEADER -->

                        <!-- SIDENAV CONTENT -->
                        <div class="content">

                            <div class="file-details">

                                <div class="preview file-icon row no-gutters align-items-center justify-content-center">
                                    <i class="icon-folder s-12"></i>
                                </div>

                                <div class="offline-switch row no-gutters align-items-center justify-content-between px-6 py-4">

                                    <span>Available Offline</span>

                                    <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input"
                                               aria-label="Toggle offline"/>
                                        <span class="custom-control-indicator"></span>
                                    </label>

                                </div>

                                <div class="title px-6 py-4">Info</div>

                                <table class="table">

                                    <tr class="type">
                                        <th class="pl-6">Type</th>
                                        <td>Folder</td>
                                    </tr>

                                    <tr class="size">
                                        <th class="pl-6">Size</th>
                                        <td>-</td>
                                    </tr>

                                    <tr class="location">
                                        <th class="pl-6">Location</th>
                                        <td>My Files > Documents</td>
                                    </tr>

                                    <tr class="owner">
                                        <th class="pl-6">Owner</th>
                                        <td>Me</td>
                                    </tr>

                                    <tr class="modified">
                                        <th class="pl-6">Modified</th>
                                        <td>April 8, 2017</td>
                                    </tr>

                                    <tr class="opened">
                                        <th class="pl-6">Opened</th>
                                        <td>April 8, 2017</td>
                                    </tr>

                                    <tr class="created">
                                        <th class="pl-6">Created</th>
                                        <td>April 8, 2017</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- / SIDENAV CONTENT -->
                    </aside>
                </div>


            </div>
        </div>
    </div>
</main>
<?php include_once(APPPATH . 'views/admin/filemanager/includes/add-folder-popup.php'); ?>
<?php init_tail(); ?>

<script src="<?php echo base_url('/assets/file_manager/js/single-page.js'); ?>"></script>
</body>
</html>
