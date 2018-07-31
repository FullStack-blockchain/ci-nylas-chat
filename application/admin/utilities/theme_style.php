<?php init_single_head(); ?>
<?php function render_theme_styling_picker($id, $value, $target, $css, $additional = '')
{
    echo '<div class="input-group mbot15 colorpicker-component" data-target="' . $target . '" data-css="' . $css . '" data-additional="' . $additional . '">
    <input type="text" value="' . $value . '" data-id="' . $id . '" class="form-control" />
    <span class="input-group-addon"><i></i></span>
    </div>';
}

$tags = get_styling_areas('tags');
?>
<style>
    .input-group .input-group-addon{
        border: 1px solid #949494 !important;
        padding: 2px 6px !important;
    }
</style>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>

            <div class="content custom-scrollbar">

                <div id="manage_theme_style" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-4">
                                    <div class="card-body">
                                        <a href="#" onclick="save_theme_style(); return false;" class="btn btn-info">Save</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 picker">
                                <div class="card p-0">
                                    <ul class="nav nav-pills flex-column">
                                        <li role="presentation" class="nav-item active">
                                            <a href="#tab_admin_styling" aria-controls="tab_admin_styling" role="tab"
                                               data-toggle="tab" class="nav-link">
                                                Admin
                                            </a>
                                        </li>
                                        <li role="presentation" class="nav-item">
                                            <a href="#tab_customers_styling" aria-controls="tab_customers_styling"
                                               role="tab"
                                               data-toggle="tab" class="nav-link">
                                                Customers
                                            </a>
                                        </li>
                                        <li role="presentation" class="nav-item">
                                            <a href="#tab_buttons_styling" aria-controls="tab_buttons_styling" role="tab"
                                               data-toggle="tab" class="nav-link">
                                                Buttons
                                            </a>
                                        </li>
                                        <li role="presentation" class="nav-item">
                                            <a href="#tab_tabs_styling" aria-controls="tab_tabs_styling" role="tab"
                                               data-toggle="tab" class="nav-link">
                                                Tabs
                                            </a>
                                        </li>
                                        <li role="presentation" class="nav-item">
                                            <a href="#tab_modals_styling" aria-controls="tab_modals_styling" role="tab"
                                               data-toggle="tab" class="nav-link">
                                                Modals
                                            </a>
                                        </li>
                                        <li role="presentation" class="nav-item">
                                            <a href="#tab_general_styling" aria-controls="tab_general_styling" role="tab"
                                               data-toggle="tab" class="nav-link">
                                                General
                                            </a>
                                        </li>
                                        <?php if (count($tags) > 0) { ?>
                                            <li role="presentation" class="nav-item">
                                                <a href="#tab_styling_tags" aria-controls="tab_styling_tags" role="tab"
                                                   data-toggle="tab" class="nav-link">
                                                    Tags
                                                </a>
                                            </li>
                                        <?php } ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="card mb-4">
                                    <div class="card-body pickers">

                                        <div class="tab-content">
                                            <div role="tabpanel" class="tab-pane ptop10 active" id="tab_admin_styling">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php
                                                        foreach (get_styling_areas('admin') as $area) { ?>
                                                            <label class="bold mbot10 inline-block"><?php echo $area['name']; ?></label>
                                                            <?php render_theme_styling_picker($area['id'], get_custom_style_values('admin', $area['id']), $area['target'], $area['css'], $area['additional_selectors']); ?>
                                                            <hr class="mt-4 mb-4"/>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane ptop10" id="tab_customers_styling">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php foreach (get_styling_areas('customers') as $area) { ?>
                                                            <label class="bold mbot10 inline-block"><?php echo $area['name']; ?></label>
                                                            <?php render_theme_styling_picker($area['id'], get_custom_style_values('customers', $area['id']), $area['target'], $area['css'], $area['additional_selectors']); ?>
                                                            <hr class="mt-4 mb-4"/>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane ptop10" id="tab_buttons_styling">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php foreach (get_styling_areas('buttons') as $area) { ?>
                                                            <label class="bold mbot10 inline-block"><?php echo $area['name']; ?></label>
                                                            <?php render_theme_styling_picker($area['id'], get_custom_style_values('buttons', $area['id']), $area['target'], $area['css'], $area['additional_selectors']); ?>
                                                            <?php if (isset($area['example'])) {
                                                                echo $area['example'];
                                                            } ?>
                                                            <div class="clearfix"></div>
                                                            <hr class="mt-4 mb-4"/>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane ptop10" id="tab_tabs_styling">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php foreach (get_styling_areas('tabs') as $area) { ?>
                                                            <label class="bold mbot10 inline-block"><?php echo $area['name']; ?></label>
                                                            <?php render_theme_styling_picker($area['id'], get_custom_style_values('tabs', $area['id']), $area['target'], $area['css'], $area['additional_selectors']); ?>
                                                            <hr class="mt-4 mb-4"/>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane ptop10" id="tab_modals_styling">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php foreach (get_styling_areas('modals') as $area) { ?>
                                                            <label class="bold mbot10 inline-block"><?php echo $area['name']; ?></label>
                                                            <?php render_theme_styling_picker($area['id'], get_custom_style_values('modals', $area['id']), $area['target'], $area['css'], $area['additional_selectors']); ?>
                                                            <hr class="mt-4 mb-4"/>
                                                        <?php } ?>
                                                        <div class="modal-content theme_style_modal_example">
                                                            <div class="modal">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">Example Modal Heading</h4>
                                                                    <span class="color-white">Sample Text</span>
                                                                    <button type="button" class="close"
                                                                            data-dismiss="modal"
                                                                            aria-label="Close"><span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Modal Body
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div role="tabpanel" class="tab-pane ptop10" id="tab_general_styling">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <?php foreach (get_styling_areas('general') as $area) { ?>
                                                            <label class="bold mbot10 inline-block"><?php echo $area['name']; ?></label>
                                                            <?php render_theme_styling_picker($area['id'], get_custom_style_values('general', $area['id']), $area['target'], $area['css'], $area['additional_selectors']); ?>
                                                            <?php if (isset($area['example'])) {
                                                                echo $area['example'];
                                                            } ?>
                                                            <hr class="mt-4 mb-4"/>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php if (count($tags) > 0) { ?>
                                                <div role="tabpanel" class="tab-pane ptop10" id="tab_styling_tags">
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <?php foreach ($tags as $area) { ?>
                                                                <label class="bold mbot10 inline-block"><?php echo $area['name']; ?></label>
                                                                <?php render_theme_styling_picker($area['id'], get_custom_style_values('tags', $area['id']), $area['target'], $area['css'], $area['additional_selectors']); ?>
                                                                <?php if (isset($area['example'])) {
                                                                    echo $area['example'];
                                                                } ?>
                                                                <hr class="mt-4 mb-4"/>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>
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

<?php init_tail(); ?>
<script>
    var pickers = $('.colorpicker-component');
    $(function () {
        $.each(pickers, function () {
            $(this).colorpicker({
                format: "hex"
            });
            $(this).colorpicker().on('changeColor', function (e) {
                var color = e.color.toHex();
                var _class = 'custom_style_' + $(this).find('input').data('id');
                var val = $(this).find('input').val();
                if (val == '') {
                    $('.' + _class).remove();
                    return false;
                }
                var append_data = '';
                var additional = $(this).data('additional');
                additional = additional.split('+');
                if (additional.length > 0 && additional[0] != '') {
                    $.each(additional, function (i, add) {
                        add = add.split('|');
                        append_data += add[0] + '{' + add[1] + ':' + color + '!important;}';
                    });
                }
                append_data += $(this).data('target') + '{' + $(this).data('css') + ':' + color + ' !important;}';
                if ($('head').find('.' + _class).length > 0) {
                    $('head').find('.' + _class).html(append_data);
                } else {
                    $("<style />", {
                        class: _class,
                        type: 'text/css',
                        html: append_data
                    }).appendTo("head");
                }
            });
        });
    });

    function save_theme_style() {
        var data = [];
        $.each(pickers, function () {
            var color = $(this).find('input').val();
            if (color != '') {
                var _data = {};
                _data.id = $(this).find('input').data('id');
                _data.color = color;
                data.push(_data);
            }
        });
        $.post(admin_url + 'utilities/save_theme_style', {
            data: JSON.stringify(data)
        }).done(function () {
            window.location.reload();
        });
    }
</script>
</body>
</html>
