<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('uplicrm_website_builder/service_plan'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_service_plan'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable([
                            _l('service_plan_name'),
                            _l('service_plan_description'),
                            _l('service_plan_price'),
                            _l('service_plan_type'),
                            _l('options')
                        ], 'uplicrm-website-builder-service-plans'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-uplicrm-website-builder-service-plans', admin_url + 'uplicrm_website_builder/service_plans_table', [4], [4]);
    });
</script>
</body>

</html>