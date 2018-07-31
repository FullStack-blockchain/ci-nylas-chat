<?php init_single_head();
$has_permission_edit = has_permission('knowledge_base', '', 'edit');
$has_permission_create = has_permission('knowledge_base', '', 'create');
?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url('assets/css/knowledge-base.css'); ?>">
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="articles-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="_buttons">
                                    <?php if ($has_permission_create) { ?>
                                        <a href="<?php echo admin_url('knowledge_base/article'); ?>"
                                           class="btn btn-secondary mright5"><?php echo _l('kb_article_new_article'); ?></a>
                                    <?php } ?>
                                    <?php if ($has_permission_edit || $has_permission_create) { ?>
                                        <a href="<?php echo admin_url('knowledge_base/manage_groups'); ?>"
                                           class="btn btn-secondary mright5"><?php echo _l('als_kb_groups'); ?></a>
                                    <?php } ?>
                                    <a href="#" class="btn btn-default hidden-xs toggle-articles-list"
                                       style="min-width: auto !important;"
                                       onclick="initKnowledgeBaseTableArticles(); return false;">
                                        <i class="fa fa-th-list line-height-25"></i>
                                    </a>
                                    <div class="btn-group pull-right mleft4 btn-with-tooltip-group _filter_data hide"
                                         data-toggle="tooltip" data-title="<?php echo _l('filter_by'); ?>">
                                        <button type="button" class="btn btn-default dropdown-toggle"
                                                data-toggle="dropdown" style="min-height: auto !important;"
                                                aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-filter line-height-25" aria-hidden="true"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-left" style="width:300px;">
                                            <li class="active">
                                                <a href="#" data-cview="all"
                                                   onclick="dt_custom_view('','.table-articles',''); return false;"><?php echo _l('view_articles_list_all'); ?></a>
                                            </li>
                                            <?php foreach ($groups as $group) { ?>
                                                <li><a href="#"
                                                       data-cview="kb_group_<?php echo $group['groupid']; ?>"
                                                       onclick="dt_custom_view('kb_group_<?php echo $group['groupid']; ?>','.table-articles','kb_group_<?php echo $group['groupid']; ?>'); return false;"><?php echo $group['name']; ?></a>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <div class="_hidden_inputs _filters">
                                        <?php foreach ($groups as $group) {
                                            echo form_hidden('kb_group_' . $group['groupid']);
                                        } ?>
                                    </div>
                                </div>
                                <hr class="hr-panel-heading"/>
                                <div class="row">
                                    <div class="tab-content col-md-12 p-0">
                                        <div role="tabpanel" class="tab-pane kb-kan-ban kan-ban-tab" id="kan-ban">
                                            <div class="container-fluid">
                                                <?php
                                                if (count($groups) == 0) {
                                                    echo _l('kb_no_articles_found');
                                                }
                                                foreach ($groups as $group) {
                                                    $kanban_colors = '';
                                                    foreach (get_system_favourite_colors() as $color) {
                                                        $color_selected_class = 'cpicker-small';
                                                        $kanban_colors .= "<div class='kanban-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ";border:1px solid " . $color . "'></div>";
                                                    }
                                                    ?>
                                                    <ul class="kan-ban-col<?php if (!$has_permission_edit) {
                                                        echo ' sortable-disabled';
                                                    } ?>" data-col-group-id="<?php echo $group['groupid']; ?>">
                                                        <li class="kan-ban-col-wrapper">
                                                            <div class="border-right panel_s">
                                                                <?php
                                                                $group_color = 'style="background:' . $group["color"] . ';border:1px solid ' . $group['color'] . '"';
                                                                ?>
                                                                <div class="panel-heading-bg primary-bg" <?php echo $group_color; ?>
                                                                     data-group-id="<?php echo $group['groupid']; ?>">
                                                                    <?php if ($has_permission_edit) { ?>
                                                                        <i class="fa fa-reorder pointer color-white"></i> <?php } ?>
                                                                    <a href="#" class="color-white"
                                                                       <?php if ($has_permission_create || $has_permission_edit){ ?>onclick="edit_kb_group(this,<?php echo $group['groupid']; ?>); return false;"
                                                                       data-name="<?php echo $group['name']; ?>"
                                                                       data-slug="<?php echo $group['group_slug']; ?>"
                                                                       data-color="<?php echo $group['color']; ?>"
                                                                       data-description="<?php echo clear_textarea_breaks($group['description']); ?>"
                                                                       data-order="<?php echo $group['group_order']; ?>"
                                                                       data-active="<?php echo $group['active']; ?>" <?php } ?>><?php echo $group['name']; ?></a>
                                                                    <small>
                                                                        - <?php echo total_rows('tblknowledgebase', 'articlegroup=' . $group['groupid']); ?></small>
                                                                    <?php if ($has_permission_edit) { ?>
                                                                        <a href="#" onclick="return false;"
                                                                           class="pull-right color-white kanban-color-picker"
                                                                           data-placement="bottom"
                                                                           data-toggle="popover"
                                                                           data-content="<div class='kan-ban-settings cpicker-wrapper'><?php echo $kanban_colors; ?></div>"
                                                                           data-html="true" data-trigger="focus"><i
                                                                                    class="fa fa-angle-down color-white"></i>
                                                                        </a>
                                                                    <?php } ?>
                                                                </div>
                                                                <?php
                                                                $this->db->select('*, (SELECT COUNT(*) FROM tblviewstracking WHERE rel_type="kb_article" AND rel_id=tblknowledgebase.articleid) as total_views')->from('tblknowledgebase')->where('articlegroup', $group['groupid'])->order_by('article_order', 'asc');
                                                                if (!$has_permission_create && !$has_permission_edit) {
                                                                    $this->db->where('active', 1);
                                                                }
                                                                $articles = $this->db->get()->result_array();
                                                                ?>
                                                                <div class="kan-ban-content-wrapper">
                                                                    <div class="kan-ban-content">
                                                                        <ul class="sortable article-group groups<?php if (!$has_permission_edit) {
                                                                            echo 'sortable-disabled';
                                                                        } ?>"
                                                                            data-group-id="<?php echo $group['groupid']; ?>">
                                                                            <?php foreach ($articles as $article) { ?>
                                                                                <li class="<?php if ($article['active'] == 0) {
                                                                                    echo 'line-throught';
                                                                                } ?>"
                                                                                    data-article-id="<?php echo $article['articleid']; ?>">
                                                                                    <div class="panel-body mat-white-bg">
                                                                                        <?php if ($article['staff_article'] == 1){ ?>
                                                                                        <a href="<?php echo admin_url('knowledge_base/view/' . $article['slug']); ?>">
                                                                                            <?php } else { ?>
                                                                                            <a href="<?php echo site_url('knowledge-base/article/' . $article['slug']); ?>"
                                                                                               target="_blank">
                                                                                                <?php } ?><?php echo $article['subject']; ?></a>
                                                                                            <?php if ($has_permission_edit) { ?>
                                                                                                <a href="<?php echo admin_url('knowledge_base/article/' . $article['articleid']); ?>"
                                                                                                   target="_blank"
                                                                                                   class="pull-right"><span><i
                                                                                                                class="fa fa-pencil-square-o"
                                                                                                                aria-hidden="true"></i></span></a>
                                                                                            <?php } ?>
                                                                                            <div class="clearfix"></div>
                                                                                            <hr/>
                                                                                            <p class="pull-left">
                                                                                                <small><?php echo _l('article_total_views'); ?>
                                                                                                    : <?php echo $article['total_views']; ?></small>
                                                                                            </p>
                                                                                            <?php if ($article['staff_article'] == 1) { ?>
                                                                                                <span class="label label-default pull-right"><?php echo _l('internal_article'); ?></span>
                                                                                            <?php } ?>
                                                                                    </div>
                                                                                </li>
                                                                            <?php } ?>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                        </li>
                                                    </ul>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div role="tabpanel" class="tab-pane active" id="list_tab">
                                            <div class="col-md-12">
                                                <?php render_datatable(
                                                    array(
                                                        _l('kb_dt_article_name'),
                                                        _l('kb_dt_group_name'),
                                                        _l('date_published'),
                                                    ), 'articles', [], [
                                                    'data-last-order-identifier' => 'kb-articles',
                                                    'data-default-order' => get_table_last_order('kb-articles'),
                                                ]); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php include_once(APPPATH . 'views/admin/knowledge_base/group.php'); ?>
