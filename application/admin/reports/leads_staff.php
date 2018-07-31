<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content">

                <div id="manage_lead_staff_reports" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <p class="text-info inline-block" data-placement="bottom" data-toggle="tooltip"
                                           data-title="<?php echo _l('leads_report_converted_notice'); ?>"><i
                                                    class="fa fa-question-circle"></i></p>
                                        <a href="<?php echo admin_url('reports/leads'); ?>"
                                           class="btn btn-success"><?php echo _l('switch_to_staff_report'); ?></a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 animated fadeIn">
                                <div class="card mb-3">
                                    <div class="card-body">
                                        <?php echo form_open($this->uri->uri_string() . '?type=staff'); ?>
                                        <div class="row mb-4">
                                            <div class="col-md-3">
                                                <?php echo render_date_input('staff_report_from_date', 'from_date', $this->input->post('staff_report_from_date'),[],[],'pt-0'); ?>
                                            </div>
                                            <div class="col-md-3">
                                                <?php echo render_date_input('staff_report_to_date', 'to_date', $this->input->post('staff_report_to_date'),[],[],'pt-0'); ?>
                                            </div>
                                            <div class="col-md-3 text-left">
                                                <button type="submit"
                                                        class="btn btn-info label-margin mt-4"><?php echo _l('generate'); ?></button>
                                            </div>
                                        </div>
                                        <?php echo form_close(); ?>
                                        <hr/>
                                        <div class="relative" style="max-height:380px">
                                            <canvas class="leads-staff-report mtop20" height="380"
                                                    id="leads-staff-report"></canvas>
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
    window.onload = function () {
        new Chart($('#leads-staff-report'), {
            data:<?php echo $leads_staff_report; ?>,
            type: 'bar',
            options: {responsive: true, maintainAspectRatio: false}
        })
    };
</script>
</body>
</html>
