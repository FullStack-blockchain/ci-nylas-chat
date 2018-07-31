<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content custom-scrollbar">

                <div id="goals-manage" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                            <div class="col-md-<?php if (!isset($goal)) {
                                echo '8 col-md-offset-2';
                            } else {
                                echo '6  p-0 pr-3';
                            } ?>">
                                <div class="card">
                                    <div class="card-header"><h4 class="no-margin"><?php echo $title; ?></h4></div>
                                    <div class="card-body">
                                        <?php echo form_open($this->uri->uri_string()); ?>
                                        <?php $attrs = (isset($goal) ? array() : array('autofocus' => true)); ?>
                                        <?php $value = (isset($goal) ? $goal->subject : ''); ?>
                                        <?php echo render_input('subject', 'goal_subject', $value, 'text', $attrs); ?>
                                        <div class="form-group select-placeholder">
                                            <label for="goal_type"
                                                   class="control-label"><?php echo _l('goal_type'); ?></label>
                                            <select name="goal_type" class="selectpicker" data-width="100%"
                                                    data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                <option value=""></option>
                                                <?php foreach (get_goal_types() as $type) { ?>
                                                    <option value="<?php echo $type['key']; ?>"
                                                            data-subtext="<?php if (isset($type['subtext'])) {
                                                                echo _l($type['subtext']);
                                                            } ?>" <?php if (isset($goal) && $goal->goal_type == $type['key']) {
                                                        echo 'selected';
                                                    } ?>><?php echo _l($type['lang_key']); ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <?php
                                        $selected = (isset($goal) ? $goal->staff_id : '');
                                        echo render_select('staff_id', $members, array('staffid', array('firstname', 'lastname')), 'staff_member', $selected, array('data-none-selected-text' => _l('all_staff_members'))); ?>
                                        <?php $value = (isset($goal) ? $goal->achievement : ''); ?>
                                        <?php echo render_input('achievement', 'goal_achievement', $value, 'number'); ?>
                                        <?php $value = (isset($goal) ? _d($goal->start_date) : _d(date('Y-m-d'))); ?>
                                        <?php echo render_date_input('start_date', 'goal_start_date', $value); ?>
                                        <?php $value = (isset($goal) ? _d($goal->end_date) : ''); ?>
                                        <?php echo render_date_input('end_date', 'goal_end_date', $value); ?>
                                        <div class="hide" id="contract_types">
                                            <?php $selected = (isset($goal) ? $goal->contract_type : ''); ?>
                                            <?php echo render_select('contract_type', $contract_types, array('id', 'name'), 'goal_contract_type', $selected); ?>
                                        </div>
                                        <?php $value = (isset($goal) ? $goal->description : ''); ?>
                                        <?php echo render_textarea('description', 'goal_description', $value); ?>

                                        <div class="form-check mt-4">
                                            <label class="form-check-label">
                                                <input type="checkbox" name="notify_when_achieve" class="form-check-input"
                                                       id="notify_when_achieve" <?php if (isset($goal)) {
                                                    if ($goal->notify_when_achieve == 1) {
                                                        echo 'checked';
                                                    }
                                                } else {
                                                    echo 'checked';
                                                } ?>>
                                                <span class="checkbox-icon"></span>
                                                <span><?php echo _l('goal_notify_when_achieve'); ?></span>
                                            </label>
                                        </div>

                                        <div class="form-check mt-1 mb-4">
                                            <label class="form-check-label">
                                                <input type="checkbox" name="notify_when_fail" class="form-check-input"
                                                       id="notify_when_fail" <?php if (isset($goal)) {
                                                    if ($goal->notify_when_fail == 1) {
                                                        echo 'checked';
                                                    }
                                                } else {
                                                    echo 'checked';
                                                } ?>>
                                                <span class="checkbox-icon"></span>
                                                <span><?php echo _l('goal_notify_when_fail'); ?></span>
                                            </label>
                                        </div>

                                        <button type="submit"
                                                class="btn btn-info pull-right"><?php echo _l('submit'); ?></button>
                                        <?php echo form_close(); ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset($goal)) { ?>
                                <div class="col-md-6  p-0 pl-3">
                                    <div class="card">
                                        <div class="card-header"><h4 class="no-margin"><?php echo _l('goal_achievement'); ?><span class="badge badge-pill badge-primary">PRIMARY</span></h4></div>
                                        <div class="card-body">
                                            <?php
                                            $show_acchievement_ribbon = false;
                                            $help_text = '';
                                            if ($goal->end_date < date('Y-m-d')) {
                                                $achieve_indicator_class = 'danger';
                                                $lang_key = 'goal_failed';
                                                $finished = true;
                                                $notify_type = 'failed';

                                                if ($goal->notified == 1) {
                                                    $help_text = '<p class="text-muted text-center">' . _l('goal_staff_members_notified_about_failure') . '</p>';
                                                }

                                                $show_acchievement_ribbon = true;
                                            } else if ($achievement['percent'] == 100) {

                                                $achieve_indicator_class = 'success';
                                                $show_acchievement_ribbon = true;
                                                if ($goal->notified == 1) {
                                                    $help_text = '<p class="text-muted text-center">' . _l('goal_staff_members_notified_about_achievement') . '</p>';
                                                }

                                                $notify_type = 'success';
                                                $finished = true;
                                                $lang_key = 'goal_achieved';

                                            } else if ($achievement['percent'] >= 80) {
                                                $achieve_indicator_class = 'warning';
                                                $show_acchievement_ribbon = true;
                                                $lang_key = 'goal_close';
                                            }
                                            if ($show_acchievement_ribbon == true) {
                                                echo '<div class="ribbon ' . $achieve_indicator_class . '"><span>' . _l($lang_key) . '</span></div>';
                                            }

                                            ?>
                                            <h3 class="text-center no-mtop"><?php echo _l('goal_result_heading'); ?>
                                                <small><?php echo _l('goal_total', $achievement['total']); ?></small>
                                            </h3>
                                            <?php if ($goal->goal_type == 1) {
                                                echo '<p class="text-muted text-center no-mbot">' . _l('goal_income_shown_in_base_currency') . '</p>';
                                            }
                                            if ((isset($finished) && $goal->notified == 0) && ($goal->notify_when_achieve == 1 || $goal->notify_when_fail == 1)) {
                                                echo '<p class="text-center text-info">' . _l('goal_notify_when_end_date_arrives') . '</p>';

                                                echo '<div class="text-center"><a href="' . admin_url('goals/notify/' . $goal->id . '/' . $notify_type) . '" class="btn btn-default">' . _l('goal_notify_staff_manually') . '</a></div>';
                                            }
                                            echo $help_text;
                                            ?>
                                            <div class="achievement mt-5" data-toggle="tooltip"
                                                 title="<?php echo _l('goal_total', $achievement['total']); ?>">
                                                <div class="goal-progress" data-thickness="40" data-reverse="true">
                                                    <strong class="goal-percent"></strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php init_tail(); ?>
<script>
    $(function () {
        _validate_form($('form'), {
            subject: 'required',
            goal_type: 'required',
            end_date: 'required',
            start_date: 'required',
            contract_type: {
                required: {
                    depends: function (element) {
                        return $('select[name="goal_type"]').val() == 5 || $('select[name="goal_type"]').val() == 7;
                    }
                }
            }
        });
        <?php if(isset($goal)){ ?>
        var circle = $('.goal-progress').circleProgress({
            value: '<?php echo $achievement['progress_bar_percent']; ?>',
            size: 250,
            fill: {
                gradient: ["#28b8da", "#059DC1"]
            }
        }).on('circle-animation-progress', function (event, progress, stepValue) {
            $(this).find('strong.goal-percent').html(parseInt(100 * stepValue) + '<i>%</i>');
        });
        <?php } ?>
        var goal_type = $('select[name="goal_type"]').val();
        if (goal_type == 5 || goal_type == 7) {
            $('#contract_types').removeClass('hide');
        }
        $('select[name="goal_type"]').on('change', function () {
            var goal_type = $(this).val();
            if (goal_type == 5 || goal_type == 7) {
                $('#contract_types').removeClass('hide');
            } else {
                $('#contract_types').addClass('hide');
                $('#contract_type').selectpicker('val', '');
            }
        });
    });
</script>
</body>
</html>
