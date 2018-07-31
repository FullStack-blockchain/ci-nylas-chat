<ul id="setup-menu-collapse"
    class="collapse <?php if ($this->session->has_userdata('setup-menu-open') && $this->session->userdata('setup-menu-open') == true) {
        echo 'display-block';
    } ?>"
    aria-expanded="false" role="tabpanel" aria-labelledby="heading-setup-menu" data-children=".nav-item">
    <?php
    $menu_active = get_option('setup_menu_active');
    $menu_active = json_decode($menu_active);
    $total_setup_items = count($menu_active->setup_menu_active);
    $m = 0;
    foreach ($menu_active->setup_menu_active as $item) {
        if (isset($item->permission) && !empty($item->permission)) {
            if (!has_permission($item->permission, '', 'view')) {
                $total_setup_items--;
                continue;
            }
        }
        $submenu = false;
        $remove_main_menu = false;
        $url = '';
        if (isset($item->children)) {
            $submenu = true;
            $total_sub_items_removed = 0;
            foreach ($item->children as $_sub_menu_check) {
                if (isset($_sub_menu_check->permission) && !empty($_sub_menu_check->permission)) {
                    if (!has_permission($_sub_menu_check->permission, '', 'view')) {
                        $total_sub_items_removed++;
                    }
                }
            }

            if ($total_sub_items_removed == count($item->children)) {
                $submenu = false;
                $remove_main_menu = true;
                $total_setup_items--;
            }
        } else {
            // child items removed
            if ($item->url == '#') {
                continue;
            }
            $url = $item->url;
        }
        if ($remove_main_menu == true) {
            continue;
        }
        $url = $item->url;
        if (!_startsWith($url, 'http://') && $url != '#') {
            $url = admin_url($url);
        }
        ?>
        <li class="nav-item">
            <a href="<?php echo $url; ?>" aria-expanded="false"
               class="nav-link ripple <?php echo ($submenu == true) ? 'with-arrow collapsed' : ''; ?>"
                <?php echo ($submenu == true) ? ' data-toggle="collapse" data-target="#sub-collapse-' . $item->id . '" href="#" aria-expanded="false" aria-controls="sub-collapse-' . $item->id . '"' : ''; ?>>
                <?php if (!empty($item->icon)) { ?><i class="<?php echo $item->icon; ?> menu-icon"></i><?php } ?>
                <span><?php echo _l($item->name, '', false); ?></span>
                <?php if ($submenu == true) { ?>
                    <span class="fa arrow"></span>
                <?php } ?>
            </a>
            <?php if (isset($item->children)) { ?>
                <ul id="sub-collapse-<?php echo $item->id; ?>" class="collapse" aria-expanded="false" role="tabpanel"
                    aria-labelledby="sub-heading-<?php echo $item->id; ?>" data-children=".nav-item">
                    <?php foreach ($item->children as $submenu) {
                        if (isset($submenu->permission) && !empty($submenu->permission)) {
                            if (!has_permission($submenu->permission, '', 'view')) {
                                continue;
                            }
                        }
                        $url = $submenu->url;
                        if (!_startsWith($url, 'http://')) {
                            $url = admin_url($url);
                        }
                        ?>
                        <li class="nav-item">
                            <a href="<?php echo $url; ?>" class="nav-link ripple">
                                <?php if (!empty($submenu->icon)) { ?>
                                    <i class="<?php echo $submenu->icon; ?> menu-icon"></i>
                                <?php } ?>
                                <span><?php echo _l($submenu->name, '', false); ?></span>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            <?php } ?>
        </li>
        <?php
        $m++;
        do_action('after_render_single_setup_menu', $m);
    }
    ?>
    <?php if (get_option('show_help_on_setup_menu') == 1 && is_admin()) {
        $total_setup_items++; ?>
        <li class="nav-item">
            <a href="<?php echo do_action('help_menu_item_link', 'https://help.perfexcrm.com'); ?>" class="nav-link ripple"
               target="_blank"><span><?php echo do_action('help_menu_item_text', _l('setup_help')); ?></span></a>
        </li>
    <?php } ?>
</ul>
<?php $this->app->set_setup_menu_visibility($total_setup_items); ?>
