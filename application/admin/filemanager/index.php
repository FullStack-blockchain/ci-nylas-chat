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

                                <!-- ADD FILE BUTTON -->
                                <button id="add-file-button" type="button" class="btn btn-danger btn-fab" aria-label="Add file">
                                    <i class="icon icon-plus"></i>
                                </button>
                                <!-- / ADD FILE BUTTON -->

                            </div>
                            <!-- / HEADER -->

                            <!-- CONTENT -->
                            <div class="page-content custom-scrollbar">
                                <!-- LIST VIEW -->
                                <table class="table list-view">

                                    <thead>

                                        <tr>
                                            <th></th>
                                            <th>Name</th>
                                            <th class="d-none d-md-table-cell">Type</th>
                                            <th class="d-none d-sm-table-cell">Owner</th>
                                            <th class="d-none d-sm-table-cell">Size</th>
                                            <th class="d-none d-lg-table-cell">Last Modified</th>
                                            <th class="d-table-cell d-xl-none"></th>
                                        </tr>

                                    </thead>

                                    <tbody>

                                        <tr>
                                            <td class="file-icon">
                                                <i class="icon-folder"></i>
                                            </td>
                                            <td class="name">Work Documents</td>
                                            <td class="type d-none d-md-table-cell">folder</td>
                                            <td class="owner d-none d-sm-table-cell">me</td>
                                            <td class="size d-none d-sm-table-cell"></td>
                                            <td class="last-modified d-none d-lg-table-cell">July 8, 2015</td>
                                            <td class="d-table-cell d-xl-none">
                                                <button type="button" class="btn btn-icon" data-fuse-bar-toggle="file-manager-info-sidebar">
                                                    <i class="icon icon-information-outline"></i>
                                                </button>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                                <!-- / LIST VIEW -->
                            </div>
                            <!-- / CONTENT -->
                        </div>

                        <aside class="page-sidebar custom-scrollbar" data-fuse-bar="file-manager-info-sidebar" data-fuse-bar-position="right" data-fuse-bar-media-step="lg">
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

                                    <div class="title mb-2">Work Documents</div>

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
                                            <input type="checkbox" class="custom-control-input" aria-label="Toggle offline" />
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
<?php init_tail(); ?>
<script>
   
</script>
</body>
</html>
