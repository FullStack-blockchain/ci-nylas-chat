<?php init_single_head(); ?>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>
            <div class="content">

                <div id="manage_expenses_vs_income_reports" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card">
                                    <div class="card-body">
                                        <?php if (count($years) > 1 || (count($years) == 1 && $years[0] != date('Y'))) { ?>
                                            <select class="selectpicker" name="expense_year"
                                                    onchange="change_expense_report_year(this.value);"
                                                    data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                <?php foreach ($years as $year) { ?>
                                                    <option value="<?php echo $year; ?>"<?php if ($year == $report_year) {
                                                        echo ' selected';
                                                    } ?>>
                                                        <?php echo $year; ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                            <hr class="mt-4 mb-4"/>
                                        <?php } ?>
                                        <p class="text-danger bold">
                                            <?php echo _l('amount_display_in_base_currency'); ?>
                                        </p>
                                        <div class="relative" style="max-height:600px;">
                                            <canvas class="chart" height="600" id="report-expense-vs-income"></canvas>
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
    $(function () {
        chartExpenseVsIncome = new Chart($('#report-expense-vs-income'), {
            type: 'bar',
            data:<?php echo $chart_expenses_vs_income_values; ?>,
            options: {
                maintainAspectRatio: false, scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                        }
                    }]
                },
            }
        });
    });

    function change_expense_report_year(year) {
        window.location.href = admin_url + 'reports/expenses_vs_income/' + year;
    }
</script>
</body>
</html>
