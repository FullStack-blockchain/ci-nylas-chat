<?php init_single_head(); ?>
<style>
    .warning-bg {
        background: #FF6F00 !important;
        color: #fff !important;
        border: 1px solid #FF6F00 !important;
    }

    .success-bg {
        background: #84c529 !important;
        color: #fff !important;
        border: 1px solid #84c529 !important;
    }

    .primary-bg {
        background: #03a9f4;
        color: #fff !important;
        border: 1px solid #03a9f4;
    }

    .info-bg {
        background: #03A9F4 !important;
        color: #fff !important;
        border: 1px solid #03A9F4 !important;
    }

    .danger-bg {
        background: #FC2D42 !important;
        color: #fff !important;
        border: 1px solid #FC2D42 !important;
    }

    .panel-body.todo-body {
        padding: 0;
    }

    .todo-title {
        margin: 0;
        line-height: 30px;
        padding: 0 0 0 17px;
        font-weight: 500;
        font-size: 13px;
    }

    ul.todo {
        margin-bottom: 0;
    }

    ul.todo li {
        display: block;
        position: relative;
        overflow: hidden;
        margin: 0 5px;
        border-bottom: 1px solid #f0f0f0;
    }

    .dragger {
        cursor: move;
        background: url(../images/dragger.png) 1px 11px no-repeat;
    }

    li.no-todos {
        background: none;
    }

    ul.todo {
        list-style: none !important;
    }

    ul.todo li.ui-sortable-handle:last-child,
    ul.todo li:last-child {
        border-bottom: 0;
    }

    .todo-description {
        position: relative;
        padding: 10px 10px 0 10px;
        width: 100%;
        display: block;
    }

    body.rtl .todo-date {
        padding-top: 10px;
    }

    body.rtl .todo-checkbox {
        padding-right: 40px !important;
    }

    .todo-date {
        padding: 0 0 10px 0;
        display: block;
    }

    .todo-checkbox {
        padding-left: 40px;
        float: left;
    }

    .sortable {
        min-height: 60px;
    }

    .no-radius {
        border-radius: 0;
    }
</style>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content">

                <div id="manage_main_menu" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="_buttons">
                                            <a href="#__todo" data-toggle="modal" class="btn btn-info">
                                                <?php echo _l('new_todo'); ?>
                                            </a>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr class="mt-4 mb-4"/>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="card events animated fadeIn">
                                                    <div class="card-body todo-body">
                                                        <h4 class="todo-title warning-bg"><i class="fa fa-warning"></i>
                                                            <?php echo _l('unfinished_todos_title'); ?></h4>
                                                        <ul class="list-unstyled todo unfinished-todos todos-sortable">
                                                            <li class="padding no-todos hide ui-state-disabled">
                                                                <?php echo _l('no_unfinished_todos_found'); ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center padding mt-5">
                                                        <a href="#"
                                                           class="btn btn-default text-center unfinished-loader"><?php echo _l('load_more'); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="card animated fadeIn">
                                                    <div class="card-body todo-body">
                                                        <h4 class="todo-title info-bg"><i class="fa fa-check"></i>
                                                            <?php echo _l('finished_todos_title'); ?></h4>
                                                        <ul class="list-unstyled todo finished-todos todos-sortable">
                                                            <li class="padding no-todos hide ui-state-disabled">
                                                                <?php echo _l('no_finished_todos_found'); ?>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-12 text-center padding mt-5">
                                                        <a href="#" class="btn btn-default text-center finished-loader">
                                                            <?php echo _l('load_more'); ?>
                                                        </a>
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
        </div>
    </div>
</main>

<?php $this->load->view('admin/todos/_todo.php'); ?>
<?php init_tail(); ?>
<script>
    $(function () {
        var total_pages_unfinished = '<?php echo $total_pages_unfinished; ?>';
        var total_pages_finished = '<?php echo $total_pages_finished; ?>';
        var page_unfinished = 0;
        var page_finished = 0;
        $('.unfinished-loader').on('click', function (e) {
            e.preventDefault();
            if (page_unfinished <= total_pages_unfinished) {
                $.post(window.location.href, {
                    finished: 0,
                    todo_page: page_unfinished
                }).done(function (response) {
                    response = JSON.parse(response);
                    if (response.length == 0) {
                        $('.unfinished-todos .no-todos').removeClass('hide');
                    }

                    $.each(response, function (i, obj) {
                        $('.unfinished-todos').append(render_li_items(0, obj));
                    });
                    page_unfinished++;
                });

                if (page_unfinished >= total_pages_unfinished - 1) {
                    $(".unfinished-loader").addClass("disabled");
                }
            }
        });

        $('.finished-loader').on('click', function (e) {
            e.preventDefault();
            if (page_finished <= total_pages_finished) {
                $.post(window.location.href, {
                    finished: 1,
                    todo_page: page_finished
                }).done(function (response) {
                    response = JSON.parse(response);

                    if (response.length == 0) {
                        $('.finished-todos .no-todos').removeClass('hide');
                    }
                    $.each(response, function (i, obj) {
                        $('.finished-todos').append(render_li_items(1, obj));
                    });

                    page_finished++;
                });

                if (page_finished >= total_pages_finished - 1) {
                    $(".finished-loader").addClass("disabled");
                }
            }
        });
        $('.unfinished-loader').click();
        $('.finished-loader').click();
    });

    function render_li_items(finished, obj) {
        var todo_finished_class = '';
        var checked = '';
        if (finished == 1) {
            todo_finished_class = ' line-throught';
            checked = 'checked';
        }
        return '<li><div class="media"><div class="media-left no-padding-right"><div class="dragger todo-dragger"></div> <input type="hidden" value="' + finished + '" name="finished"><input type="hidden" value="' + obj.item_order + '" name="todo_order">' +
            '<div class="form-check mt-2"><label class="form-check-label"><input type="checkbox" name="todo_id" value="' + obj.todoid + '" ' + checked + ' class="form-check-input"/><span class="checkbox-icon"></span><span></span></label></div>' +
            '</div> <div class="media-body"><p class="todo-description' + todo_finished_class + ' no-padding-left">' + obj.description + '<a href="#" onclick="delete_todo_item(this,' + obj.todoid + '); return false;" class="pull-right text-muted"><i class="fa fa-remove"></i></a><a href="#" onclick="edit_todo_item(' + obj.todoid + '); return false;" class="pull-right text-muted mright5"><i class="fa fa-pencil-square-o"></i></a></p><small class="todo-date">' + obj.dateadded + '</small></div></div></li>';
    }
</script>
</body>
</html>
