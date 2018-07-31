<?php init_single_head(); ?>
<style>
    .goal-progress {text-align: center;position: relative;}
    table .goal-percent {font-size: 10px;text-align: center;left: 1px;top: 22px;line-height: 0;}
    .goal-percent {position: absolute;top: 100px;left: 0;width: 100%;text-align: center;line-height: 40px;font-size: 50px;}
</style>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="goals-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="card">
                            <?php if (has_permission('goals', '', 'create')) { ?>
                                <div class="card-header">
                                    <a href="<?php echo admin_url('goals/goal'); ?>"
                                       class="btn btn-secondary pull-left display-block"><?php echo _l('new_goal'); ?></a>
                                </div>
                            <?php } ?>
                            <div class="card-body">
                                <?php render_datatable(array(
                                    _l('goal_subject'),
                                    _l('staff_member'),
                                    _l('goal_achievement'),
                                    _l('goal_start_date'),
                                    _l('goal_end_date'),
                                    _l('goal_type'),
                                    _l('goal_progress'),
                                ), 'goals'); ?>
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
    $(function () {
        initDataTable('.table-goals', window.location.href, [6], [6]);
        $('.table-goals').DataTable().on('draw', function () {
            var rows = $('.table-goals').find('tr');
            $.each(rows, function () {
                var td = $(this).find('td').eq(6);
                var percent = $(td).find('input[name="percent"]').val();
                $(td).find('.goal-progress').circleProgress({
                    value: percent,
                    size: 45,
                    animation: false,
                    fill: {
                        gradient: ["#28b8da", "#059DC1"]
                    }
                })
            })
        })
    });
</script>
</body>
</html>
