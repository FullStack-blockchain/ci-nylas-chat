<?php
$total_qa_removed = 0;
$quickActions = $this->app->get_quick_actions_links();
foreach ($quickActions as $key => $item) {
    if (isset($item['permission'])) {
        if (!has_permission($item['permission'], '', 'create')) {
            $total_qa_removed++;
        }
    }
}
?>
<style type="text/css">
.aside-left #sidenav li.active,.aside-left #setup-menu li.active,.aside-left #sidenav.nav > li:hover,.aside-left #sidenav.nav > li:focus,.aside-left #setup-menu > li:hover,.aside-left #setup-menu > li:focus{
    background:none!important;
}
.layout #wrapper>.aside>.aside-content>#sidenav .nav-link:hover, .layout #wrapper>.aside>.aside-content>#sidenav li.active .nav-link, .layout #wrapper>.aside>.aside-content>#sidenav .nav-link:focus {
    background-color: rgba(255,255,255,0.1) !important;
}
</style>
<aside id="aside" class="aside aside-left" data-fuse-bar="aside" data-fuse-bar-media-step="md"
       data-fuse-bar-position="left">
    <div class="aside-content bg-primary-700 text-auto" style="background: #0a4763!important">

        <div class="aside-toolbar">

            <div class="logo">
                <?php get_company_logo(get_admin_uri() . '/') ?>
            </div>

            <button id="toggle-fold-aside-button" type="button" class="btn btn-icon d-none d-lg-block"
                    data-fuse-aside-toggle-fold data-toggle="tooltip" title="Nav Toggle" data-placement="bottom">
                <i class="icon icon-backburger"></i>
            </button>

        </div>

        <ul class="nav flex-column custom-scrollbar" id="sidenav" data-children=".nav-item">

            <li class="subheader nav-item">
                <!--<span class="quick-links">
                    <?php /*if ($total_qa_removed != count($quickActions)) { */?>
                        <div class="dropdown">
                    <a href="#" class="dropdown-toggle ripple row align-items-center justify-content-center no-gutters"
                       id="dropdownQuickLinks" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <i class="fa fa-gavel" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownQuickLinks">
                        <?php
