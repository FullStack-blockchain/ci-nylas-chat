<div class="modal fade _event" id="newEventModal">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><?php echo _l('utility_calendar_new_event_title'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>
            <?php echo form_open('admin/utilities/calendar', array('id' => 'calendar-event-form')); ?>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <div class="form-group mb-6">
                            <input type="text" class="form-control" name="title" id="title" placeholder=" "/>
                            <label for="title"><?php echo _l('utility_calendar_new_event_placeholder'); ?></label>
                        </div>

                        <div class="form-group mb-0">
                            <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                            <label for="description"><?php echo _l('event_description'); ?></label>
                        </div>

                        <?php echo render_datetime_input('start', 'utility_calendar_new_event_start_date', null, [], [], 'mb-0'); ?>
                        <?php echo render_datetime_input('end', 'utility_calendar_new_event_end_date', null, [], [], 'mb-0'); ?>

                        <div class="form-group mb-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="reminder_before"><?php echo _l('event_notification'); ?></label>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="reminder_before" value="30"
                                               id="reminder_before">
                                        <span class="input-group-addon">
                                            <i class="fa fa-question-circle" data-toggle="tooltip"
                                               data-title="<?php echo _l('reminder_notification_placeholder'); ?>"></i></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-t-0-75rem">
                                        <select name="reminder_before_type" id="reminder_before_type"
                                                class="form-control" data-width="100%">
                                            <option value="minutes" selected><?php echo _l('minutes'); ?></option>
                                            <option value="hours"><?php echo _l('hours'); ?></option>
                                            <option value="days"><?php echo _l('days'); ?></option>
                                            <option value="weeks"><?php echo _l('weeks'); ?></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p class="bold"><?php echo _l('event_color'); ?></p>
                        <?php
                        $event_colors = '';
                        $favourite_colors = get_system_favourite_colors();
                        $i = 0;
                        foreach ($favourite_colors as $color) {
                            $color_selected_class = 'cpicker-small';
                            if ($i == 0) {
                                $color_selected_class = 'cpicker-big';
                            }
                            $event_colors .= "<div class='calendar-cpicker cpicker " . $color_selected_class . "' data-color='" . $color . "' style='background:" . $color . ";border:1px solid " . $color . "'></div>";
                            $i++;
                        }
                        echo '<div class="cpicker-wrapper">';
                        echo $event_colors;
                        echo '</div>';
                        echo form_hidden('color', $favourite_colors[0]);
                        ?>
                        <div class="clearfix"></div>
                        <hr class="m-t-20 m-b-20"/>
                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" name="public" id="public"/>
                                <span class="checkbox-icon"></span>
                                <span><?php echo _l('utility_calendar_new_event_make_public'); ?></span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo _l('close'); ?></button>
                <button type="submit" class="btn btn-info"><?php echo _l('submit'); ?></button>
            </div>
            <?php echo form_close(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
