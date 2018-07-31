<?php

defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Defined styling areas for the theme style feature
 * Those string are not translated to keep the language file neat
 * @param  string $type
 * @return array
 */
function get_styling_areas($type = 'admin')
{
    $areas = [
        'admin' => [
            [
                'name'                 => 'Sidebar Menu/Setup Menu Background Color',
                'id'                   => 'admin-menu',
                'target'               => '.bg-primary-700.text-auto',
                'css'                  => 'background-color',
                'additional_selectors' => '',
            ],
//            [
//                'name'                 => 'Sidebar Menu/Setup Menu Submenu Open Background Color',
//                'id'                   => 'admin-menu-submenu-open',
//                'target'               => '#sidenav li .nav-second-level li,#setup-menu li .nav-second-level li',
//                'css'                  => 'background',
//                'additional_selectors' => '',
//            ],
//            [
//                'name'                 => 'Sidebar Menu/Setup Menu Links Color',
//                'id'                   => 'admin-menu-links',
//                'target'               => '#sidenav li a,#setup-menu li a',
//                'css'                  => 'color',
//                'additional_selectors' => '',
//            ],
            [
                'name'                 => 'Sidebar Menu User Welcome Text Color',
                'id'                   => 'user-welcome-text-color',
                'target'               => '.welcome_user_text_color',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'   => 'Sidebar Menu/Setup Active Item Background Color',
                'id'     => 'admin-menu-active-item',
                'target' => '
                #sidenav li.active,
                #setup-menu li.active,
                #sidenav.nav > li:hover,
                #sidenav.nav > li:focus,
                #setup-menu > li:hover,
                #setup-menu > li:focus',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'   => 'Sidebar Menu/Setup Active Item Color',
                'id'     => 'admin-menu-active-item-color',
                'target' => '
                #sidenav li.active > a,
                #setup-menu li.active > a',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Sidebar Menu/Setup Active Subitem Background Color',
                'id'                   => 'admin-menu-active-subitem',
                'target'               => '#sidenav li.active > ul',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Sidebar Menu/Setup Submenu links color',
                'id'                   => 'admin-menu-submenu-links',
                'target'               => '#sidenav > li > ul > li > a',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Top Header Background Color',
                'id'                   => 'top-header',
                'target'               => 'nav#toolbar.bg-white',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Top Header Links Color',
                'id'                   => 'top-header-links',
                'target'               => '#toolbar .header-icon-button > i,#toolbar .language-button .dropdown-toggle .align-items-center span.d-none',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
        ],
        'customers' => [
            [
                'name'                 => 'Navigation Background Color',
                'id'                   => 'customers-navigation',
                'target'               => '.customers .navbar-default',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Navigation Links Color',
                'id'                   => 'customers-navigation-links',
                'target'               => '.customers .navbar-default .navbar-nav>li>a',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Invoice/Estimate HTML View Top Header Background Color',
                'id'                   => 'html-view-top-header',
                'target'               => '.viewinvoice .page-pdf-html-logo,.viewestimate .page-pdf-html-logo',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Footer Background',
                'id'                   => 'customers-footer-background',
                'target'               => '.customers footer',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Footer Text Color',
                'id'                   => 'customers-footer-text',
                'target'               => '.customers footer',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
        ],
        'general' => [
            [
                'name'                 => '<a href="#" onclick="return false;">Links</a> Color (href)',
                'id'                   => 'links-color',
                'target'               => 'a',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Links Hover/Focus Color',
                'id'                   => 'links-hover-focus',
                'target'               => 'a:hover,a:focus',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Table Headings Color',
                'id'                   => 'table-headings',
                'target'               => 'table.dataTable thead tr>th, .table.dataTable>thead:first-child>tr:first-child>th',
                'css'                  => 'color',
                'additional_selectors' => '',
                'example'              => '<table class="table dataTable"><thead><tr><th style="border-bottom: 1px solid #f0f0f0" class="sorting">Example Heading 1</th><th style="border-bottom: 1px solid #f0f0f0" class="sorting">Example Heading 2</th></tr></thead></table>',
            ],
            [
                'name'                 => 'Items Table Headings Background Color',
                'id'                   => 'table-items-heading',
                'target'               => '.table.items thead',
                'css'                  => 'background',
                'additional_selectors' => '',
                'example'              => '<table class="table items"><thead><tr><th>Example Heading 1</th><th>Example Heading 2</th></tr></thead></table>',
            ],
            [
                'name'                 => 'Admin Login Background',
                'id'                   => 'admin-login-background',
                'target'               => 'body.login_admin',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Text Muted',
                'id'                   => 'text-muted',
                'target'               => '.text-muted',
                'css'                  => 'color',
                'additional_selectors' => '',
                'example'              => '<p>Example <span class="bold text-muted">text muted</span></p>',
            ],
            [
                'name'                 => 'Text Danger',
                'id'                   => 'text-danger',
                'target'               => '.text-danger',
                'css'                  => 'color',
                'additional_selectors' => '',
                'example'              => '<p>Example <span class="bold text-danger">text danger</span></p>',
            ],
            [
                'name'                 => 'Text Warning',
                'id'                   => 'text-warning',
                'target'               => '.text-warning',
                'css'                  => 'color',
                'additional_selectors' => '',
                'example'              => '<p>Example <span class="bold text-warning">text warning</span></p>',
            ],
            [
                'name'                 => 'Text Info',
                'id'                   => 'text-info',
                'target'               => '.text-info',
                'css'                  => 'color',
                'additional_selectors' => '',
                'example'              => '<p>Example <span class="bold text-info">text info</span></p>',
            ],
            [
                'name'                 => 'Text Success',
                'id'                   => 'text-success',
                'target'               => '.text-success',
                'css'                  => 'color',
                'additional_selectors' => '',
                'example'              => '<p>Example <span class="bold text-success">text success</span></p>',
            ],
        ],
        'tabs' => [
            [
                'name'                 => 'Tabs Background Color',
                'id'                   => 'tabs-bg',
                'target'               => '.nav-tabs',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Tabs Links Color',
                'id'                   => 'tabs-links',
                'target'               => '.nav-tabs>li>a',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Tabs Link Active/Hover Color',
                'id'                   => 'tabs-links-active-hover',
                'target'               => '.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover, .nav-tabs>li>a:focus, .nav-tabs>li>a:hover',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],

            [
                'name'                 => 'Tabs Active Border Color',
                'id'                   => 'tabs-active-border',
                'target'               => '.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover, .nav-tabs>li>a:focus, .nav-tabs>li>a:hover',
                'css'                  => 'border-bottom-color',
                'additional_selectors' => '',
            ],
        ],
        'modals' => [
            [
                'name'                 => 'Heading Background',
                'id'                   => 'modal-heading',
                'target'               => '.modal-header',
                'css'                  => 'background',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Heading Color',
                'id'                   => 'modal-heading-color',
                'target'               => '.modal-header .modal-title',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Close Button Color',
                'id'                   => 'modal-close-button-color',
                'target'               => '.modal-header .close',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
            [
                'name'                 => 'Modal White Text Color',
                'id'                   => 'modal-header-white-text-color',
                'target'               => '.modal-header .color-white',
                'css'                  => 'color',
                'additional_selectors' => '',
            ],
        ],
        'buttons' => [
            [
                'name'                 => 'Button Default',
                'id'                   => 'btn-default',
                'target'               => '.btn-default',
                'css'                  => 'background-color',
                'additional_selectors' => '.btn-default|border-color',
                'example'              => '<button type="button" class="btn btn-default">Button Default</button>',
            ],
            [
                'name'                 => 'Button Secondary',
                'id'                   => 'btn-secondary',
                'target'               => '.btn-secondary',
                'css'                  => 'background-color',
                'additional_selectors' => '.btn-secondary|border-color',
                'example'              => '<button type="button" class="btn btn-secondary">Button Secondary</button>',
            ],
            [
                'name'                 => 'Button Info',
                'id'                   => 'btn-info',
                'target'               => '.btn-info',
                'css'                  => 'background-color',
                'additional_selectors' => '.btn-info|border-color',
                'example'              => '<button type="button" class="btn btn-info">Button Info</button>',
            ],
            [
                'name'                 => 'Button Success',
                'id'                   => 'btn-success',
                'target'               => '.btn-success',
                'css'                  => 'background-color',
                'additional_selectors' => '.btn-success|border-color',
                'example'              => '<button type="button" class="btn btn-success">Button Success</button>',
            ],
            [
                'name'                 => 'Button Danger',
                'id'                   => 'btn-danger',
                'target'               => '.btn-danger',
                'css'                  => 'background-color',
                'additional_selectors' => '.btn-danger|border-color',
                'example'              => '<button type="button" class="btn btn-danger">Button Danger</button>',
            ],
        ],
    ];


    $CI   = & get_instance();
    $tags = get_tags();

    $areas['tags'] = [];

    foreach ($tags as $tag) {
        array_push($areas['tags'], [
                'name'                 => $tag['name'],
                'id'                   => 'tag-' . $tag['id'],
                'target'               => '.tag-id-' . $tag['id'],
                'css'                  => 'color',
                'additional_selectors' => '.tag-id-' . $tag['id'] . '|border-color+ul.tagit li.tagit-choice-editable.tag-id-' . $tag['id'] . '|border-color+ul.tagit li.tagit-choice.tag-id-' . $tag['id'] . ' .tagit-label:not(a)|color',
                'example'              => '<span class="label label-tag tag-id-' . $tag['id'] . '">' . $tag['name'] . '</span>',
            ]);
    }

    $areas = do_action('get_styling_areas', $areas);
    if (!is_array($type)) {
        return $areas[$type];
    }
    $_areas = [];
    foreach ($type as $t) {
        $_areas[] = $areas[$t];
    }

    return $_areas;
}
/**
 * Will fetch from database the stored applied styles and return
 * @return object
 */
function get_applied_styling_area()
{
    $theme_style = get_option('theme_style');
    if ($theme_style == '') {
        return [];
    }
    $theme_style = json_decode($theme_style);

    return $theme_style;
}
/**
 * Function that will parse and render the applied styles
 * @param  string $type
 * @return void
 */
function render_custom_styles($type)
{
    $theme_style   = get_applied_styling_area();
    $styling_areas = get_styling_areas($type);


    foreach ($styling_areas as $type => $area) {
        foreach ($area as $_area) {
            foreach ($theme_style as $applied_style) {
                if ($applied_style->id == $_area['id']) {
                    echo '<style class="custom_style_' . $_area['id'] . '">' . PHP_EOL;
                    echo $_area['target'] . '{' . PHP_EOL;
                    echo $_area['css'] . ':' . $applied_style->color . ' !important;' . PHP_EOL;
                    echo '}' . PHP_EOL;
                    if (_startsWith($_area['target'], '.btn')) {
                        echo '
                        ' . $_area['target'] . ':focus,' . $_area['target'] . '.focus,' . $_area['target'] . ':hover,' . $_area['target'] . ':active,
                        ' . $_area['target'] . '.active,
                        .open > .dropdown-toggle' . $_area['target'] . ',' . $_area['target'] . ':active:hover,
                        ' . $_area['target'] . '.active:hover,
                        .open > .dropdown-toggle' . $_area['target'] . ':hover,
                        ' . $_area['target'] . ':active:focus,
                        ' . $_area['target'] . '.active:focus,
                        .open > .dropdown-toggle' . $_area['target'] . ':focus,
                        ' . $_area['target'] . ':active.focus,
                        ' . $_area['target'] . '.active.focus,
                        .open > .dropdown-toggle' . $_area['target'] . '.focus,
                        ' . $_area['target'] . ':active,
                        ' . $_area['target'] . '.active,
                        .open > .dropdown-toggle' . $_area['target'] . '{background-color:' . adjust_color_brightness($applied_style->color, -50) . ';color:#fff;border-color:'.adjust_color_brightness($applied_style->color, -50).'}';
                        echo '
                        ' . $_area['target'] . '.disabled,
                        ' . $_area['target'] . '[disabled],
                        fieldset[disabled] ' . $_area['target'] . ',
                        ' . $_area['target'] . '.disabled:hover,
                        ' . $_area['target'] . '[disabled]:hover,
                        fieldset[disabled] ' . $_area['target'] . ':hover,
                        ' . $_area['target'] . '.disabled:focus,
                        ' . $_area['target'] . '[disabled]:focus,
                        fieldset[disabled] ' . $_area['target'] . ':focus,
                        ' . $_area['target'] . '.disabled.focus,
                        ' . $_area['target'] . '[disabled].focus,
                        fieldset[disabled] ' . $_area['target'] . '.focus,
                        ' . $_area['target'] . '.disabled:active,
                        ' . $_area['target'] . '[disabled]:active,
                        fieldset[disabled] ' . $_area['target'] . ':active,
                        ' . $_area['target'] . '.disabled.active,
                        ' . $_area['target'] . '[disabled].active,
                        fieldset[disabled] ' . $_area['target'] . '.active {
                            background-color: ' . adjust_color_brightness($applied_style->color, 50) . ';color:#fff;border-color:'.adjust_color_brightness($applied_style->color, 50).';}';
                    }
                    if ($_area['additional_selectors'] != '') {
                        $additional_selectors = explode('+', $_area['additional_selectors']);
                        foreach ($additional_selectors as $as) {
                            $_temp = explode('|', $as);
                            echo $_temp[0] . ' {' . PHP_EOL;
                            echo $_temp[1] . ':' . $applied_style->color . ' !important;' . PHP_EOL;
                            echo '}' . PHP_EOL;
                        }
                    }
                    echo '</style>' . PHP_EOL;
                }
            }
        }
    }
}
/**
 * Get selected value for some styling area for the Theme style feature
 * @param  string $type
 * @param  string $selector
 * @return string
 */
function get_custom_style_values($type, $selector)
{
    $value         = '';
    $theme_style   = get_applied_styling_area();
    $styling_areas = get_styling_areas($type);
    foreach ($styling_areas as $area) {
        if ($area['id'] == $selector) {
            foreach ($theme_style as $applied_style) {
                if ($applied_style->id == $selector) {
                    $value = $applied_style->color;

                    break;
                }
            }
        }
    }

    return $value;
}