/*                        foreach ($quickActions as $key => $item) {
                            $url = '';
                            if (isset($item['permission'])) {
                                if (!has_permission($item['permission'], '', 'create')) {
                                    continue;
                                }
                            }
                            if (isset($item['custom_url'])) {
                                $url = $item['url'];
                            } else {
                                $url = admin_url('' . $item['url']);
                            }
                            $href_attributes = '';
                            if (isset($item['href_attributes'])) {
                                foreach ($item['href_attributes'] as $key => $val) {
                                    $href_attributes .= $key . '=' . '"' . $val . '"';
                                }
                            }
                            */?>
                            <li>
                                <a class="dropdown-item" href="<?php /*echo $url; */?>" <?php /*echo $href_attributes; */?>>
                                    <i class="fa fa-plus-square-o"></i>
                                    <?php /*echo $item['name']; */?></a>
                            </li>
                        <?php /*} */?>
                    </ul>
                </div>
                    <?php /*} */?>
                </span>-->
                <span class="welcome_user_text_color">
                    <?php echo _l('welcome_top', $current_user->firstname); ?>
                </span>
                <span class="logout">
                    <i class="icon s-6 icon-logout welcome_user_text_color" data-toggle="tooltip" title="<?php echo _l('nav_logout'); ?>"
                       data-placement="bottom" onclick="logout(); return false;"></i>
                </span>
            </li>

            <?php
            do_action('before_render_aside_menu');
            $menu_active = get_option('aside_menu_active');
            $menu_active = json_decode($menu_active);
            $m = 0;
            foreach ($menu_active->aside_menu_active as $item) {
                if ($item->id == 'tickets' && (get_option('access_tickets_to_none_staff_members') == 0 && !is_staff_member())) {
                    continue;
                } elseif ($item->id == 'customers') {
                    if (!has_permission('customers', '', 'view') && (have_assigned_customers() || (!have_assigned_customers() && has_permission('customers', '', 'create')))) {
                        $item->permission = '';
                    }
                } elseif ($item->id == 'child-proposals') {
                    if ((staff_has_assigned_proposals()
                            && get_option('allow_staff_view_proposals_assigned') == 1)
                        && (!has_permission('proposals', '', 'view')
                            && !has_permission('proposals', '', 'view_own'))) {
                        $item->permission = '';
                    }
                } elseif ($item->id == 'child-invoices') {
                    if ((staff_has_assigned_invoices() && get_option('allow_staff_view_invoices_assigned') == 1)
                        && (!has_permission('invoices', '', 'view')
                            && !has_permission('invoices', '', 'view_own'))) {
                        $item->permission = '';
                    }
                } elseif ($item->id == 'child-estimates') {
                    if ((staff_has_assigned_estimates() && get_option('allow_staff_view_estimates_assigned') == 1)
                        && (!has_permission('estimates', '', 'view')
                            && !has_permission('estimates', '', 'view_own'))) {
                        $item->permission = '';
                    }
                }
                if (!empty($item->permission)
                    && !has_permission($item->permission, '', 'view')
                    && !has_permission($item->permission, '', 'view_own')) {
                    continue;
                }
                $submenu = false;
                $remove_main_menu = false;
                $url = '';
                if (isset($item->children)) {
                    $submenu = true;
                    $total_sub_items_removed = 0;
                    foreach ($item->children as $_sub_menu_check) {
                        if (!empty($_sub_menu_check->permission)
                            && ($_sub_menu_check->permission != 'payments'
                                && $_sub_menu_check->permission != 'tickets'
                                && $_sub_menu_check->permission != 'customers'
                                && $_sub_menu_check->permission != 'proposals'
                                && $_sub_menu_check->permission != 'payments'
                                && $_sub_menu_check->permission != 'estimates'
                                && $_sub_menu_check->permission != 'invoices')
                        ) {
                            if (!has_permission($_sub_menu_check->permission, '', 'view')
                                && !has_permission($_sub_menu_check->permission, '', 'view_own')) {
                                $total_sub_items_removed++;
                            }
                        } elseif ($_sub_menu_check->permission == 'payments' && (!has_permission('payments', '', 'view') && !has_permission('invoices', '', 'view_own') && (get_option('allow_staff_view_invoices_assigned') == 0
                                    || (get_option('allow_staff_view_invoices_assigned') == 1 && !staff_has_assigned_invoices())))) {
                            $total_sub_items_removed++;
                        } elseif ($_sub_menu_check->id == 'tickets' && (get_option('access_tickets_to_none_staff_members') == 0 && !is_staff_member())) {
                            $total_sub_items_removed++;
                        } elseif ($_sub_menu_check->id == 'customers') {
                            if (!has_permission('customers', '', 'view') && !have_assigned_customers() && !has_permission('customers', '', 'create')) {
                                $total_sub_items_removed++;
                            }
                        } elseif ($_sub_menu_check->id == 'child-proposals') {
                            if ((get_option('allow_staff_view_proposals_assigned') == 0
                                    || (get_option('allow_staff_view_proposals_assigned') == 1 && !staff_has_assigned_proposals()))
                                && !has_permission('proposals', '', 'view')
                                && !has_permission('proposals', '', 'view_own')) {
                                $total_sub_items_removed++;
                            }
                        } elseif ($_sub_menu_check->id == 'child-invoices') {
                            if ((get_option('allow_staff_view_invoices_assigned') == 0
                                    || (get_option('allow_staff_view_invoices_assigned') == 1 && !staff_has_assigned_invoices()))
                                && !has_permission('invoices', '', 'view')
                                && !has_permission('invoices', '', 'view_own')) {
                                $total_sub_items_removed++;
                            }
                        } elseif ($_sub_menu_check->id == 'child-estimates') {
                            if ((get_option('allow_staff_view_estimates_assigned') == 0
                                    || (get_option('allow_staff_view_estimates_assigned') == 1 && !staff_has_assigned_estimates()))
                                && !has_permission('estimates', '', 'view')
                                && !has_permission('estimates', '', 'view_own')) {
                                $total_sub_items_removed++;
                            }
                        }
                    }
                    if ($total_sub_items_removed == count($item->children)) {
                        $submenu = false;
                        $remove_main_menu = true;
                    }
                } else {
                    if ($item->url == '#') {
                        continue;
                    }
                    $url = $item->url;
                }
                if ($remove_main_menu == true) {
                    continue;
                }
                $url = $item->url;
                if (!_startsWith($url, 'http://') && !_startsWith($url, 'https://') && $url != '#') {
                    $url = admin_url($url);
                }
                ?>
                <li class="nav-item menu-item-<?php echo $item->id; ?>">
                    <a href="<?php echo $url; ?>" aria-expanded="false" class="nav-link ripple <?php echo ($submenu == true)?'with-arrow collapsed':''; ?>"
                        <?php echo ($submenu == true) ? ' data-toggle="collapse" data-target="#collapse-' . $item->id . '" href="#" aria-expanded="false" aria-controls="collapse-' . $item->id . '"' : ''; ?>>
                        <i class="<?php echo $item->icon; ?> menu-icon"></i>
                        <span><?php echo _l($item->name); ?></span>
                        <?php if ($submenu == true) { ?>
                            <span class="fa arrow"></span>
                        <?php } ?>
                    </a>
                    <?php if (isset($item->children)) { ?>
                        <ul id="collapse-<?php echo $item->id; ?>" class="collapse" aria-expanded="false" role="tabpanel" aria-labelledby="heading-<?php echo $item->id; ?>" data-children=".nav-item">
                            <?php foreach ($item->children as $submenu) {
                                if (
                                    !empty($submenu->permission)
                                    && ($submenu->permission != 'payments'
                                        && $submenu->permission != 'tickets'
                                        && $submenu->permission != 'proposals'
                                        && $submenu->permission != 'invoices'
                                        && $submenu->permission != 'estimates'
                                        && $submenu->permission != 'customers')
                                    && (!has_permission($submenu->permission, '', 'view') && !has_permission($submenu->permission, '', 'view_own'))
                                ) {
                                    continue;
                                } elseif (
                                    $submenu->permission == 'payments'
                                    && (!has_permission('payments', '', 'view') && !has_permission('invoices', '', 'view_own') && (get_option('allow_staff_view_invoices_assigned') == 0
                                            || (get_option('allow_staff_view_invoices_assigned') == 1 && !staff_has_assigned_invoices())))
                                ) {
                                    continue;
                                } elseif ($submenu->id == 'tickets' && (get_option('access_tickets_to_none_staff_members') == 0 && !is_staff_member())) {
                                    continue;
                                } elseif ($submenu->id == 'customers') {
                                    if (!has_permission('customers', '', 'view') && !have_assigned_customers() && !has_permission('customers', '', 'create')) {
                                        continue;
                                    }
                                } elseif ($submenu->id == 'child-proposals') {
                                    if ((get_option('allow_staff_view_proposals_assigned') == 0
                                            || (get_option('allow_staff_view_proposals_assigned') == 1 && !staff_has_assigned_proposals()))
                                        && !has_permission('proposals', '', 'view')
                                        && !has_permission('proposals', '', 'view_own')) {
                                        continue;
                                    }
                                } elseif ($submenu->id == 'child-invoices') {
                                    if ((get_option('allow_staff_view_invoices_assigned') == 0
                                            || (get_option('allow_staff_view_invoices_assigned') == 1 && !staff_has_assigned_invoices()))
                                        && !has_permission('invoices', '', 'view')
                                        && !has_permission('invoices', '', 'view_own')) {
                                        continue;
                                    }
                                } elseif ($submenu->id == 'child-estimates') {
                                    if ((get_option('allow_staff_view_estimates_assigned') == 0
                                            || (get_option('allow_staff_view_estimates_assigned') == 1 && !staff_has_assigned_estimates()))
                                        && !has_permission('estimates', '', 'view')
                                        && !has_permission('estimates', '', 'view_own')) {
                                        continue;
                                    }
                                }
                                $url = $submenu->url;
                                if (!_startsWith($url, 'http://') && !_startsWith($url, 'https://')) {
                                    $url = admin_url($url);
                                }
                                ?>
                                <li class="nav-item sub-menu-item-<?php echo $submenu->id; ?>"><a href="<?php echo $url; ?>" class="nav-link ripple">
                                        <?php if (!empty($submenu->icon)) { ?>
                                            <i class="<?php echo $submenu->icon; ?> menu-icon"></i>
                                        <?php } ?>
                                        <span><?php echo _l($submenu->name); ?></span>
                                    </a>
                                </li>
                            <?php } ?>
                        </ul>
                    <?php } ?>
                </li>
                <?php
                $m++;
                do_action('after_render_single_aside_menu', $m); ?>
            <?php } ?>
            <?php if ((is_staff_member() || is_admin()) && $this->app->show_setup_menu() == true){ ?>
            <li<?php if (get_option('show_setup_menu_item_only_on_hover') == 1) {
                echo ' style="display:none;"';
            } ?> class="nav-item" id="setup-menu-item">
                <a href="#" aria-expanded="false" class="nav-link ripple with-arrow collapsed" data-toggle="collapse" data-target="#setup-menu-collapse" aria-expanded="false" aria-controls="setup-menu-collapse">
                    <i class="fa fa-cog menu-icon"></i>
                    <span><?php echo _l('setting_bar_heading'); ?></span>
                    <span class="fa arrow"></span>
                </a>
                <?php
                include_once(APPPATH.'views/admin/includes_fuse/setup_menu.php');
            } ?>
            </li>
            <?php do_action('after_render_aside_menu'); ?>
            <?php
            $pinnedProjects = get_user_pinned_projects();
            if (count($pinnedProjects) > 0) { ?>
                <li class="pinned-separator"></li>
                <?php foreach ($pinnedProjects as $pinnedProject) { ?>
                    <li class="pinned_project">
                        <a href="<?php echo admin_url('projects/view/' . $pinnedProject['id']); ?>"
                           data-toggle="tooltip"
                           data-title="<?php echo _l('pinned_project'); ?>"><?php echo $pinnedProject['name']; ?><br>
                            <small><?php echo $pinnedProject["company"]; ?></small>
                        </a>
                        <div class="col-md-12">
                            <div class="progress progress-bar-mini">
                                <div class="progress-bar no-percent-text not-dynamic" role="progressbar"
                                     data-percent="<?php echo $pinnedProject['progress']; ?>"
                                     style="width: <?php echo $pinnedProject['progress']; ?>%;">
                                </div>
                            </div>
                        </div>
                    </li>
                <?php } ?>
            <?php } ?>


        </ul>
    </div>

</aside>