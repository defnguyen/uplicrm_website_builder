<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('uplicrm_website_builder/website'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_website'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable([
                            _l('website_name'),
                            _l('website_subdomain'),
                            _l('website_plan'),
                            _l('website_status'),
                            _l('website_created_at'),
                            _l('options')
                        ], 'uplicrm-website-builder-websites'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-uplicrm-website-builder-websites', admin_url + 'uplicrm_website_builder/websites_table', [5], [5]);
    });
</script>
</body>

</html>