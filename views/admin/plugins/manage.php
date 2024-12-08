<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="_buttons">
                            <a href="<?php echo admin_url('uplicrm_website_builder/plugin'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_plugin'); ?></a>
                        </div>
                        <div class="clearfix"></div>
                        <hr class="hr-panel-heading" />
                        <div class="clearfix"></div>
                        <?php render_datatable([
                            _l('plugin_name'),
                            _l('plugin_description'),
                            _l('plugin_price'),
                            _l('options')
                        ], 'uplicrm-website-builder-plugins'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script>
    $(function(){
        initDataTable('.table-uplicrm-website-builder-plugins', admin_url + 'uplicrm_website_builder/plugins_table', [3], [3]);
    });
</script>
</body>

</html>