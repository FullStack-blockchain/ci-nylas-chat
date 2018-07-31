<?php init_single_head(); ?>
<style>

</style>
<main>
    <div id="wrapper">
        <?php init_aside(); ?>

        <div class="content-wrapper">

            <?php init_header(); ?>

            <div class="content custom-scrollbar">

                <div id="manage_roles" class="page-layout simple left-sidebar-floating">

                    <div class="page-content p-4 p-sm-6">

		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-body">
						<div class="_buttons">
							<a href="<?php echo admin_url('roles/role'); ?>" class="btn btn-secondary pull-left display-block"><?php echo _l('new_role'); ?></a>
						</div>
						<div class="clearfix"></div>
						<hr class="mt-4 mb-4" />
						<div class="clearfix"></div>
						<?php render_datatable(array(
							_l('roles_dt_name'),
							_l('options')
							),'roles'); ?>
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
		initDataTable('.table-roles', window.location.href, [1], [1]);
	</script>
</body>
</html>