<?php init_tail(); ?>
<script>

    $(function () {
        fix_kanban_height(290, 360);
        initKnowledgeBaseTableArticles();
        $(".groups").sortable({
            connectWith: ".article-group",
            helper: 'clone',
            appendTo: '#kan-ban',
            placeholder: "ui-state-highlight-kan-ban-kb",
            revert: true,
            scroll: true,
            scrollSensitivity: 50,
            scrollSpeed: 70,
            start: function (event, ui) {
                $('body').css('overflow', 'hidden');
            },
            stop: function (event, ui) {
                $('body').removeAttr('style');
            },
            update: function (event, ui) {
                if (this === ui.item.parent()[0]) {
                    var articles = $(ui.item).parents('.article-group').find('li');
                    i = 1;
                    var order = [];
                    $.each(articles, function () {
                        i++;
                        order.push([$(this).data('article-id'), i]);
                    });
                    setTimeout(function () {
                        $.post(admin_url + 'knowledge_base/update_kan_ban', {
                            order: order,
                            groupid: $(ui.item.parent()[0]).data('group-id')
                        });
                    }, 100);
                }
            }
        }).disableSelection();

        $('.groups').sortable({
            cancel: '.sortable-disabled'
        });

        setTimeout(function () {
            $('.kb-kan-ban').removeClass('hide');
        }, 200);

        $(".container-fluid").sortable({
            helper: 'clone',
            item: '.kan-ban-col',
            cancel: '.sortable-disabled',
            update: function (event, ui) {
                var order = [];
                var status = $('.kan-ban-col');
                var i = 0;
                $.each(status, function () {
                    order.push([$(this).data('col-group-id'), i]);
                    i++;
                });
                var data = {}
                data.order = order;
                $.post(admin_url + 'knowledge_base/update_groups_order', data);
            }
        });
        // Status color change
        $('body').on('click', '.kb-kan-ban .cpicker', function () {
            var color = $(this).data('color');
            var group_id = $(this).parents('.panel-heading-bg').data('group-id');
            $.post(admin_url + 'knowledge_base/change_group_color', {
                color: color,
                group_id: group_id
            });
        });
        $('.toggle-articles-list').on('click', function () {
            var list_tab = $('#list_tab');
            if (list_tab.hasClass('active')) {
                list_tab.css('display', 'none').removeClass('active');
                $('.kan-ban-tab').css('display', 'block');
                fix_kanban_height(290, 360);
                mainWrapperHeightFix();
            } else {
                list_tab.css('display', 'block').addClass('active');
                $('.kan-ban-tab').css('display', 'none');
            }
        });
    });

    function initKnowledgeBaseTableArticles() {
        var KB_Articles_ServerParams = {};
        $.each($('._hidden_inputs._filters input'), function () {
            KB_Articles_ServerParams[$(this).attr('name')] = '[name="' + $(this).attr('name') + '"]';
        });
        $('._filter_data').toggleClass('hide');
        initDataTable('.table-articles', window.location.href, undefined, undefined, KB_Articles_ServerParams, [2, 'desc']);
    }
</script>
</body>
</html>
